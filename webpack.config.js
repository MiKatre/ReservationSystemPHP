var Encore = require('@symfony/webpack-encore');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    .addEntry('js/app', ['babel-polyfill', './assets/js/app.js'])


    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    .configureBabel(function (babelConfig) {
        babelConfig.presets.push('stage-3');
        babelConfig.presets.push('env');
        babelConfig.plugins.push(['import', { "libraryName": "antd", "libraryDirectory": "es", "style": true }]);
    })
    .enableReactPreset()

    .enableLessLoader(function(options) {
        options.modifyVars = {
            'primary-color': '#1DA57A',
            'link-color': '#1DA57A',
        };
        options.javascriptEnabled = true;
    });

;

module.exports = Encore.getWebpackConfig();
