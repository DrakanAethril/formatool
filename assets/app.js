import './bootstrap.js';
const jQuery = require('jquery');
// create global $ and jQuery variables
global.$ = global.jQuery = jQuery;

import 'datatables.net-dt';
import 'datatables.net-rowgroup-dt';

import './js/tabler.min.js'; 



/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import $ from "jquery";
import DataTable from 'datatables.net-dt';
import 'datatables.net-rowgroup-dt';

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/tabler.min.css';
import 'datatables.net-dt/css/jquery.dataTables.min.css';
//import './styles/tabler-flags.min.css';
//import './styles/tabler-payments.min.css';
//import './styles/tabler-vendors.min.css';