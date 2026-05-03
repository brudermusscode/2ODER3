/**
 * * If using Bruder's SPA style page loading capabilities, you
 * * want to add each route here as a key in the routes object.
 * * The key should match the exact name of the page in the
 * * browser's address bar.
 * * Example: /beatmaps/12/all would require the key "beatmaps".
 */

export const routes = {
  "not-found": {},

  home: {
    // * Which [page] attribute in a possible main menu should be
    // * marked with [active] attribute.
    mark: "home",

    // * Should match the exact params in your router for this route.
    params: "/:id/:sub",

    // * Some functions should just be executed once when loading a
    // * page and not again, when entering a sub page of this route.
    // * For example, you click to /user/1 and it should load all
    // * scores of the user with the id 1. Then you click on
    // * /user/1/friends and it should not execute the function
    // * loading all scores again.
    execute_once: () => {},

    // TODO: implement execute_always.
  },
};

export const router = async (route) => {
  let match;
  let matches_any = route in routes ? true : null;

  // Check for any matches or fallback to not_found.
  match = !matches_any ? routes["not-found"] : routes[route];

  // Append the key.
  match["key"] = route;

  return match;
};
