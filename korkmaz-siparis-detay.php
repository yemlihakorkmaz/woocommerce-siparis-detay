<?php 

/**
 * Korkmaz Sipariş Detay
 *
 * @package           korkmazsiparisdetay
 * @author            Yemliha KORKMAZ
 * @copyright         2020 yemlihakorkmaz
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Sipariş Detay -KORKMAZ
 * Plugin URI:        https://yemlihakorkmaz.com/korkmaz-siparis-detay-eklentisi
 * Description:       Sipariş bilgisi sayfasında işlem adamlarını daha güzel göstermeye yarar. 
 * Version:           1.0.0
 * Requires at least: 5.4
 * Requires PHP:      7.2
 * Author:            yemlihakorkmaz
 * Author URI:        https://yemlihakorkmaz.com
 * Text Domain:       korkmaz-siparis-detay
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}





add_action( 'woocommerce_order_details_before_order_table', 'siparis_detay', 20 );
 
function siparis_detay( $order_id ){ 

$order = wc_get_order( $order_id );

?>



<style>
    ol#shippingdurum {
        list-style-type: none;
        list-style: none;
    }

    #shippingdurum li {
        position: relative;
        margin: 0;
        padding-bottom: 3em;
        padding-left: 20px;
    }

    #shippingdurum li:before {
        content: '';
        background-color: #c00;
        position: absolute;
        bottom: 0px;
        top: 0px;
        left: 6px;
        width: 2px;
    }

    #shippingdurum li:after {
        content: '';
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' aria-hidden='true' viewBox='0 0 32 32' focusable='false'%3E%3Ccircle stroke='none' fill='%23c00' cx='16' cy='16' r='25'%3E%3C/circle%3E%3C/svg%3E");
        position: absolute;
        left: 0;
        top: 5px;
        height: 12px;
        width: 12px;
    }

</style>
<ol id="shippingdurum" style="text-align:left; padding:0 !important; margin:0 !important;">

    <h2 class="woocommerce-order-details__title">Sipariş Durumu</h2>


    <?php 
        
   if($order->get_status() == 'failed') {
       
       echo '<li>Sipariş İşlemi Başarısız</li>';
       
       
   }elseif($order->get_status() == 'cancelled' || $order->get_status() == 'refunded') { ?>

    <?php if($order->get_status() == 'cancelled') {?>

    <li>
        <strong style="background: #cc0a00; color: #fff !important; padding: 5px 15px;">İptal Edildi</strong><span class="dashicons dashicons-arrow-left-alt"></span></li>
    <?php }else {   ?>

    <li>İptal Edildi</li>

    <?php } ?>

    <?php if($order->get_status() == 'refunded') {?>

    <li><strong style="background: #cc0a00; color: #fff !important; padding: 5px 15px;">İade Edildi</strong><span class="dashicons dashicons-arrow-left-alt"></span></li>
    <?php }else {   ?>

    <li>İade Edildi</li>

    <?php } ?>





    <?php
       
   }else {
       
        ?>



    <?php if($order->get_status() == 'pending') {?>

    <li><strong style="background: #cc0a00; color: #fff !important; padding: 5px 15px;">Ödeme Bekleniyor</strong><span class="dashicons dashicons-arrow-left-alt"></span></li>
    <?php }else {   ?>

    <li>Ödeme Bekleniyor</li>

    <?php } ?>


    <?php if($order->get_status() == 'processing') {?>

    <li><strong style="background: #cc0a00; color: #fff !important; padding: 5px 15px;">İşleme Alındı</strong><span class="dashicons dashicons-arrow-left-alt"></span></li>
    <?php }else {   ?>

    <li>İşleme Alındı</li>

    <?php } ?>

    <?php if($order->get_status() == 'hazirlaniyor') {?>

    <li><strong style="background: #cc0a00; color: #fff !important; padding: 5px 15px;">Hazırlanıyor</strong><span class="dashicons dashicons-arrow-left-alt"></span></li>
    <?php }else {   ?>

    <li>Hazırlanıyor</li>

    <?php } ?>

    <?php if($order->get_status() == 'completed') {?>

    <li><strong style="background: #cc0a00; color: #fff !important; padding: 5px 15px;">Kargoya Verildi</strong><span class="dashicons dashicons-arrow-left-alt"></span></li>
    <?php }else {   ?>

    <li>Kargoya Verildi</li>

    <?php }    }?>

</ol>







<?php }


function yeni_siparis_durumu() {
register_post_status( 'wc-hazirlaniyor', array(
'label' => _x( 'Hazırlanıyor', 'WooCommerce Order status', 'text_domain' ),
'public' => true,
'exclude_from_search' => false,
'show_in_admin_all_list' => true,
'show_in_admin_status_list' => true,
'label_count' => _n_noop( 'Approved (%s)', 'Approved (%s)', 'text_domain' )
) );
}
add_filter( 'init', 'yeni_siparis_durumu',10,2 );

function yeni_siparis_durumu2( $order_statuses ) {
$order_statuses['wc-hazirlaniyor'] = _x( 'Hazırlanıyor', 'WooCommerce Order status', 'text_domain' );
return $order_statuses;
}
add_filter( 'wc_order_statuses', 'yeni_siparis_durumu2',10,2 );






?>
