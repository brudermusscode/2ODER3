import * as Frontend from "../framework/frontend";
import * as Page from "../framework/page";

/**
 * @event DOMContentLoaded
 */
$(function () {
  //
  //

  /**
   * Choose another thumbnail.
   *
   * @event click
   * @this HTMLElement [thumbnails] picture
   */
  $(document).on("click", "choose-option coption", function (e) {
    let wrapper = this.closest("choose-option");
    let options = wrapper.find_all("coption");
    let input = wrapper.find("input[type=hidden]");

    options.forEach((option) => option.deactivate());

    this.activate();
    input.value = this.dataset.value;
  });

  /**
   * @event keypress
   */
  $(document).on("keypress", function (e) {
    let code = e.originalEvent.code.toLowerCase();

    // Click a choose option element if it is targeted.
    if (code === "enter" && e.target.closest("coption"))
      e.target.closest("coption").click();
  });

  /**
   * Delete a Log.
   *
   * @action DELETE
   * @controller LogsController
   * @event click
   * @this HTMLElement [data-action="log:delete"]
   */
  $(document).on("click", '[data-action="log:delete"]', function (e) {
    e.preventDefault();

    let formdata = new FormData();
    formdata.append("id", this.dataset.id);

    Frontend.load();

    $.ajax({
      url: "/log/delete",
      data: formdata,
      method: "POST",
      success: async function (data) {
        Frontend.unload();
        Frontend.ajax_response(data.status ? "success" : "error");

        if (data.status) {
          await Page.get(`/log/new`);
        }
      },
    });
  });

  /**
   * Create a new Log.
   *
   * @action CREATE
   * @controller LogsController
   * @event submit
   * @this HTMLElement [data-action="log:create"]
   */
  $(document).on("submit", '[data-action="log:create"]', function (e) {
    e.preventDefault();

    if (!this.find("input[type=file]").value) return;

    let button = this.find("[submit-closest]");
    let formdata = new FormData(this);
    let url = __env === "dev" ? "/log/create" : "https://uploads.heia.kim";

    button.disable();

    $.ajax({
      url: url,
      data: formdata,
      method: "POST",
      xhr: function () {
        let xhr = new window.XMLHttpRequest();
        let progress = document.find("progress-bar");
        let process_msg = document.find("[processing-message]");

        progress.activate();

        xhr.upload.addEventListener(
          "progress",
          function (e) {
            if (e.lengthComputable) {
              let percent = Math.round((e.loaded / e.total) * 100);
              progress.find("progress-track").style.width = percent + "%";

              if (percent >= 100) {
                progress.deactivate();
                process_msg.activate();
              }
            }
          },
          false,
        );

        return xhr;
      },
      success: async function (data) {
        Frontend.ajax_response(data.status ? "success" : "error");

        if (data.status) {
          await Page.get(`/log/${data.data.id}/edit/metadata`);
        } else {
          button.enable();
        }
      },
    });
  });

  /**
   * Finalize editing a Log.
   *
   * @action UPDATE
   * @controller LogsController
   * @event submit
   * @this HTMLElement [data-action="log:finalize"]
   */
  $(document).on("submit", '[data-action="log:finalize"]', function (e) {
    e.preventDefault();

    let formdata = new FormData(this);
    let url = "/log/update";

    Frontend.load();

    $.ajax({
      url: url,
      data: formdata,
      method: "POST",
      success: async function (data) {
        Frontend.unload();
        Frontend.ajax_response(data.status ? "success" : "error");

        if (data.status && data.data.project_id > 0) {
          await Page.get(
            `/project/${data.data.project_id}/log/${data.data.id}`,
          );
        }
      },
    });
  });
});
