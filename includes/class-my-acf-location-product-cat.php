<?php

if (!defined('ABSPATH')) exit;

class My_ACF_Location_Product_category extends ACF_Location
{

    public function initialize()
    {
        $this->name = 'product_category';
        $this->label = __("Product Category", 'acf');
        $this->category = 'product';
        // $this->object_type = 'product';
    }

    public function get_values($rule)
    {
        $choices = array();
        $args = array(
            'taxonomy' => 'product_cat',
            'orderby' => 'name',
            'order' => 'ASC',
            'number' => '',
            'pad_counts' => false
        );
        $product_categories = get_terms($args);

        if (!empty($product_categories)) {
            foreach ($product_categories as $category) {
                $choices[$category->term_id] = $category->name;
            }
        }
        return $choices;
    }

    public function match($rule, $screen, $field_group)
    {
        // Check if we are on a product edit screen
        if (!isset($screen['post_type']) || $screen['post_type'] !== 'product') {
            return false;
        }

        // Check screen args for "post_id" which will exist when editing a post.
        // Return false for all other edit screens.
        if (isset($screen['post_id'])) {
            $post_id = $screen['post_id'];
        } else {
            return false;
        }

        // Load the product object for this edit screen.
        $product = wc_get_product($post_id);
        if (!$product) {
            return false;
        }

        // Get the product categories
        $categories = $product->get_category_ids();

        // Check if the product is in the specified category
        $result = in_array($rule['value'], $categories);

        // Return result taking into account the operator type.
        if ($rule['operator'] == '!=') {
            return !$result;
        }
        return $result;
    }
}
