// Search for entries in ´pages´ directory
const path = require("path");
const fs = require("fs");

function getWebpackEntries() {
  /**
   * Pages Assets
   */
  const pagesDirectoryPath = path.join(__dirname, "pages");

  const assets = {
    vendor: ["./assets/js/vendor.js"],
  };

  const pageFiles = fs.readdirSync(pagesDirectoryPath);

  pageFiles.forEach(function(page) {
    const jsPath = path.join(__dirname, "pages", page, "scripts.js");

    if (fs.existsSync(jsPath)) {
      if (typeof assets[page] === "undefined") {
        assets[page] = [];
      }

      assets[page].push(`./pages/${page}/scripts.js`);
    }

    const cssPath = path.join(__dirname, "pages", page, "styles.scss");
    if (fs.existsSync(cssPath)) {
      if (typeof assets[page] === "undefined") {
        assets[page] = [];
      }

      assets[page].push(`./pages/${page}/styles.scss`);
    }
  });
  /**
   * Admin Assets
   */

  const adminJs = path.join(__dirname, "assets/js/admin", "scripts.js");
  const adminCss = path.join(__dirname, "assets/scss/admin", "styles.scss");
  assets["admin-area"] = [];

  if (fs.existsSync(adminJs)) {
    assets["admin-area"].push(adminJs);
  }
  if (fs.existsSync(adminCss)) {
    assets["admin-area"].push(adminCss);
  }

  return assets;
}

module.exports = getWebpackEntries();
