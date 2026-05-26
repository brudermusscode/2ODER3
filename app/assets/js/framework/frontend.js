import * as Responder from "./responder";
import { clog } from "../abstract/prototypes";

/**
 * Hides the sidebar to the left if param is given.
 *
 * @param {bool} hide
 */
export const toggle_sidebar = (hide = false) => {
  if (typeof hide === "function") hide = hide();

  if (hide === true) {
    document.find("sidebar")?.activate();
    document.body.setAttribute("sidebar-hidden", "");
  } else {
    document.find("sidebar")?.deactivate();
    document.body.removeAttribute("sidebar-hidden");
  }
};

/**
 * Takes in an object of background which can contain various
 * settings for image, blur etc. The background of the page
 * will be changed accordingly.
 *
 * @param {object} settings
 * @return {Promise}
 */
export const adjust_background = async (settings) => {
  return new Promise((resolve) => {
    let background = document.find("background");
    let background_blur = background.find("blur");
    let background_img = background.find("actual");
    let set = {
      image: settings?.image ?? "/bg-colors.png",
      blur: settings?.blur ?? 12,
      color: settings?.color ?? "rgba(0,0,0,.78)",
    };

    if (set.image !== __page.background?.image) background_img.deactivate();

    background_blur.style.display = set.blur < 1 ? "none" : "block";
    background_blur.style.backdropFilter = `blur(${set.blur}px)`;
    background_blur.style.background = set.color;
    background_img.style.backgroundImage = `url(${set.image})`;

    // Create a new Image object to check for loaded
    // state so we can fade it in when it's fully loaded.
    let img = new Image();

    img.addEventListener("load", () => {
      setTimeout(() => {
        clog("fully loaded!");
        background_img.activate();
      }, 20);
    });

    img.src = set.image;

    clog(img);

    // Set the settings to the global __page object.
    __page.background = set;

    resolve(1);
  });
};

/**
 * Sets the frontend to be loading.
 */
export const load = async () => {
  __page.is_loading = true;

  let overlay = document.find("page-loader");

  if (overlay) overlay.setAttribute("visible", true);
};

/**
 * Unsets the loading state of the frontend.
 */
export const unload = async () => {
  __page.is_loading = false;

  let overlay = document.find("page-loader");

  setTimeout(() => {
    if (overlay) overlay.setAttribute("visible", false);
  }, 10);
};

/**
 * Scrolls to the top!
 */
export const scroll_to_top = async () => {
  window.scrollTo({ top: 0, behavior: "smooth" });
};

/**
 * Scrolls to a certain place!
 * @param {int} number
 */
export const scroll_to = async (number) => {
  window.scrollTo({ top: number, behavior: "smooth" });
};

/**
 * This boy checks for any images already being loaded on the page
 * startup andd shows them.
 *
 * @param {HTMLImageElement} img
 */
const any_loaded_images = (img) => {
  if (img.complete && img.naturalHeight !== 0) {
    img.setAttribute("loaded", true);
  }
};

/**
 * Preloads a list of <img> elements by creating new Image
 * instances and marks them with a [loaded] tag so they will fade in.
 *
 * @param {HTMLImageElement[]} arr - Array of <img> elements to load
 * @returns {void}
 */
export const load_images = (arr) => {
  arr.forEach((img) => {
    function load(e) {
      this.setAttribute("loaded", true);
    }

    any_loaded_images(img);

    img.addEventListener("load", load);
  });
};

/**
 * Reloads all images and marks them again so new ones fade in.
 */
export const reload_images = () => load_images(document.find_all("img"));

/**
 * Creates a new responder with default values set.
 * Accepts either a message string or an object with `message` and `status`.
 *
 * @param {string|{message?: string, status?: boolean}} message - The message or config object
 * @param {string} status - Status string ("error", "success", etc.)
 * @param {HTMLElement} append_to - Element to append the responder to
 * @returns {void}
 */
