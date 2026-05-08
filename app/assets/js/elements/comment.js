import * as Frontend from "../framework/frontend";
import * as Page from "../framework/page";
import * as Cookie from "../framework/cookie";
import * as Request from "../framework/requests";

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

  $(document).on("input", "textarea[auto-resize]", function (e) {
    console.log(e);

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
   * @action CREATE
   * @controller CommentsController
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
