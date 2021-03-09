var Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    .addEntry('website', './assets/apps/website/index.js')
    .addEntry('app', './assets/apps/app/index.ts')

    .splitEntryChunks()

    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .configureBabel(() => {}, {
        useBuiltIns: 'usage',
        corejs: 3,
    })

    .enableSassLoader()
    .enableTypeScriptLoader(function(tsConfig) {
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
  test: /\/Resources\/(.*)\.(jpg|png|gif|glb|gltf)$/i,
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
