<?php

namespace Bruder\Controller;

use Bruder\Application\Cookie;
use Bruder\Model\Session as ModelSession;
use Bruder\Model\User;
use Bruder\Model\Visitor;
use Bruder\Utils\Arr;
use Bruder\Trait\ProcessesRequests;

class Controller
{
  use ProcessesRequests;

  # Keys that are valid for prequests even tho not explicitly
  # noted down in the controller.
  protected static array $valid_passthrough_keys = [
    "__admin_key",
    "Client",
  ];

  /**
   * Sent parameter from $_GET or $_POST.
   */
  protected array|object|null $params = [];

  /**
   * Sent files from $_FILES.
   */
  protected array $files = [];

  /**
   * The current client, which can either be a non authenticated
   * Visitor or a fully signed up User. Or null, of course 🐣
   * * Not being used by now, as I often pass the Client as a
   * * param to model methods.
   */
  protected User|Visitor|null $Client = null;

  /**
   * @param array $params
   * @param array $files
   */
  public function __construct(array $params = [], array $files = [])
  {
    # Set input params.
    $this->params = $params;

    # Set possible files to params.
    foreach ($files as $key => $file) {
      $this->params[$key] = $file;
    }

    # Set the current Bruder as the Client, so we can interact with
    # it either in Controller files itself or any Model as we pass
    # the params. This is save, as params are set above and if the
    # User would set a custom Client, this below will overwrite it
    # again. Sanitization for key Client will be skipped.
    # * It feels wrong passing the User or Visitor as a param to any
    # * model and work with it inside the model. Shouldn't this be
    # * happening in the controller? Using $this->Client and limiting
    # * access to any controller only would be cleaner.
    $this->params["Client"] = CURRENT_BRUDER;
  }

  /**
   * Authorizes the client which has to send a secret key that
   * needs to match the one in the .env file. Only for author-
   * izing admina actions.
   *
   * ? May terminate the execution using die().
   *
   * @return true
   */
  public function authorize()
  {
    # Do'nt forget to add a key to the .env file 😂
    if (!_env("WEB_ADMIN_KEY")) {
      die(error("Kein Key in der .env Brudi."));
    }

    # Either the transmitted params…
    $params_have_valid_key =
      !empty($this->params->__admin_key) &&
      $this->params->__admin_key === _env("WEB_ADMIN_KEY");

    # …or a cookie has to have the matching key.
    $cookies_have_valid_key =
      !empty(Cookie::get("__admin_key")) &&
      Cookie::get("__admin_key") === _env("WEB_ADMIN_KEY");

    return $params_have_valid_key || $cookies_have_valid_key ?:
      die(error("Nö Bruder. Einfach nö."));
  }

  /**
   * Authorizes a client to take action. Respects exceptional
   * nullability of $Client. If null, it checks for the
   * CURRENT_BRUDER to be popuplated.
   *
   * ? May terminate the execution using die().
   *
   * @param User|Visitor|null $Client
   * @param bool $die
   * @return true
   */
  public function can_interact(User|Visitor|null $Client = null, bool $die = true)
  {
    return $this->params->Client?->exists
      ?: ($die ? die(error("Nicht aUtHoRiSiErT Brudi. 🤡")) : false);
  }

  /**
   * Validates given params having keys specified and sets the
   * result to the protected params object to make it available in
   * the scope of this classes and those inheriting it.
   *
   * @param array $strict - Strictly necessary parameter.
   * @param array $optional - Will pass, but not necessary.
   * @param ?array $input_params
   * @return void
   */
  public function validate_params(
    array $strict,
    array $optional,
    ?array $input_params = null,
  ) {
    /**
     * @var ?object
     */
    $this->params = $this->serialize_request_params(
      $strict,
      $input_params ?? $this->params,
      $optional,
    );

    /**
     * If the param validation failed, we can die out of the
     * execution of any further code, since this will (hopefully)
     * always be the end.
     */
    if (!$this->params) {
      die($this->error());
    }
  }

  /**
   * Checks for given array keys being present in another array
   * and for array keys that are not allowed to be passed.
   *
   * @param array $necessary The keys needing to be present
   * @param array $post_params The array to check against
   * @param array $optional Let keys pass that are there but not filled
   * @return ?object
   */
  protected function serialize_request_params(
    array $necessary,
    array $post_params,
    array $optional = [],
  ) {
    /**
     * @var array
     */
    $always_pass = self::$valid_passthrough_keys;

    // Check if all required parameters are set in the post request
    foreach ($necessary as $param) {
      if (!isset($post_params[$param]))
        return null;
    }

    // Check if any parameter in the post request is not in the required or optional arrays
    foreach ($post_params as $key => $value) {
      if (
        !in_array($key, $necessary) &&
        !in_array($key, $optional) &&
        !in_array($key, $always_pass)
      ) {
        return null;
      }
    }

    $final = (object) Arr::sanitize_special_chars($post_params, skip_keys: ["Client"]);

    return $final;
  }

  /**
   * Get the magic happening! Wizards from Waverly Place have been
   * working on this: This function calls a controller file from a
   * given file inside a given path and determines the method to
   * call based on the file name this function is being called in.
   *
   * WOW.
   *
   * @param $file __FILE__
   * @param $from __DIR__
   * @return string Basic JSON return string
   */
  public static function call(string $file, string $from)
  {
    $dir_split = explode("/", $from);

    # Remove all directories before (and including) templates so we
    # can determine, how deep the Controller file lays.
    foreach ($dir_split as $key => $dir) {
      unset($dir_split[$key]);
      if ($dir === "templates") {
        break;
      }
    }

    # Build the controller name.
    $ControllerName = "Bruder\\Controller\\";

    foreach ($dir_split as $dir) {
      $dir_split2 = explode("-", $dir);
      foreach ($dir_split2 as $dirnamepart)
        $ControllerName .= ucfirst($dirnamepart);
    }
    $ControllerName .= "sController";

    // Controller class is non-existent?
    if (!class_exists($ControllerName)) {
      return error("Klasse gibts nicht Bruder.");
    }

    # Get the method name from file name.
    $method = pathinfo($file, PATHINFO_FILENAME);

    // Method is non-existent inside controller class?
    if (!method_exists($ControllerName, $method)) {
      return error("Methode gibts nicht Bruder.");
    }

    return new $ControllerName($_POST, $_FILES)->$method();
  }
}
