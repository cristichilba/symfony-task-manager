var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // will create public/build/app.js and public/build/app.css
    .addEntry('app', './assets/js/app.js')

    // uncomment for legacy applications that require $/jQuery as a global variable
    .autoProvidejQuery()

    .autoProvideVariables({
        'window.$': 'jquery'
    })

    // uncomment if you use Sass/SCSS files
    .enableSassLoader()
;

module.exports = Encore.getWebpackConfig();
