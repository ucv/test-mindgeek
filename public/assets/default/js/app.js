// require('../css/app.css');

// Set jQuery
// var $ = require('jquery');

import $ from 'jquery';
window.$ = window.jQuery = $;
// import 'slick-carousel'

// Popper included in .bundle.js
require('bootstrap/dist/js/bootstrap.bundle.js');
require('slick-carousel/slick/slick.min');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});

// SCSS style
require('../css/app.scss');
require('slick-carousel/slick/slick.scss');
require('slick-carousel/slick/slick-theme.scss');