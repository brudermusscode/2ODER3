import * as Frontend from "../framework/frontend";
import * as Page from "../framework/page";
import * as Cookie from "../framework/cookie";
import * as Request from "../framework/requests";

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

export const close_reactions = (log_id) => {
  let cont = document.find("reactions");
  if (!cont) return;

  cont.deactivate();
  cont.find_all("reaction")?.forEach((e) => e.deactivate());
  cont
    .closest("reactions-container")
    .find("active-reactions")
    ?.removeAttribute("behind");
};

export const load_reactions = (log_id) => {
  let container = document.find("reactions-container active-reactions");

  if (!container) return;

  container.disable();

  $.ajax({
    url: "/get/log/reactions?log_id=" + log_id,
    success: function (data) {
      if (!data.status) return Frontend.ajax_response("error");

      container.innerHTML = data.data;
      container.enable();
    },
  });
};

// document.addEventListener("DOMContentLoaded", async (e) => {});

$(function () {
  //
  //

  /**
   * Click events.
   */
  $(document).on("click", function (e) {
    // Close reactions popup.
    let reactions = document.find("reactions");
    if (
      reactions &&
      reactions.hasAttribute("active") &&
      !e.target.closest("reactions")
    )
      close_reactions();
  });

  /**
   * Scroll events.
   */
  $(document).on("scroll", function (e) {
    close_reactions();
  });

  $(document).on("click", "reactions", function (e) {
    let reactions = this.find_all("reaction");
    let reaction_clicked = e.target.closest("reaction");

    if (
      reaction_clicked ||
      (this.hasAttribute("active") && !e.target.closest("reactions-choose"))
    )
      return close_reactions();

    let timer = 0;

    for (let i = 0; i < reactions.length; i++) {
      setTimeout(() => {
        reactions[reactions.length - (1 + i)].activate();
      }, timer);

      timer += 32;
    }

    this.activate();
    this.closest("reactions-container")
      .find("active-reactions")
      .setAttribute("behind", true);
  });

  /**
   * @action CREATE
   * @controller ReactionsController
   */
  $(document).on("click", "[data-action='reaction:create']", function (e) {
    // When clicking inside the reactions container which is the
    // choose popup and the target is not a reaction tag, return.
    if (e.target.closest("reactions") && !e.target.closest("reaction")) return;

    let reaction = e.target.closest("reaction") ?? this.find("reaction");

    let formdata = new FormData();
    formdata.append("log_id", this.dataset.logId);
    formdata.append("type", this.dataset.type);
    formdata.append("emote", reaction.innerHTML);

    // Disable reactions container to prevent action.
    document.find("active-reactions")?.disable();

    $.ajax({
      url: Request.url(this),
      data: formdata,
      method: "POST",
      success: function (data) {
        if (!data.status) return Frontend.ajax_response("error");

        load_reactions(data.data.Object.log_id);
      },
    });
  });

  /**
   * @action DELETE
   * @controller ReactionsController
   */
  $(document).on("click", "[data-action='reaction:delete']", function (e) {
    let button = this;
    let reaction = this.find("reaction");
    let countHTML = this.find("reaction-count");
    let count = parseInt(countHTML.innerHTML);
    let new_count = count - 1;

    let formdata = new FormData();
    formdata.append("id", this.dataset.id);

    // Disable reactions container to prevent actions.
    this.closest("active-reactions")?.disable();

    $.ajax({
      url: Request.url(this),
      data: formdata,
      method: "POST",
      success: function (data) {
        button.closest("active-reactions")?.enable();

        if (new_count < 1) {
          button.setAttribute("removed", true);
          setTimeout(() => {
            button.remove();
          }, 600);

          return;
        }

        // Enable reactinons container again
        button.setAttribute("data-action", "reaction:create");
        button.deactivate();
        countHTML.innerHTML = count - 1;
      },
    });
  });

  $(document).on("click", '[data-action="project:get"]', function (e) {
    let id = e.target.closest("option[data-id]")?.dataset.id;

    if (!id) return;

    Page.get(`/project/${id}`);
    Cookie.set("__project_id", id, 365);
  });

  /**
   * Create a new Log.
   *
   * @action CREATE
   * @controller LogsController
   */
  $(document).on("submit", '[data-action="log:create"]', function (e) {
    e.preventDefault();

    let formdata = new FormData(this);
    let url = __env === "dev" ? "/log/create" : "https://uploads.heia.kim";

    Frontend.load();

    $.ajax({
      url: url,
      data: formdata,
      method: "POST",
      success: function (data) {
        if (data.status) Page.reload();

        Frontend.ajax_response(data.status ? "success" : "error");
        Frontend.unload();
      },
    });
  });
});
