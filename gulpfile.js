var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('inspinia-scss/style.scss', 'public/css/admin-style.css');
    mix.sass('font-awesome/scss/font-awesome.scss', 'public/css/font-awesome.css');
    mix.scripts([
    	'resources/assets/js/jquery-2.1.1.js',
        'resources/assets/js/plugins/jquery-ui/jquery-ui.min.js',
    	'resources/assets/js/bootstrap.min.js',
    	'resources/assets/js/plugins/metisMenu/jquery.metisMenu.js',
    	'resources/assets/js/plugins/slimscroll/jquery.slimscroll.min.js',
    	'resources/assets/js/plugins/flot/jquery.flot.js',
    	'resources/assets/js/plugins/flot/jquery.flot.tooltip.min.js',
    	'resources/assets/js/plugins/flot/jquery.flot.spline.js',
    	'resources/assets/js/plugins/flot/jquery.flot.resize.js',
    	'resources/assets/js/plugins/flot/jquery.flot.pie.js',
    	'resources/assets/js/plugins/peity/jquery.peity.min.js',
        'resources/assets/js/plugins/jasny/jasny-bootstrap.min.js',
    	'resources/assets/js/demo/peity-demo.js',
    	'resources/assets/js/plugins/pace/pace.min.js',
        'resources/assets/js/plugins/iCheck/icheck.min.js',
    	'resources/assets/js/plugins/gritter/jquery.gritter.min.js',
    	'resources/assets/js/plugins/easypiechart/jquery.easypiechart.js',
    	'resources/assets/js/plugins/sparkline/jquery.sparkline.min.js',
    	'resources/assets/js/demo/sparkline-demo.js',
    	'resources/assets/js/plugins/chartJs/Chart.min.js',
        'resources/assets/js/inspinia.js',
        'resources/assets/js/jquery.waypoints.min.js',     
        'resources/assets/js/laroute.js', 
        'resources/assets/js/mustache.min.js', 
        'resources/assets/js/crud.js',
        'resources/assets/js/video.js',
        'resources/assets/js/media.js',
        'resources/assets/js/moment.js',
        'resources/assets/js/moment-timezone.js',
        'resources/assets/js/moment-timezone-with-data-2012-2022.min.js',
    	'resources/assets/js/custom.js',
    	], 'public/js/app.js');
	mix.copy(
		   'resources/assets/font-awesome/fonts',
		   'public/css/fonts')
		.copy(
		   'resources/assets/fonts',
		   'public/fonts')
		.copy(
			'resources/assets/css',
			'public/css')
        .copy(
            'resources/assets/js/plugins',
            'public/js/');
});