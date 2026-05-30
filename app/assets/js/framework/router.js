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
    execute_always: () => {
      let coding_session = document.find("coding-session");

      if (coding_session) {
        let in_this_session = coding_session.find("[in-this-session]");

        console.log(in_this_session.scrollWidth);

        in_this_session?.scrollTo({
          left: in_this_session.scrollWidth,
          top: 0,
          behaviour: "smooth",
        });

        let h = coding_session.find("[h]");
        let i = coding_session.find("[i]");
        let s = coding_session.find("[s]");

        let interval_timing = 1000; // 1 second.

        setInterval(() => {
          let new_h = Number.parseInt(h.innerHTML);
          let new_i = Number.parseInt(i.innerHTML);
          let new_s = Number.parseInt(s.innerHTML) + 1;

          if (new_s === 60) {
            new_s = 0;
            new_i += 1;
          }

          if (new_i === 60) {
            new_i = 0;
            new_h += 1;
          }

          h.innerHTML = new_h < 10 ? `0${new_h}` : new_h;
          i.innerHTML = new_i < 10 ? `0${new_i}` : new_i;
          s.innerHTML = new_s < 10 ? `0${new_s}` : new_s;
        }, interval_timing);
      }
    },
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
