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

/**
 * @event DOMContentLoaded
 */
$(function () {
  //
  //

  /**
   * @action CREATE
   * @controller UsersController
   * @event submit
   * @this HTMLElement form[data-action="user:create"]
   */
  $(document).on("submit", '[data-action="user:create"]', function (e) {
    e.preventDefault();

    let formdata = new FormData(this);
    let button = this.find("[submit-closest]");

    Frontend.load();
    button.disable();

    $.ajax({
      url: Request.url(this),
      data: formdata,
      method: "POST",
      success: function (data) {
        Frontend.unload();
        console.log(data);

        if (!data.status) {
          Frontend.create_responder(data);
          button.enable();
        } else {
          Request.get(
            "/user/email",
            `?is_popup=kurwa&tolkien=${data.data.uuid}`,
          );
        }
      },
    });
  });

  /**
   * @action CREATE
   * @controller UserVerificationsController
   * @event submit
   * @this HTMLElement form[data-action="user:verification:create"]
   */
  $(document).on(
    "submit",
    '[data-action="user:verification:create"]',
    function (e) {
      e.preventDefault();

      let formdata = new FormData(this);
      let button = this.find("[submit-closest]");

      Frontend.load();
      button.disable();

      $.ajax({
        url: Request.url(this),
        data: formdata,
        method: "POST",
        success: function (data) {
          Frontend.unload();

          if (!data.status) {
            Frontend.create_responder(data);
            button.enable();
          } else {
            Request.get(
              "/user/code",
              `?is_popup=kurwa&tolkien=${data.data.token}&email=${data.data.email}`,
            );
          }
        },
      });
    },
  );

  /**
   * @action UPDATE
   * @controller UserVerificationsController
   * @event submit
   * @this HTMLElement form[data-action="user:verification:update"]
   */
  $(document).on(
    "submit",
    '[data-action="user:verification:update"]',
    function (e) {
      e.preventDefault();

      let formdata = new FormData(this);
      let button = this.find("[submit-closest]");

      Frontend.load();
      button.disable();

      $.ajax({
        url: Request.url(this),
        data: formdata,
        method: "POST",
        success: function (data) {
          Frontend.unload();

          if (!data.status) {
            Frontend.create_responder(data);
            button.enable();
          } else {
            Frontend.ajax_response("success");
            setTimeout(() => {
              window.location.replace("/");
            }, 1000);
          }
        },
      });
    },
  );
});
