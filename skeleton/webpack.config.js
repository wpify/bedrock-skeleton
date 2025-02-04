const path = require('path');
const fs = require('fs');
const BrowserSyncPlugin = require('browser-sync-v3-webpack-plugin');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const {merge} = require('webpack-merge');
const WooCommerceDependencyExtractionWebpackPlugin = require('@woocommerce/dependency-extraction-webpack-plugin');

const wcDepMap = {
  '@woocommerce/blocks-registry': ['wc', 'wcBlocksRegistry'],
  '@woocommerce/settings': ['wc', 'wcSettings'],
  '@woocommerce/*': ['wc', '*'],
};

const wcHandleMap = {
  '@woocommerce/blocks-registry': 'wc-blocks-registry',
  '@woocommerce/settings': 'wc-settings',
  '@woocommerce/*': ['wc', '*'],
};

const requestToExternal = (request) => {
  console.log(request);
  if (wcDepMap[request]) {
    return wcDepMap[request];
  }
};

const requestToHandle = (request) => {
  if (wcHandleMap[request]) {
    return wcHandleMap[request];
  }
};


module.exports = merge(defaultConfig, {
  entry: {
    'plugin': '/assets/styles/plugin.pcss',
    'mini-cart': [
      '/assets/apps/mini-cart/app.js'
    ],
    'side-cart': [
      '/assets/apps/side-cart/app.js'
    ],
    'search': [
      '/assets/apps/search/app.js'
    ]
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'assets/apps'),
      '@styles': path.resolve(__dirname, 'assets/styles'),
    },
  },
  output: {
    path: path.resolve(__dirname, 'web/app/themes/wpify-skeleton/build'),
  },
  plugins: [
    ...defaultConfig.plugins.filter(
        (plugin) =>
            plugin.constructor.name !== 'DependencyExtractionWebpackPlugin'
    ),
    new WooCommerceDependencyExtractionWebpackPlugin({
      requestToExternal,
      requestToHandle
    }),
    new BrowserSyncPlugin({
      files: [
        './build/**/*.css',
        './build/**/*.js',
        './build/**/*.svg',
        './**/*.twig',
        './**/*.php',
        './*.php',
        './**/*.php',
      ],
      ...(
        fs.existsSync('./.ddev/traefik/certs/wpify-skeleton.key') && fs.existsSync('./.ddev/traefik/wpify-skeleton/events.crt')
          ? {
            https: {
              key: './.ddev/traefik/certs/wpify-skeleton.key',
              cert: './.ddev/traefik/certs/wpify-skeleton.crt',
            },
          }
          : {}
      ),
    }, {
      injectCss: true,
      reload: true,
    }),
  ],
});
