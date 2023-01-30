const path = require('path');
const fs = require('fs');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');
const globImporter = require('node-sass-glob-importer');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const CopyAfterCompilationWebpackPlugin = require('./.webpack/CopyAfterCompilationWebpackPlugin');

module.exports = {
  ...defaultConfig,
  entry: {
    'plugin': [
      './assets/scripts/plugin.js',
      './assets/styles/plugin.scss',
    ],
    'block-editor': './assets/scripts/block-editor.js',
    'editor-style': './assets/styles/editor-style.scss',
    'contact-form': './assets/apps/contact-form/contact-form.js',
  },
  output: {
    ...defaultConfig.output,
    path: path.resolve('web/app/mu-plugins/wpify-skeleton/build'),
  },
  module: {
    ...defaultConfig.module,
    rules: [
      ...defaultConfig.module.rules.map((rule) => {
        if (rule.test.test('.scss')) {
          rule.use.forEach(use => {
            if (use.loader === require.resolve('sass-loader')) {
              use.options.sassOptions = {
                ...(use.options.sassOptions || null),
                importer: globImporter(),
              };
            }
          });
        }

        return rule;
      }),
    ],
  },
  plugins: [
    ...defaultConfig.plugins,
    new BrowserSyncPlugin({
      files: [
        './web/app/mu-plugins/wpify-skeleton/build/**/*.css',
        './web/app/mu-plugins/wpify-skeleton/build/**/*.js',
        './web/app/mu-plugins/wpify-skeleton/build/**/*.svg',
        './web/app/mu-plugins/wpify-skeleton/templates/**/*.php',
        './web/app/themes/wpify-skeleton/**/*.twig',
        './web/app/themes/wpify-skeleton/**/*.php',
        './web/app/themes/wpify-skeleton/*.php',
        './web/app/themes/wpify-skeleton/**/*.php',
        './src/**/*.php',
      ],
      ...(
        fs.existsSync('./.ssl/certs/master.key') && fs.existsSync('./.ssl/certs/master.crt')
          ? {
            https: {
              key: './.ssl/certs/master.key',
              cert: './.ssl/certs/master.crt',
            },
          }
          : {}
      ),
    }, {
      injectCss: true,
      reload: true,
    }),
    new CopyAfterCompilationWebpackPlugin(
      [
        {
          source: path.resolve('web/app/mu-plugins/wpify-skeleton/build/editor-style.css'),
          destination: path.resolve('web/app/themes/wpify-skeleton/editor-style.css'),
        },
      ],
    ),
    new SVGSpritemapPlugin('./assets/sprites/**/*.svg', {
      output: {
        filename: 'sprites.svg',
      },
      styles: {
        filename: path.resolve('assets/styles/sprites.scss'),
        callback: (content) => `${content}\n@each $name, $size in $sizes {\n.sprite--#{$name} { width: map-get($size, width); height: map-get($size, height); }\n}`,
      },
    }),
  ],
};
