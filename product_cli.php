<?php
/**
 * Product_cli class
 *
 * @package Ndevr
 * @since 1.0.0
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

class Product_CLI extends \WP_CLI_Command {

	/**
	 * Untag all early access classes when public access date is past.
	 *
     * 
     * @return array 
	 */
	// Your function goes here.
    public function remove_term() {

        $today = date( "Y-m-d" );
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',            
            'meta_query' => array(
                array(
                    'key' => 'public_access_date',                    
                    'value' => $today,
                    'compare' => '>',
                    'type' => 'datetime'
                )
            )
        );

        $products = get_posts( $args );

        if ( !empty( $products ) ) {
            foreach( $products as $product ) {
                wp_remove_object_terms( $product->ID, 'early-access', 'product_cat' );

                $product_name = $proudct->post_name;
                $access_date = get_post_meta( $product->ID, 'public_access_date', true );
                $printed_date = date( "Y-m-d", strtotime( $access_date ) );

                WP_CLI::line( "removed: $product_name : $printed_date" );
            }

            $total_removed_product_count = count( $products );
            WP_CLI::succcess( "$total_removed_product_count Products is removed " );
        } else {
            WP_CLI::succcess( "No Products" );
        }
    }
}

WP_CLI::add_command( 'product', 'Product_CLI' );