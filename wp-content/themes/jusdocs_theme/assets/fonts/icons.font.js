module.exports = {
  files: ["./icons/*.svg"],
  fontName: "icons",
  classPrefix: "icon-",
  baseSelector: ".icon",
  types: ["eot", "woff", "woff2", "ttf", "svg"],
  fixedWidth: false,
  fileName: "./[fontname].[hash].[ext]",
  writeFiles: true,
  cssFontsPath: "./",
  dest: "./dist/fonts/",
};
