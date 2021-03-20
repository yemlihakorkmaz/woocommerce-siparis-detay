<?php 

/**
 * Korkmaz Woo Kargo Takip ve Sipariş Detay
 *
 * @package           wookargotakip
 * @author            Yemliha KORKMAZ
 * @copyright         2020 yemlihakorkmaz
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Kargo Takip ve Sipariş Detay -KORKMAZ
 * Plugin URI:        https://yemlihakorkmaz.com/korkmaz-siparis-detay-eklentisi
 * Description:       Woocommerce eklentisi için kargo takip numaraası ekleyebilme ve Sipariş bilgisi sayfasında işlem adamlarını daha güzel gösterebilmenize yarar. 
 * Version:           1.1
 * Requires at least: 5.0
 * Requires PHP:      5.0
 * Author:            yemlihakorkmaz
 * Author URI:        https://yemlihakorkmaz.com
 * Text Domain:       wookargotakip
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// yönetici değilsen çık
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

    .woocommerce-bacs-bank-details {
        display: none !important;

    }

</style>


<div id="thankyoumesaj">

    <p>Sayın : <?php echo $order->get_formatted_billing_full_name(); ?></p>

    <p>Yapmış olduğunuz siparişiniz kaydedilmiştir.</p>

    <p>Sipariş numaranız: <strong><?php echo $order->get_order_number();?></strong>'dir</p>

    <?php if ($order->get_status() == 'on-hold') :?>

    <p>Bu aşamadan sonra banka hesaplarımıza <strong><?php echo $order->get_formatted_order_total(); ?></strong> TL tutarında ücret havale etmeniz beklenecektir. Ücret havale ettikten sonra tarafımıza bilgi vermeniz halinde siparişinizi hemen hazırlayıp kargoya vereceğiz.</p>

    <?php endif; ?>

    <?php if ($order->get_status() == 'processing') :?>

    <p>Kredi kartınızdan satın aldığınız ürünlerin toplam fiyatı olan <strong><?php echo $order->get_formatted_order_total(); ?></strong> TL tahsil edilmiştir.</p>

    <?php endif; ?>

    <p>Tüm siparişlerinizin durumunu üyelik bölümündeki "Siparişlerim" başlığı altında izleyebilirsiniz.</p>

    <p>Alışverişiniz için teşekkür ederiz.</p>
    <br><br>

</div>



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

    <?php } 
       
   }else {
       
 if($order->get_status() == 'pending' || $order->get_status() == 'on-hold') {?>

    <li><strong style="background: #cc0a00; color: #fff !important; padding: 5px 15px;">Ödeme Bekleniyor</strong><span class="dashicons dashicons-arrow-left-alt"></span></li>
    <?php }else {   ?>

    <li>Ödeme Bekleniyor</li>

    <?php } ?>


    <?php if($order->get_status() == 'processing') {?>

    <li><strong style="background: #cc0a00; color: #fff !important; padding: 5px 15px;">İşleme Alındı</strong><span class="dashicons dashicons-arrow-left-alt"></span></li>
    <?php }else {   ?>

    <li>İşleme Alındı</li>

    <?php }  if($order->get_status() == 'hazirlaniyor') {?>

    <li><strong style="background: #cc0a00; color: #fff !important; padding: 5px 15px;">Hazırlanıyor</strong><span class="dashicons dashicons-arrow-left-alt"></span></li>
    <?php }else {   ?>
    <li>Hazırlanıyor</li>

    <?php } if($order->get_status() == 'completed') {?>

    <li><strong style="background: #cc0a00; color: #fff !important; padding: 5px 15px;">Kargoya Verildi</strong>

        <span class="dashicons dashicons-arrow-left-alt"></span></li>


    <?php 
        $kargo_takip_kodu = get_post_meta( $order->get_id(), 'kargo_takip_kodu', true );
        
        if(!empty($kargo_takip_kodu)) {
            
            echo '<p>
            Paketinizi Yurtiçi Kargo ile belirttiğiniz adrese ulaştıracağız. Kargonuzuz durumunu görmek için tıklayınız.
            
            
            </p><li><button style="background-color:#ff8f21; padding:8px 22px; color:#fff; border:none;"><a style="color:#fff;" href="https://www.yurticikargo.com/tr/online-servisler/gonderi-sorgula?code='.$kargo_takip_kodu.'" rel="_blank" target="_blank">Kargo Takibi</a></button></li>';
            
        }
        
        ?>



    <?php }else {   ?>

    <li>Kargoya Verildi</li>

    <?php } }?>




</ol>
<br>
<br>




<?php }


// kargo takip modülü
add_action( 'woocommerce_admin_order_data_after_order_details', 'kargo_takip_alan' );
 
function kargo_takip_alan( $order ){  ?>

<br class="clear" />
<h4>KARGO TAKİP <a href="#" class="edit_address">Edit</a></h4>
<?php 

			$kargo_takip_kodu = get_post_meta( $order->get_id(), 'kargo_takip_kodu', true );
		?>
<div class="address">
    <p><strong>Kargo Takip Kodu:</strong> <?php echo $kargo_takip_kodu ?></p>


</div>
<div class="edit_address"><?php
 
 
			woocommerce_wp_text_input( array(
				'id' => 'kargo_takip_kodu',
				'label' => 'Kargo Takip Kodu:',
				'value' => $kargo_takip_kodu,
				'wrapper_class' => 'form-field-wide'
			) );
 

 
		?></div>


<?php }
 
add_action( 'woocommerce_process_shop_order_meta', 'misha_save_general_details' );
 
function misha_save_general_details( $ord_id ){

	update_post_meta( $ord_id, 'kargo_takip_kodu', wc_clean( $_POST[ 'kargo_takip_kodu' ] ) );
	// wc_clean() and wc_sanitize_textarea() are WooCommerce sanitization functions
}


// kargo linki mail için shortcode
add_filter( 'woocommerce_email_format_string' , 'filter_email_format_string', 20, 2 );
function filter_email_format_string( $string, $email ) {
    // Get the instance of the WC_Order object
    $order = $email->object;
    $kargo_takip_kodu = get_post_meta( $order->get_id(), 'kargo_takip_kodu', true );
    $yurticilink = ' <a href="https://www.yurticikargo.com/tr/online-servisler/gonderi-sorgula?code='.$kargo_takip_kodu.'">KARGONUZUN TAKİBİ İÇİN TIKLAYINIZ</a>';

    $additional_placeholders = array(

        '{kargo_takip}'   => $yurticilink,
    );


    return str_replace( array_keys( $additional_placeholders ), array_values( $additional_placeholders ), $string );
}



add_action( 'wp', function() {
  remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20 );
  remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_terms_and_conditions_page_content', 30 );
} );


?>