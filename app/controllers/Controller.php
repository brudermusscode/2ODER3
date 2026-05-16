<?php

namespace Bruder\Controller;

use Bruder\Application\Cookie;
use Bruder\Application\Session;
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
  protected static array $valid_passthrough_keys = ["habibi", "csrf_token", "__admin_key"];

  protected array|object|null $params = [];

  protected array $files = [];

  /**
   * @param array $request - GET/POST/REQUEST
   */
  public function __construct(array $params = [], array $files = [])
  {

    # Set input params.
    $this->params = $params;

    # Set possible files to params.
    foreach ($files as $key => $file) {
      $this->params[$key] = $file;
    }
  }

  /**
   * Authorizes the client which has to send a secret key that
   * needs to match the one in the .env file. Otherwise it
   * instantly dies the php processing.
   *
   * @return true|die
   */
  public function authorize()
  {

    # Do'nt forget to add a key to the .env file 😂
    if (!_env("WEB_ADMIN_KEY")) die(error("Kein Key in der .env Brudi."));

    # Either the transmitted params…
    $params_have_valid_key = !empty($this->params->__admin_key) && $this->params->__admin_key === _env("WEB_ADMIN_KEY");

    # …or a cookie has to have the matching key.
    $cookies_have_valid_key = !empty(Cookie::get("__admin_key")) && Cookie::get("__admin_key") === _env("WEB_ADMIN_KEY");

    return $params_have_valid_key || $cookies_have_valid_key
      ?: die(error("Nö Bruder. Einfach nö."));
  }

  /**
   * Authorizes a visitor to take action.
   *
   * @return true|die
   */
  public function visitor_authorized()
  {

    if (!Visitor::authorized())
      return die(error("Nicht aUtHoRiSiErT Brudi. 🤡"));

    return true;
  }

  /**
   * Validates if the current Visitor is digitated into a User
   * already.
   *
   * @return true|die
   */
  public function user_authorized(bool $exit = true)
  {
    return ModelSession::valid()
      ?: ($exit ? exit(error("D1 User ist nicht authorisiert 🤡")) : false);
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
    ?array $input_params = null
  ) {

    /**
     * @var ?object
     */
    $this->params = $this->serialize_request_params(
      $strict,
      $input_params ?? $this->params,
      $optional
    );

    /**
     * If the param validation failed, we can die out of the
     * execution of any further code, since this will (hopefully)
     * always be the end.
     */
    if (!$this->params)
      die($this->error());
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
    array $optional = []
  ) {
    /**
     * @var array
     */
    $always_pass = self::$valid_passthrough_keys;

    // Check if all required parameters are set in the post request
    foreach ($necessary as $param)
      if (!isset($post_params[$param]))
        return null;

    // Check if any parameter in the post request is not in the required or optional arrays
    foreach ($post_params as $key => $value)
      if (
        !in_array($key, $necessary) &&
        !in_array($key, $optional) &&
        !in_array($key, $always_pass)
      )
        return null;

    $final = (object) Arr::sanitize_special_chars($post_params);
    $final->Visitor = CURRENT_VISITOR;

    return $final;
  }

  /**
   * Get the magic happening! Wizards from waverly Place have been
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
      if ($dir === "templates") break;
    }

    # Build the controller name.
    $ControllerName = "Bruder\\Controller\\";
    foreach ($dir_split as $dir) {
      $ControllerName .= ucfirst($dir);
    }
    $ControllerName .= "sController";

    // ! Controller class is non-existent.
    if (!class_exists($ControllerName))
      return error("Klasse gibts nicht Bruder.");

    # Get the method name from file name.
    $method = pathinfo($file, PATHINFO_FILENAME);

    // ! Method is non-existent inside controller class.
    if (!method_exists($ControllerName, $method))
      return error("Methode gibts nicht Bruder.");

    return (new $ControllerName($_POST, $_FILES))->$method();
  }
}
