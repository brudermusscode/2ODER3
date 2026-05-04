$(function () {
  //
  //

  /**
   * General click event handler.
   */
  $(document).on("click", function (e) {
    if (!e.target.closest("option-select")) {
      document
        .find_all("option-select")
        .forEach((select) => select.deactivate());
    }
  });

  /**
   * Option select model.
   */
  $(document).on("click", "option-select", function (e) {
    let current_option = this.find("current-option");
    let selected_option = this.find("option[active]");
    let all_options = this.find_all("option");
    let options = this.find("options");
    let option_clicked = e.target.closest("option");

    if (option_clicked) {
      all_options.forEach((o) => o.deactivate());
      option_clicked.activate();
      current_option.innerHTML = option_clicked.find("p").innerHTML;
      this.deactivate();
    } else if (!e.target.closest("options")) {
      if (this.hasAttribute("active")) this.deactivate();
      else this.activate();
    }
  });
});
