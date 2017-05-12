<?php

/**
 * Função responsável por gerar botão de voucher
 */
function do_voucherbuttom($atts) {

	$attributes = array("referencia" => "","texto"=>"","size" => "", "eventos"=>"");

	extract(
			shortcode_atts($attributes, $atts)
			);

	$texto = trim($texto) != "" ? trim($texto) : "SOLICITAR VOUCHER";
	$size = trim($size) != "" ? "apo-voucher-button-$size" : "";

	$out = "<button class='apo-voucher-button $size ' data-referencia='$referencia'>$texto</button>";

	echo $out;
	
}

add_shortcode('apovoucherbuttom', 'do_voucherbuttom');


function load_voucher_assets() {

	wp_register_script('voucherboostrap', PLUGIN_VOUCHER_URL . "assets/js/bootstrap.min.js",array("jquery"));
    wp_enqueue_script('voucherboostrap' );

    wp_register_script('jquery.maskedinput', PLUGIN_VOUCHER_URL . "assets/js/jquery.maskedinput.min.js",array("jquery"));
    wp_enqueue_script('jquery.maskedinput' );

	wp_register_script('voucherbase', PLUGIN_VOUCHER_URL . "assets/js/base.js",array("voucherboostrap"));
	wp_localize_script('voucherbase', 'PLUGIN_VOUCHER_URL', PLUGIN_VOUCHER_URL);
    wp_enqueue_script('voucherbase' );

   	wp_register_style( 'voucherboostrapcss', PLUGIN_VOUCHER_URL . "assets/css/bootstrap.min.css");
    wp_enqueue_style( 'voucherboostrapcss' );

    wp_register_style( 'voucherboostrapthemecss', PLUGIN_VOUCHER_URL . "assets/css/bootstrap-theme.min.css");
    wp_enqueue_style( 'voucherboostrapthemecss' );

    wp_register_style( 'vouchercss', PLUGIN_VOUCHER_URL . "assets/css/voucher.css");
    wp_enqueue_style( 'vouchercss' );
}
add_action( 'wp_enqueue_scripts', 'load_voucher_assets');


