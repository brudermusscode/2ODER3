const DEFAULT_VOLUME = 0.5;
let __hide_ui_timeout = null;
let __hide_ui_timeout_ms = 3000;

export const start = (wrapper) => {
  let video = wrapper.find("video");

  if (!wrapper.hasAttribute("has-played"))
    wrapper.setAttribute("has-played", "");

  wrapper.activate();
  video.play();
  __player.active = true;
  __player.object = video;

  let track = wrapper.find("vt-duration-track");
  let progress;

  // Manipulate the duration track on running video.
  video.addEventListener("timeupdate", () => {
    progress = (video.currentTime / video.duration) * 100;
    track.style.width = `${progress}%`;
  });

  // Stop the video, when ended.
  video.addEventListener("ended", () => {
    stop(wrapper);
    set_time(wrapper, 0);
  });
};

export const stop = (wrapper) => {
  let video = wrapper.find("video");

  wrapper.deactivate();
  video.pause();
  __player.active = false;
  __player.object = video;
};

export const set_time = (wrapper, sec) => {
  let video = wrapper.find("video");

  if (sec >= video.duration) return (video.currentTime = video.duration);
  if (sec <= 0) return (video.currentTime = 0);

  video.currentTime = sec;
};

export const mute = (wrapper) => {
  let video = wrapper.find("video");
  let volume = wrapper.find("volume");
  let track = volume.find("volume-track");

  video.volume = 0;
  track.style.height = 0 + "%";
  volume.setAttribute("muted", "");
};

export const unmute = (wrapper) => {
  let video = wrapper.find("video");
  let volume = wrapper.find("volume");

  volume.removeAttribute("muted");
};

export const is_muted = (wrapper) => {
  return wrapper.find("volume").hasAttribute("muted");
};

export const set_volume = (wrapper, volume) => {
  let track = wrapper.find("volume volume-track");
  let volume = Number(volume);

  if (!is_muted(wrapper) && volume * 100 <= 5) mute(wrapper);
  if (is_muted(wrapper) && volume * 100 > 5) unmute(wrapper);

  track.style.height = volume * 100 + "%";

  wrapper.find("video").volume = volume;
  __player.volume = volume;
  localStorage.setItem("__player_volume", volume);
};

const fullscreen = (wrapper) => {
  if (document.fullscreenElement) {
    document.exitFullscreen();
  } else {
    wrapper.requestFullscreen();
  }
};

const cinema_mode = () => {
  if (document.body.hasAttribute("cinema-mode")) {
    document.body.removeAttribute("cinema-mode");
  } else {
    document.body.setAttribute("cinema-mode", "");
  }
};

const hide_ui_w_timeout = (wrapper) => {
  __hide_ui_timeout = setTimeout(() => {
    wrapper.setAttribute("inactive", "");
  }, __hide_ui_timeout_ms);
};

const show_ui = (wrapper) => {
  clearTimeout(__hide_ui_timeout);
  wrapper.removeAttribute("inactive");
};

const init = (wrapper) => {
  let video = wrapper.find("video");
  let volume = localStorage.getItem("__player_volume") ?? DEFAULT_VOLUME;

  set_volume(wrapper, volume);
};

$(function () {
  //
  //
  let video_wrapper = document.find("video-wrapper");

  init(video_wrapper);

  let __mouse_down = false;

  document.addEventListener("mousedown", () => {
    __mouse_down = true;
  });

  document.addEventListener("mouseup", () => {
    __mouse_down = false;
  });

  $(document).on("mousedown", "video-wrapper", function (e) {
    show_ui(this);
  });

  $(document).on("click", "video-wrapper", function (e) {
    show_ui(this);

    if (__player.active && !__mouse_down) {
      hide_ui_w_timeout(this);
    }
  });

  $(document).on("mousemove", "video-wrapper", function (e) {
    show_ui(this);

    if (!__player.active || __mouse_down) return;

    hide_ui_w_timeout(this);
  });

  $(document).on(
    "click",
    "video-wrapper fullscreen, video-wrapper cinema-mode",
    function (e) {
      let wrapper = this.closest("video-wrapper");

      if (e.target.closest("fullscreen")) fullscreen(wrapper);
      else if (e.target.closest("cinema-mode")) cinema_mode(wrapper);
    },
  );

  $(document).on("mousedown", "video-wrapper volume", function (e) {
    let wrapper = this.closest("video-wrapper");

    const dragging = (e) => {
      let volume_y = this.getBoundingClientRect().y;
      let volume_h = this.clientHeight; // => 100 %
      let click_y = e.clientY - volume_y; // => x
      let track_h = (click_y * 100) / volume_h;

      if (track_h >= 100) {
        track_h = 100;
      } else if (track_h <= 0) {
        track_h = 0;
      }

      set_volume(wrapper, track_h / 100);
    };

    dragging(e);

    document.addEventListener("mousemove", dragging);
    document.addEventListener(
      "mouseup",
      function () {
        document.removeEventListener("mousemove", dragging);
      },
      { once: true },
    );
  });

  /**
   * Toggle play/pause.
   */
  $(document).on("click", "video-wrapper video-toggle", function (e) {
    let wrapper = this.closest("video-wrapper");

    if (wrapper.hasAttribute("active")) return stop(wrapper);
    return start(wrapper);
  });

  /**
   * Click on duration track, manipulate the width of it and set
   * the currentTime of the video to the requested time based on
   * the click position inside the duration track.
   */
  $(document).on(
    "mousedown",
    "video-wrapper vt-duration-track-wrapper",
    function (e) {
      const dragging = (e) => {
        // Set width of the duration track.
        let wrapper_x = this.getBoundingClientRect().x;
        let wrapper_w = this.clientWidth; // => 100%
        let click_w = e.clientX - wrapper_x; // => x
        let track_w = (click_w * 100) / wrapper_w;

        if (track_w >= 100 || track_w <= 0) return;

        this.find("vt-duration-track").style.width = track_w + "%";

        // Set currentTime of the video.
        let video = this.closest("video-wrapper").find("video");
        video.currentTime = (track_w * video.duration) / 100;
      };

      dragging(e);

      document.addEventListener("mousemove", dragging);
      document.addEventListener(
        "mouseup",
        function () {
          document.removeEventListener("mousemove", dragging);
        },
        { once: true },
      );
    },
  );
});
