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
    mark: "home",
    params: "/:id/:sub",
    execute_once: () => {},
  },

  project: {
    mark: "project",
    params: ":id",
    hide_sidebar: (uri) => {
      let uri_split = uri.split("/");

      return uri_split.length > 3 ? false : true;
    },
  },

  "get-back": {
    mark: "login",
    hide_sidebar: true,
    background: {
      image: "/jesus-programming-compressed.png",
      blur: 0,
      color: "rgba(0,0,0,.24)",
    },
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
