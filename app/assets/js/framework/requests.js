import * as Frontend from "./frontend";
import * as Page from "./page";
import Overlay from "./overlay";

$(function () {
  $(document).on("submit", "[request], [request-do]", function (e) {
    e.preventDefault();

    let request_url =
      this.getAttribute("request") || this.getAttribute("request-do");

    if (!request_url) return;

    let delay = this.getAttribute("delay") ?? 0;

    setTimeout(() => {
      let formdata = new FormData(this);
      let button = this.find("[submit-closest]");
      let method = this.getAttribute("method") ?? "POST";
      let responder = this.getAttribute("responder");
      let redirect = this.getAttribute("redirect");
      let scroll_top = !this.hasAttribute("no-scroll-top");
      let reload = this.getAttribute("reload");
      let full_reload = this.getAttribute("full-reload");
      let execute_success = this.getAttribute("on-success");

      /**
       * Serialize request url.
       */
      request_url = request_url.replaceAll(":", "/");

      button.disable();
      Frontend.load();

      if (redirect) {
        /**
         * @var array
         */
        let split_redirect_url = redirect.split("/");

        /**
         * Build a new redirect url by substituting the colon
         * parameter with actual values from the submitted form.
         */
        split_redirect_url.forEach((section, index) => {
          if (section[0] === ":") {
            let param = section.replace(":", "");
            let value = formdata.get(param);

            redirect = redirect.replace(section, value);
          }
        });
      }

      $.ajax({
        url: "/" + request_url,
        data: formdata,
        method: method,
        contentType: false,
        processData: false,
        success: function (data) {
          Frontend.unload();

          if (data.status) {
            if (reload !== null) Page.reload();
            if (redirect !== null && full_reload === null)
              Page.get(redirect, false, null, scroll_top);
            else if (full_reload !== null)
              window.location.replace(
                redirect ?? window.location.pathname + window.location.search
              );

            if (responder !== null && responder === "success")
              Frontend.create_responder(data.message, "success");

            /**
             * Execute on success functions.
             */
            if (execute_success) $.globalEval(execute_success);
          } else {
            if (responder !== null && responder === "error")
              Frontend.create_responder(data.message, "error");
            button.enable();
          }

          if (responder !== null && (responder === "always" || !responder))
            Frontend.create_responder(
              data.message,
              data.status ? "success" : "error"
            );
        },
        error: function (error) {
          button.enable();
          Frontend.unload();
          Frontend.ajax_error(error);
        },
      });
    }, delay);
  });

  /**
   * Open popups dynamically.
   */
  $(document).on("click", "[request-get]", function (e) {
    let href = this.getAttribute("request-get");
    let url = "/" + href.replaceAll(":", "/");
    let query = "?";
    let dataset_count = Object.keys(this.dataset).length;

    /**
     * Construct the url query by iterating through all data
     * elements on the clicked element.
     */
    if (dataset_count > 0) {
      for (const key in this.dataset) {
        query +=
          key.replace(/[A-Z]/g, (letter) => "_" + letter.toLowerCase()) +
          "=" +
          this.dataset[key] +
          "&";
      }

      query += "is_popup=kurwa";
    } else query += "is_popup=kurwa";

    Frontend.load();

    $.ajax({
      url: url + query,
      method: "GET",
      contentType: false,
      processData: false,
      success: function (data) {
        Frontend.unload();

        if (data.status) {
          let overlay = new Overlay();
          overlay.append(data.data);

          setTimeout(() => {
            overlay.overlay.find("[autofocus]")?.focus();
          }, 400);
        } else new Frontend.create_responder(data.message, "error");
      },
      error: function (error) {
        Frontend.unload();
        Frontend.ajax_error(error);
      },
    });
  });
});

/**
 * Builds a complete query string with a leading ? for get
 * requests from a given dataset.
 */
export const dataset_build_query_string = (dataset) => {
  let dataset_count = Object.keys(dataset).length;
  let query = "?";

  /**
   * Construct the url query by iterating through all data
   * elements on the clicked element.
   */
  if (dataset_count > 0) {
    for (const key in dataset) {
      query +=
        key.replace(/[A-Z]/g, (letter) => "_" + letter.toLowerCase()) +
        "=" +
        dataset[key] +
        "&";
    }
  }

  return query;
};

/**
 * Builds up a FormData object from a given dataset and returns it.
 */
export const dataset_build_formdata = (dataset) => {
  let formdata = new FormData();
  let dataset_count = Object.keys(dataset).length;

  /**
   * Construct the url query by iterating through all data
   * elements on the clicked element.
   */
  if (dataset_count > 0) {
    for (const key in dataset) {
      let transformed_key = key.replace(
        /[A-Z]/g,
        (letter) => "_" + letter.toLowerCase()
      );

      if (transformed_key !== "action")
        formdata.append(transformed_key, dataset[key]);
    }
  }

  return formdata;
};

/**
 * Either pass an element that has a data-action attribute or a
 * string which both will be transformed to a valid request url.
 */
export const url = (string) => {
  let action = string.dataset.action;
  let url = action ? action : string;

  return "/" + url.replaceAll(":", "/");
};
