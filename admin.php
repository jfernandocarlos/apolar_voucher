<?php

session_start();

/**
 * Pagina ADM do Plugin 
 */
function voucher_admin_menu() {
	add_menu_page( 'Apolar Voucher', 'Apolar Voucher', 'manage_options', basename(__DIR__) . "/admin.php", 'voucher_admin', 'dashicons-tickets-alt', 6  );
}

add_action( 'admin_menu', 'voucher_admin_menu' );

function voucher_admin() {

    $referencia = isset($_POST['referencia']) ? trim($_POST['referencia']) : "";
    $dateBegin = isset($_POST['date_ini']) ? trim($_POST['date_ini']) : date("d/m/Y");
    $dateEnd = isset($_POST['date_fim']) ? trim($_POST['date_fim']) : date("d/m/Y");
    $vouchers = getVouchers($referencia, $dateBegin, $dateEnd);
    $_SESSION['vouchers'] = $vouchers;

	require_once dirname(__FILE__) . DS . "templates/admin_page.php";
}

function getVouchers($referencia="", $dateBegin= "", $dateEnd="") {
    global $wpdb;

    $where = "";

    $dateBegin  =  trim($dateBegin) != "" ? date("Y-m-d",strtotime(str_replace("/", "-", $dateBegin))) : "";
    $dateEnd    =  trim($dateEnd) != "" ? date("Y-m-d",strtotime(str_replace("/", "-", $dateEnd))) : "";

    if ( trim($dateBegin) == "" && trim($dateEnd) == "" ) {
        $where .= " WHERE date(created) = '" . date("Y-m-d") . "' ";
    } else if ( trim($dateBegin) != "" && trim($dateEnd) == "" ) {
        $where .= " WHERE date(created) >= '" . $dateBegin . "' ";
    } else if ( trim($dateBegin) == "" && trim($dateEnd) != "" ) {
        $where .= " WHERE date(created) <= '" . $dateEnd . "' ";
    } else {
        $where .= " WHERE date(created) BETWEEN '$dateBegin' AND '$dateEnd' ";
    }

    if ( trim($referencia) != "" ) {
        $where .= " AND property_reference = '$referencia' ";
    }

    $vouchers = $wpdb->get_results($wpdb->prepare ("SELECT * FROM ". APO_VOUCHER_TABLE ." $where ORDER BY created DESC",array()));
    return $vouchers;
}

function load_admin_voucher_assets() {

    wp_register_script('datatables', PLUGIN_VOUCHER_URL . "assets/js/jquery.dataTables.min.js",array("jquery"));
    wp_enqueue_script('datatables' );

    wp_register_script('maskedinput', PLUGIN_VOUCHER_URL . "assets/js/jquery.maskedinput.min.js",array("jquery"));
    wp_enqueue_script('maskedinput' );


    wp_register_script('voucheradmin', PLUGIN_VOUCHER_URL . "assets/js/admin.js",array("datatables"));
    wp_localize_script('voucheradmin', 'PLUGIN_VOUCHER_URL', PLUGIN_VOUCHER_URL);
    wp_enqueue_script('voucheradmin' );

    wp_register_style( 'datatablescss', PLUGIN_VOUCHER_URL . "assets/css/jquery.dataTables.min.css");
    wp_enqueue_style( 'datatablescss' );

	wp_register_style( 'voucheradmincss', PLUGIN_VOUCHER_URL . "assets/css/admin.css");
    wp_enqueue_style( 'voucheradmincss' );


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
add_action( 'admin_enqueue_scripts', 'load_admin_voucher_assets' );