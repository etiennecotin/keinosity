/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

const $ = require('jquery');
// require('modernizr');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('./bootstrap');
require('bootstrap-datepicker');
require('nanoscroller');
require('magnific-popup');
require('jquery-ui');
require('bootstrap-multiselect');
require('flot');
// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');
$.fn.datepicker.defaults.format = "dd-mm-yyyy";

require('./theme');
require('./theme.init');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});
