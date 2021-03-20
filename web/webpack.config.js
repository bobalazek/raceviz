var Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    .addEntry('website', './assets/apps/website/index.js')
    .addEntry('race-editor', './assets/apps/race-editor/index.js')
    .addEntry('race-viewer', './assets/apps/race-viewer/index.ts')

    .splitEntryChunks()

    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableReactPreset()
    .enableEslintLoader((config) => {
      config.configFile = __dirname + '/.eslintrc.js';
    })

    .configureBabel(() => {}, {
        useBuiltIns: 'usage',
        corejs: 3,
    })

    .enableSassLoader()
    .enableTypeScriptLoader((tsConfig) => {
      tsConfig.compilerOptions = {
        sourceMap: true,
        esModuleInterop: true,
      };
    })

    .enableIntegrityHashes(Encore.isProduction())

    .autoProvidejQuery()
;

Encore.configureWatchOptions(watchOptions => {
    watchOptions.poll = 250;
});

let config = Encore.getWebpackConfig();

config.module.rules.push({
  test: /\/Resources\/(.*)\.(jpg|png|gif|glb|gltf|hdr)$/i,
  use: [
    {
      loader: 'url-loader',
      options: {
        limit: false,
        name: 'resources/[name].[hash:8].[ext]',
      },
    },
  ],
});

module.exports = config;
