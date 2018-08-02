// loads the jquery package from node_modules
const $ = require('jquery');

global.$ = global.jQuery = $;

require('../css/global.scss');

require('bootstrap');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
    $('[data-toggle="tooltip"]').tooltip();
});
