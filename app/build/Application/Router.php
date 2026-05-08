<?php

namespace Bruder\Application;

use Bruder\Exception\RouteConstraintException;
use Exception;

class Router
{

  /**
   * @var array
   */
  protected $routes = [];

  /**
   * @var array
   */
  public $current_route = null;

  /**
   * @var array
   */
  protected $return_types = [
    "JSON" => "Content-type: application/json",
    "HTML" => "Content-type: text/html",
  ];

  /**
   * GET Request
   *
   * @param string $uri - Requested URI e. g. /cart
   * @param string $template
   *        Path of the template e. g. carts/index
   * @param array $constraints
   *        Constraints for any colon param e. g. :id
   * @param callable|string $title
   *        Dynamic title, can be based on global params resulting
   *        from the routing
   * @return self
   */
  public function get(string $uri, string $template, ?array $constraints = null, null|callable|string $title = null, ?string $return = null)
  {
    return $this->add("GET", $uri, $template, $constraints, $title, return: $return);
  }

  /**
   * POST Request
   *
   * @param string $uri - Requested URI e. g. /cart
   * @param string $template
   *        Path of the template e. g. carts/index
   * @param array $constraints
   *        Constraints for any colon param e. g. :id
   * @return self
   */
  public function post(string $uri, string $template, ?array $constraints = null, ?string $return = null)
  {
    return $this->add("POST", $uri, $template, $constraints, return: $return);
  }

  /**
   * Adds a new route.
   */
  protected function add(string $method, string $uri, string $template, ?array $constraints = null, null|callable|string $title = null, ?string $return = null)
  {
    /**
     * @var array
     */
    $split = explode("/", $uri);

    /**
     * @var string
     */
    $page = count($split) > 1 ? $split[1] : "";

    $this->routes[] = compact("method", "uri", "template", "constraints", "title", "page", "return");

    return $this;
  }

  /**
   * Can be used to define the return type for a route.
   *
   * @param string $type
   * @return self
   */
  protected function set_return_type(string $type)
  {
    /**
     * Return type is not valid?
     */
    if (!isset($this->return_types[$type])) {
      throw new Exception("Invalid route return type '$type'.");
      die;
    }

    /**
     * Set the header.
     */
    header($this->return_types[$type]);
  }

  /**
   * Checks the currently requested route against the routes array
   * and returns the path of the correspond. template, if one exists.
   * Otherwise it will fallback to the 404 template.
   *
   * @param string $uri
   * @param string $method
   * @return self
   */
  public function route(string $uri, string $method)
  {

    # * Standart routes.
    foreach ($this->routes as $route) :

      # Skip existing routes with dynamic parameters.
      if (str_contains($route["uri"], ":"))
        continue;

      # Check if the uri and method are same for the requested and
      # existing route.
      if (
        ($uri === $route["uri"])
        && $method === $route["method"]
      )
        $this->current_route = $route;

      # If any route was found, I can break out.
      if ($this->current_route)
        break;
    endforeach;

    # * Routes with dynamic parameter.
    if (!$this->current_route)
      $this->serialize_param_w_dynamic_parameter($uri, $method);

    # ! No route found.
    if (!$this->current_route)
      return $this->return_not_found();

    # Set return type.
    if (isset($this->current_route["return"]))
      $this->set_return_type($this->current_route["return"]);

    return $this;
  }

