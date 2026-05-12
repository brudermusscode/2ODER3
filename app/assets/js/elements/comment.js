import * as Frontend from "../framework/frontend";
import * as Page from "../framework/page";
import * as Cookie from "../framework/cookie";
import * as Request from "../framework/requests";

let __control_left_active = false;

export const load_comments = (log_id) => {
  let comments = document.find("comments");

  if (!comments) return;

  $.ajax({
    url: "/get/log/comments?log_id=" + log_id,
    success: function (data) {
      comments.innerHTML = data.data;
    },
  });
};

$(function () {
  //
  //

  // ? Panel: Comments
  if (localStorage.getItem("panel-comments-collapsed"))
    document
      .find("content[log]")
      ?.setAttribute("panel-comments-collapsed", true);

  /**
   * @event click
   * @this {HTMLElement}
   */
  $(document).on("click", "[panel-open], [panel-close]", function (e) {
    let content = this.closest("content");
    let panel =
      this.getAttribute("panel-open") ?? this.getAttribute("panel-close");

    if (!content || !panel) return;

    let attribute = `panel-${panel}-collapsed`;

    if (content.hasAttribute(attribute)) {
      content.removeAttribute(attribute);
      localStorage.removeItem("panel-comments-collapsed");
    } else {
      content.setAttribute(attribute, "");
      localStorage.setItem("panel-comments-collapsed", true);
    }
  });

  /**
   * @event keydown
   * @this {HTMLDocument}
   */
  $(document).on("keydown", function (e) {
    let code = e.originalEvent.code.toLowerCase();

    if (code === "controlleft") __control_left_active = true;
  });

  /**
   * @event keyup
   * @this {HTMLDocument}
   */
  $(document).on("keyup", function (e) {
    let code = e.originalEvent.code.toLowerCase();

    if (code === "controlleft") __control_left_active = false;
  });

  /**
   * @event keypress
   * @this {HTMLElement} <textarea name=comment></textarea>
   */
  $(document).on("keypress", "textarea[name=comment]", function (e) {
    let code = e.originalEvent.code.toLowerCase();

    // Submit the comment when left ctrl + enter is pressed while
    // being focused on the textarea.
    if (code === "enter" && __control_left_active) {
      document.find('[data-action="comment:create"] [submit-closest]')?.click();
    }
  });

  $(document).on("input", "textarea[auto-resize]", function (e) {
    if (
      e.originalEvent.inputType.toLowerCase() === "deletecontentbackward" ||
      e.originalEvent.inputType.toLowerCase() === "deletewordbackward"
    )
      this.style.height = "auto";

    this.style.height = this.scrollHeight + "px";
  });

  $(document).on("focus", "textarea[name=comment]", function (e) {
    let composer = this.closest("composer");

    composer.activate();
  });

  $(document).on("blur", "textarea[name=comment]", function (e) {
    let composer = this.closest("composer");

    composer.deactivate();
  });

  /**
   * @action DELETE
   * @controller CommentsController
   * @event submit
   */
  $(document).on("click", '[data-action="comment:delete"]', function (e) {
    let button = this;
    let comments = this.closest("comments");
    let comment = this.closest("comment");
    let formdata = new FormData();

    formdata.append("id", this.dataset.id);

    button.disable();
    Frontend.load();

    $.ajax({
      url: Request.url(this),
      method: "POST",
      data: formdata,
      success: function (data) {
        Frontend.unload();
        Frontend.ajax_response(data.status ? "success" : "error");

        if (data.status) {
          comment?.remove();

          if (comments.find_all("comment").length < 1)
            comments.setAttribute("is-empty", "");
        }
      },
    });
  });

  /**
   * @action CREATE
   * @controller CommentsController
   * @event submit
   */
  $(document).on("submit", '[data-action="comment:create"]', function (e) {
    e.preventDefault();

    let button = this.find("[submit-closest]");
    let formdata = new FormData(this);
    let comments = document.find("comments");

    if ($.trim(formdata.get("comment").length) < 1) return;

    Frontend.load();
    button.disable();

    $.ajax({
      url: Request.url(this),
      method: "POST",
      data: formdata,
      success: function (data) {
        Frontend.unload();
        Frontend.ajax_response(data.status ? "success" : "error");

        if (data.status) {
          comments.removeAttribute("is-empty");
          load_comments(data.data.Object.log_id);
        }
      },
    });
  });
});
