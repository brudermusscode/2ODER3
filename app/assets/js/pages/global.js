import * as Frontend from "../framework/frontend";
import * as Cookie from "../framework/cookie";
import * as Prototype from "../abstract/prototypes";

/**
 * Set some defaults for future ajax requests.
 */
$.ajaxSetup({
  contentType: false,
  processData: false,
  method: "GET",
  error: function (error_data) {
    Frontend.ajax_error(error_data);
  },
});

// document.addEventListener("DOMContentLoaded", async (e) => {});

$(function () {
  $(document).on("click", "theme-switcher", function (e) {
    if (this.hasAttribute("active")) {
      this.deactivate();
      document.body.setAttribute("theme", "light");
      Cookie.set("__theme", "light", 365);
    } else {
      this.activate();
      document.body.setAttribute("theme", "dark");
      Cookie.set("__theme", "dark", 365);
    }
  });

  /**
   * Release keypress events.
   */
  $(document).on("keyup", function (e) {
    let key = e.key.toLowerCase();

    /**
     * Focusing a submit button with tag-name <mbutton> and
     * pressing enter will submit the closest form, basically just
     * clicking the button.
     */
    if (
      e.key.toLowerCase() === "enter" &&
      e.target?.tagName.toLowerCase() === "mbutton"
    )
      e.target.click();
  });

  /**
   * Scroll events.
   */
  $(document).on("scroll", function (e) {
    let $scroll_container = document.body.querySelectorAll(
      "[scroll-manipulated]",
    );

    if (!$scroll_container[0]) return;

    if (
      document.documentElement.scrollTop >= 40 ||
      document.body.scrollTop >= 40
    ) {
      $scroll_container.forEach((s) => {
        s.setAttribute("scrolled", true);
      });
    } else {
      $scroll_container.forEach((s) => {
        s.setAttribute("scrolled", false);
      });
    }
  });

  /**
   * Custom file chooser clicking clicks the file-input inside.
   */
  $(document).on("click", "[trigger-file-input]", function (e) {
    this.find("input[type='file']")?.click();

    __page.file_dialog_open = true;
  });

  /**
   * Change event for file inputs.
   */
  $(document).on(
    "change",
    "[trigger-file-input] input[type='file']",
    function (e) {
      __page.file_dialog_open = false;

      let [file] = this.files;
      let object;
      let selector = this.closest("media-select");

      if (!selector) return;

      selector.find_all("video").forEach((elem) => elem.remove());
      selector.find_all("img").forEach((elem) => elem.remove());

      if (file.type.startsWith("video/")) {
        object = document.createElement("video");
        object.setAttribute("controls", "");
      } else if (file.type.startsWith("image/")) {
        object = document.createElement("img");
      }

      object.src = URL.createObjectURL(file);

      selector.prepend(object);
      selector.setAttribute("filled", "");
    },
  );

  $(document).on("click", "popup-close", function (e) {
    if (__current_overlay.overlay) {
      __current_overlay.delete();
    }
  });

  /**
   * Creates a button of type submit inside the closest form and
   * triggers a click event on it.
   */
  // TODO: Add this to bruder framework.
  $(document).on("click", "[submit-closest]", function (e) {
    let form = this.closest("form");

    if (!form) return;

    let submit_button = form.querySelector("button[type='submit']");

    if (!submit_button) {
      submit_button = document.createElement("button");
      submit_button.setAttribute("type", "submit");
    }

    form.appendChild(submit_button);
    form.querySelector("button[type='submit']").click();

    if (this.hasAttribute("confirm-submit-button"))
      this.removeAttribute("submit-closest");
  });
});
