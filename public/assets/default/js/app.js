// require('../css/app.css');

// Set jQuery
var $ = require('jquery');

// Popper included in .bundle.js
require('bootstrap/dist/js/bootstrap.bundle.js');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});

// SCSS style
require('../css/app.scss');