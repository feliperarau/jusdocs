// Search for entries in ´pages´ directory
const path = require("path");
const fs = require("fs");

function getWebpackEntries() {
  const emailsDirectoryPath = path.join(__dirname, "emails/src");

  const emailFiles = fs.readdirSync(emailsDirectoryPath).filter(function(x) {
    return x !== ".DS_Store";
  });

  const assets = {};
  emailFiles.forEach(function(email) {
    const cssPath = path.join(__dirname, "emails", "src", email, "styles.scss");
    const assetsPrefix = email + "/template";
    const mjmlPath = path.join(
      __dirname,
      "emails",
      "src",
      email,
      "template.mjml"
    );

    if (fs.existsSync(cssPath)) {
      if (typeof assets[assetsPrefix] === "undefined") {
        assets[assetsPrefix] = [];
      }

      assets[assetsPrefix].push(`./emails/src/${email}/styles.scss`);
    }

    if (fs.existsSync(mjmlPath)) {
      if (typeof assets[assetsPrefix] === "undefined") {
        assets[assetsPrefix] = [];
      }

      assets[assetsPrefix].push(`./emails/src/${email}/template.mjml`);
    }

    const partsPath = path.join(__dirname, "emails", "src", email, "parts");

    if (fs.existsSync(partsPath)) {
      const partsFiles = fs.readdirSync(partsPath).filter(function(x) {
        return x !== ".DS_Store";
      });

      partsFiles.forEach(function(partFile) {
        const fileName = path.parse(partFile).name;
        const partFilePath = path.join(
          __dirname,
          "emails",
          "src",
          email,
          "parts",
          `${fileName}.mjml`
        );

        const partPrefix = email + "/" + fileName;

        if (fs.existsSync(partFilePath)) {
          if (typeof assets[partPrefix] === "undefined") {
            assets[partPrefix] = [];
          }

          assets[partPrefix].push(
            `./emails/src/${email}/parts/${fileName}.mjml`
          );
        }
      });
    }
  });

  //console.log(assets);
  return assets;
}

module.exports = getWebpackEntries();
