import * as Frontend from "./frontend";
import * as Page from "./page";

$(function () {
  /**
   * * · Initialize Bruder! ¬
   */
  init_application();

  /**
   * * · Click event generalization ¬
   */
  document.addEventListener("click", async function (e) {
    let anchor = e.target.closest("a");
    let href;
    let is_external;

    if (anchor !== null && anchor.hasAttribute("href")) {
      href = anchor.getAttribute("href");

      /**
       * Don't SPA style page load when it's a different target.
       */
      is_external =
        anchor.hasAttribute("extern") ||
        anchor.getAttribute("target") === "_blank";

      if (!is_external) {
        e.preventDefault();

        await Page.get(
          href,
          false,
          anchor,
          !anchor.hasAttribute("no-scroll-top"),
        );
      }
    }
  });

  /**
   * * · Back in history! ¬
   */
  window.addEventListener("popstate", async (e) => {
    if (!e.state) return;

    Frontend.close_overlays();

    if (e.state.href == undefined || !e.state.href) return;

    await Page.get(e.state.href, true);
  });
});

/**
 * Loads all necessary parts for showing a proper starting state
 * of this application.
 */
const init_application = async () => {
  let Route = await Page.get_route(window.location.pathname);

  Frontend.set_state(Route);

  // TODO: Fix first page to second won't allow history

  console.log(
    "%c🌞 Bruder, alles geladen!",
    "color:light-blue;font-size:1.32em;font-weight:800;",
    "\nJustin Seidel ©️ 2022-" + new Date().getFullYear(),
  );
};
