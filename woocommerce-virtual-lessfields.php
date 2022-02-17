<?php if ( ! defined( 'WPINC' ) ) die;
/**
 * Plugin Name:       WooCommerce Virtual less fields
 * Description:       Less address fields for virtual only orders
 * Version:           0.1.1
 * Author:            Magiiic
 * Author URI:        https://magiiic.com
 * Plugin URI:        https://git.magiiic.com/wordpress/woocommerce-virtual-lessfields
 * License:           GNU General Public License v2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 4.6
 * Requires PHP:      5.6
 *
 * Adapted from: https://wpbeaches.com/remove-address-fields-in-woocommerce-checkout/
 */

add_filter( 'woocommerce_checkout_fields' , 'wcvlf_checkout_fields_filter' );

/**
* WooCommerce Remove Address Fields from checkout based on presence of virtual products in cart
* @link https://www.skyverge.com/blog/checking-woocommerce-cart-contains-product-category/
* @link https://docs.woothemes.com/document/tutorial-customising-checkout-fields-using-actions-and-filters/
* @link https://businessbloomer.com/woocommerce-hide-checkout-billing-fields-if-virtual-product-cart/
*/
function wcvlf_checkout_fields_filter( $fields ) {

  // set our flag to be true until we find a product that isn't virtual
  $virtual_products = true;

  // loop through our cart
  foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
    // Check if there are non-virtual products and if so make it false
    if ( ! $cart_item['data']->is_virtual() || $cart_item['data']->get_meta( '_domainname' ) == 'yes' ) {
      $virtual_products = false;
      break;
    }
  }

  // only unset fields if virtual_products is true so we have no physical products in the cart
  if( $virtual_products===true) {
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_phone']);
    //Removes Additional Info title and Order Notes
    add_filter( 'woocommerce_enable_order_notes_field', '__return_false',9999 );
  }

  return $fields;
}
