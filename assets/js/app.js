// loads the jquery package from node_modules
const $ = require('jquery');

require('../css/global.scss');

require('bootstrap');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});