export const create_responder = (
  message,
  status = "error",
  append_to = document.body,
) => {
  if (
    typeof message === "object" &&
    message !== null &&
    !Array.isArray(message)
  )
    new Responder.Responder().add(
      append_to,
      message?.message ?? "No message",
      message?.status ? "success" : "error",
    );
  else new Responder.Responder().add(append_to, message, status);
};

/**
 * Closes all open overlays.
 */
export const close_overlays = () => {
  let overlays = document.querySelectorAll("overlay");

  close_exception_overlay();

  if (!overlays) return;

  overlays.forEach((overlay) => {
    overlay.removeAttribute("visible");

    setTimeout(() => {
      overlay.remove();

      if (
        document.body.hasAttribute("toggled") &&
        document.body.getAttribute("toggled") == "true"
      )
        document.body.setAttribute("toggled", "false");
    }, 400);
  });

  __page.overlay = null;
};

export const close_exception_overlay = () => {
  document.find("exception-container")?.remove();
};

/**
 * Extract an exception from the incoming reponse either as text
 * or HTML already.
 *
 * @param {string|HTMLElement} from
 * @returns {void}
 */
export const extract_exception = (from) => {
  if (
    from &&
    !(from instanceof Element) &&
    from.includes("exception-container")
  )
    document.body.insertAdjacentHTML("beforeend", from);
  else if (from && !(from instanceof Element)) return;

  let exception = document.find("exception-container");

  if (exception) {
    exception.remove();
    document.body.appendChild(exception);
  }
};

/**
 * Handles AJAX errors by cleaning up UI and displaying an error message.
 *
 * - Unloads any loaders
 * - Re-enables all submit buttons
 * - Extracts the exception message
 * - Shows a responder with the error status
 *
 * @param {{ responseText: string, statusText: string }} error
 * @returns {void}
 */
export const ajax_error = (error) => {
  unload();

  /**
   * Activate all submit buttons, so the user can try again.
   */
  let buttons = document.find_all("[submit-closest]");
  if (buttons)
    buttons.forEach((button) => {
      button.enable();
    });

  extract_exception(error.responseText);
  create_responder(error.statusText, "error");
};

/**
 * Animates the ajax response container based on the return of requests.
 *
 * @param {string} type
 */
export const ajax_response = (type = "success") => {
  let container = document.find("ajax-response");

  container.setAttribute(type, true);
  container.activate();

  container.addEventListener("animationend", function (e) {
    container.removeAttribute(type);
    container.deactivate();
  });
};

/**
 * Finds all <get-content></get-content> elements and loads the
 * content from the specified from attribute dynamically.
 */
export const get_content = () => {
  let elements = document.find_all("get-content");
  let from;

  elements.forEach((c) => {
    from = c.getAttribute("from");

    // return;

    if (from)
      $.ajax({
        url: from,
        success: function (data) {
          c.insertAdjacentHTML("afterend", data?.data ?? data);
          c.remove();

          // reload_images();

          let elem = document.createElement("div");
          elem.insertAdjacentHTML("afterbegin", data?.data ?? data);
          $.globalEval($(elem).find("script").text());
        },
      });
  });
};

$(function () {
  get_content();

  /**
   * Close all dangling overlays on click.
   */
  $(document).on("click", "[o-closer], [close-overlay]", function () {
    // close_exception_overlay();
    // close_overlays();

    this.closest("exception-container")?.remove();
    this.closest("overlay")?.remove();
  });

  /**
   * * · Generalize keyboard shortcut event triggering ¬
   */
  document.addEventListener("keyup", (e) => {
    if (!e.key) return;

    if (e.key.toLowerCase() === "escape") {
      if (__page.file_dialog_open) return (__page.file_dialog_open = false);

      let has_overlays = document.find_all("overlay");

      if (has_overlays) close_overlays();

      return;
    }
  });
});
