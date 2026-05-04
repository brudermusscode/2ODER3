import * as Frontend from "../framework/frontend";
import * as Page from "../framework/page";
import * as Cookie from "../framework/cookie";

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
  //
  //

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