  /**
   *
   */
  protected function serialize_param_w_dynamic_parameter($uri, $method)
  {

    /**
     * @var array Requested URI
     */
    $request_uri_split = explode("/", $uri);

    # Unset the first as this is always empty but be sure that
    # it starts with a /. Otherwise it's not empty WWOOOOH
    if (str_starts_with($uri, "/"))
      array_shift($request_uri_split);

    foreach ($this->routes as $route) :

      # Skip all basic routes without any :param.
      if (!str_contains($route["uri"], ":"))
        continue;

      /**
       * @var array Current Route (foreach)
       */
      $route_uri_split  = explode("/", $route["uri"]);

      # Unset first array key as its always empty.
      if (str_starts_with($route["uri"], "/"))
        array_shift($route_uri_split);

      # Check between the requested uri and the current
      # itterated route, if the count of segments are same, the
      # base page is same  and the method is same. If any
      # mismatches, continue.
      if (! (
        count($request_uri_split) === count($route_uri_split)
        && $request_uri_split[0] === $route_uri_split[0]
        && $method === $route["method"]
      ))
        continue;

      /**
       * @var int
       */
      $route_segments_w_dynamic_parameter = 0;

      foreach ($route_uri_split as $usplit)
        if (str_contains($usplit, ":"))
          $route_segments_w_dynamic_parameter++;

      // echo "Dynamic param segments: " . $route_segments_w_dynamic_parameter . "\n";

      $minimum_matching_segments_count = count($route_uri_split) - $route_segments_w_dynamic_parameter;

        // echo "Minimum segments that have to match without dynamic param segments: " . $minimum_matching_segments_count . "\n";

      /**
       * @var int
       */
      $matching_segments_count = 0;

      /**
       * Loop through all requested uri segments and check
       * against the current iteration route uri for how many
       * segments are equal.
       */
      foreach ($request_uri_split as $key => $usplit) {

        // echo $usplit . " => " . $route_uri_split[$key] . " / " . ($usplit !== $route_uri_split[$key] ? "false" : "true") . "\n";

        if ($usplit !== $route_uri_split[$key])
          continue;

        $matching_segments_count++;
      }

      /**
       * Continue if the count of matching segments is not
       * strictly equal to the count of the minimum matching
       * segments count.
       */
      if ($matching_segments_count !== $minimum_matching_segments_count)
        continue;

      $has_found_route = true;
      break;
    endforeach;

    # ! No route found
    if (!isset($has_found_route))
      return $this->return_not_found();

    /**
     * @var array
     */
    $params_w_constraint = [];

    /**
     * Loop through the splitted route uri and append each param that
     * has a dynamic parameter, indicated by the :, at the exact same
     * position to the params-with-constraint array.
     */
    foreach ($route_uri_split as $key => $param)
      if (str_starts_with($param, ":"))
        $params_w_constraint[$key] = $param;

    /**
     * @var array
     */
    $params_w_values = [];

    /**
     * Loop through the params-with-constraints array and put
     * together the possible parameter having a constraint with
     * the actual constraint. The keys should match the values.
     */
    foreach ($params_w_constraint as $key => $param) {

      # Validate any route param constraint.
      if (isset($route["constraints"][$param])) {

        $constraint = $route["constraints"][$param];
        $is_valid = preg_match("#^$constraint$#", $request_uri_split[$key]);

        # ! Constraint not matched
        if (!$is_valid) {
          new RouteConstraintException("Param '$request_uri_split[$key]' violates ':$param' with constraint '/$constraint/'");

          redirect("/404", headers: [
            "HTTP/1.0 404 Not Found",
          ]); // Will die.
        }
      }

      # Serialize the param name.
      $param = str_replace(":", "", $param);

      /**
       * Create an array with the param name and the actual
       * value to be used in page title generation.
       */
      $params_w_values[$param] = $request_uri_split[$key];

      /**
       * Create a variable with the name of the constrainted
       * param and asiign it the value of the requested uri.
       * Globalize it, so it's available in template files
       * through $GLOBALS["param_name"].
       */
      global ${"route_param_" . $param};
      ${"route_param_" . $param} = $request_uri_split[$key];

      /**
       * Append all get parameter to the global $_GET.
       */
      $_GET[$param] = $request_uri_split[$key];
    }

    # Make the title.
    $_CURRENT_PAGE_TITLE = is_callable($route["title"])
      ? $route["title"]($params_w_values)
      : $route["title"];

    /**
     * Append everything to the server query string so it is
     * available by using filter_input().
     */
    $_SERVER["QUERY_STRING"] = http_build_query($_GET);

    # # Append route!
    $this->current_route = $route;
    $this->current_route["title"] = $_CURRENT_PAGE_TITLE;
  }

  /**
   * @return self
   */
  protected function return_not_found()
  {
    header("HTTP/1.0 404 Not Found");
    $route = [
      "method" => "GET",
      "uri" => "/404",
      "template" => "error/404",
      "title" => "big oof. much 404. wow",
      "page" => "404",
    ];
    $this->current_route = $route;

    return $this;
  }


  /**
   * @return array
   */
  protected function not_found_route()
  {
    header("HTTP/1.0 404 Not Found");
    return [
      "method" => "GET",
      "uri" => "/404",
      "template" => "error/404",
      "title" => "big oof. much 404. wow",
      "page" => "404",
    ];
  }
}
