const entry = require("./entryEmails.js");
const path = require("path");
const fs = require("fs");
const exec = require("child_process").exec;
const BrowserSyncPlugin = require("browser-sync-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");

module.exports = {
  context: __dirname,
  entry,
  output: {
    path: path.resolve(__dirname, "emails/dist"),
    filename: "[name].js",
  },
  mode: "development",
  devtool: "eval-cheap-source-map",
  module: {
    rules: [
      {
        test: /\.s?css$/,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: "css-loader",
            options: {
              url: false,
            },
          },
          {
            loader: "sass-loader",
            options: {
              // Prefer `dart-sass`
              implementation: require("sass"),
            },
          },
        ],
      },
      {
        test: /\.mjml$/,
        use: {
          loader: "null-loader",
          options: { /* any mjml options */ minify: true }, // optional, you can omit options
        },
      },
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: "[name].css",
    }),
    {
      apply: (compiler) => {
        const buildMjml = (assetFolder) => {
          var mjmlCLI;
          const distFolder = `./emails/dist/${assetFolder}`;
          const templatePath = `./emails/src/${assetFolder}/template.mjml`;
          const partsFolder = `./emails/src/${assetFolder}/parts`;

          var shouldCheckParts = false;
          var mjmlTemplateCLI = `mjml ${templatePath} -o ${distFolder}`;
          var mjmlPartsCLI = `mjml ${partsFolder}/*.mjml -o ${distFolder}`;

          mjmlCLI = mjmlTemplateCLI;

          if (fs.existsSync(partsFolder)) {
            const partsFiles = fs.readdirSync(partsFolder).filter(function (x) {
              return x !== ".DS_Store";
            });

            if (partsFiles.length) {
              shouldCheckParts = true;
            }
          }

          if (shouldCheckParts) {
            mjmlCLI = `${mjmlTemplateCLI} && ${mjmlPartsCLI}`;
          }

          exec(mjmlCLI, (err, stdout, stderr) => {
            if (stdout) process.stdout.write(stdout);
            if (stderr) process.stderr.write(stderr);
          });
        };
        compiler.hooks.afterEmit.tap("Compile MJML", (compilation) => {
          var assets = compilation.assetsInfo;

          assets.forEach((value, name) => {
            const assetFolder = path.basename(path.dirname(name));

            buildMjml(assetFolder);
          });
        });
      },
    },
    new BrowserSyncPlugin(
      {
        proxy: "http://localhost/test-email",
        files: ["**/template.html", "**/*.php"],
        reloadOnRestart: false,
        notify: false,
        stream: {
          once: true,
        },
      },
      {
        reload: false,
      }
    ),
  ],
  optimization: {
    minimizer: [new CssMinimizerPlugin()],
  },
};
