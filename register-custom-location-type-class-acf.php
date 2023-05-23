<?php

add_action('acf/init', 'my_acf_init_location_types');
function my_acf_init_location_types()
{
    // Check function exists, then include and register the custom location type class.
    if (function_exists('acf_register_location_type')) {
        include_once('includes/class-my-acf-location-product-cat.php');
        acf_register_location_type('My_ACF_Location_Product_category');
    }
}
