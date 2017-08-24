//@kenjisakuramoto
//?Inject jQuery

var jq = document.createElement('script');
jq.src = "https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js";
document.getElementsByTagName('head')[0].appendChild(jq);
jQuery.noConflict();

//Link in <head>
<link rel="stylesheet" href="css/*****.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js></script>
<script src="js/jquery.*****.js"></script>

//Check if jQuery is loaded
$ === jQuery

wp_register_script('porto-codemirror', porto_js.'/codemirror.js', array('jquery'), porto_version, true);
wp_enqueue_script('porto-codemirror');

//Enqueue scripts
<?php 
if (function_exists('load_my_scripts')) {
    function load_my_scripts() {
        if (!is_admin()) {
        wp_deregister_script( 'jquery' );
        wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js');
        wp_enqueue_script('jquery');
        wp_register_script('myscript', bloginfo('template_url').'/js/myScript.js'__FILE__), array('jquery'), '1.0', true );
        wp_enqueue_script('myscript');
        }
    }
}
add_action('init', 'load_my_scripts');
?>
