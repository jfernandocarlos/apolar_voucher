<?php

session_start();

if(!defined('DS')){
	define('DS', DIRECTORY_SEPARATOR);
}

if ( !defined('ROOT_PATH') ) {
	define('ROOT_PATH', str_replace( DS . 'wp-content' . DS . 'plugins' . DS . basename(__DIR__) , "", dirname(__FILE__)));
}


require_once( ROOT_PATH . DS . 'wp-config.php');
require_once(dirname(__FILE__) . DS . "settings.php");



require_once dirname(__FILE__) . DS . "libs/dompdf/autoload.inc.php";
use Dompdf\Adapter\CPDF;
use Dompdf\Dompdf;
use Dompdf\Exception;
use Dompdf\Options;


$action = trim($_REQUEST['action']);

unset($_REQUEST['action']);

$data   = $_REQUEST;

$_SESSION['voucher_default_text'] = json_decode(file_get_contents(PLUGIN_VOUCHER_URL . "templates/default.texts.json"));

switch ($action) {
	case 'formvoucher':
		getFormVoucher($data);
		break;

	case 'registervoucher':
		registerVoucher($data);
		break;

	case 'getvoucher':
		getVoucher($data['id']);
		break;

	case 'exportcsv':
		exportCsv();
		break;

	case 'savevoucherconfig':
		saveVoucherConfig($data);
		break;	
	
	default:
		# code...
		break;
}


function getFormVoucher($data) {
	$referencia = $data['referencia'];
	require_once dirname(__FILE__) . DS . "templates/form_voucher.php";
}

function registerVoucher($data) {

	global $wpdb;

	$propertyData = $wpdb->get_row($wpdb->prepare ("SELECT * FROM ". TABLE_PROPERTIES_VOUCHER ." WHERE referencia = '%s' LIMIT 1",$data['referencia']));

	$voucherData = array();
	$voucherData['property_reference'] = $data['referencia'];
	$voucherData['property_type'] = $propertyData->finalidadeimovel;
	$voucherData['property_size'] = $propertyData->metragem;
	$voucherData['original_price'] = $propertyData->valorvenda;
	$voucherData['promotional_price'] = $propertyData->valorEvento;
	$voucherData['validity'] = $propertyData->dataFimEvento;
	$voucherData['name'] = $data['name'];
	$voucherData['email'] = $data['email'];
	$voucherData['fone'] = $data['fone'];
	$voucherData['document'] = $data['document'];
	$voucherData['created'] = date("Y-m-d H:i:s");

	if ( isset($data['newsletter']) && $data['newsletter'] == "1" ) {
		$voucherData['newsletter'] = 1;
	}

	$wpdb->insert(APO_VOUCHER_TABLE, $voucherData);

	$voucherData['id'] = $wpdb->insert_id;;

	ob_start();
	getVoucher(null,$voucherData);
	$voucher = ob_get_contents();
	ob_end_clean();

	$options = new Options();
	$options->set('isRemoteEnabled', true);
	$dompdf = new Dompdf($options);
    $dompdf->load_html($voucher);
    $dompdf->set_paper('A4', 'landscape');
    $dompdf->render();
    $output = $dompdf->output();

    $pdf_file = dirname(__FILE__) . DS . 'temp/voucher.' . $voucherData['id'] . '.pdf';

    file_put_contents($pdf_file, $output);


    _sendClientEmail($voucherData['email'], $propertyData, $voucher, $pdf_file);

    _sendRealEstateEmail($propertyData, $voucherData, $pdf_file);


    @unlink($pdf_file);

  
    require_once dirname(__FILE__) . DS . "templates/register_voucher.php";



}


function _sendClientEmail($email, $propertyData, $voucher, $pdfFile) {


	  $mensagem = $_SESSION['voucher_default_text']->msg_email_voucher_ok;
	  $mensagem = str_replace("%dpto%", $propertyData->transacao, $mensagem);
	  $mensagem = str_replace("%lojafone%", $propertyData->lojatelefone, $mensagem);
	  $mensagem = str_replace("%loja%", $propertyData->loja, $mensagem);

	  $mensagem = htmlentities($mensagem);

	$msg  = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
		<html xmlns='http://www.w3.org/1999/xhtml'>
			<head>
				<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
				<title>Apolar Im&oacute;veis - Cupom de Desconto</title>
				<style>
					body, table, td{font-family:arial;}
				</style>
			</head>
			<body bgcolor='#ffffff'>
				<table width='805' border='0' align='center' cellpadding='5' cellspacing='5' class='bordaPadrao'  bgcolor='#f9f9f9' >
					<tr>
						<td align='center' valign='middle' class='bordaTabela'  Style='font-size:28px;'>
							<img src='". PLUGIN_VOUCHER_URL ."assets/img/apolar-feirao.png' style='margin-left:60px;margin-bottom:5px;width:250px;float:left;'>
							<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cupom de Desconto
						</td>
					</tr>
					<tr>
						<td align='left' valign='middle' class='bordaTabela' Style='font-size:14px;line-height:160%;letter-spacing:0.02em;padding:10px;'>
							<p Style='margin-top:10px;margin-bottom:10px;float:left'>
								$mensagem
							</p>
						</td>
					</tr>
				</table>
			</body>
		</html>";

	//$msg = utf8_encode($msg);

	$headers[] = "Content-type: text/html";
	$headers[] = "From: Apolar Imoveis <$propertyData->lojaemail>";
	wp_mail($email,"Apolar Imoveis - Cupom de Desconto",$msg,$headers, $pdfFile);
	

}

function _sendRealEstateEmail($propertyData, $voucher, $pdfFile) {

	$voucher['newsletter'] = isset($voucher['newsletter']) && $voucher['newsletter'] == "1" ? "Sim" : "Não";

	$msg  = "	<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
		<html xmlns='http://www.w3.org/1999/xhtml'>
			<head>
				<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
				<title>Apolar Im&oacute;veis - Cupom de Desconto</title>
				<style>
					body, table, td{font-family:arial;}
				</style>
			</head>
			<body bgcolor='#ffffff'>
				<table width='707' border='0' align='center' cellpadding='5' cellspacing='5' class='bordaPadrao'  bgcolor='#FFFFFF' >
					<tr>
						<td align='center' valign='middle' class='bordaTabela'  Style='font-size:28px;background-color:#f0f0f0;'>
							<img src='". PLUGIN_VOUCHER_URL ."assets/img/apolar-feirao.png' style='margin-left:60px;margin-bottom:5px;width:250px;float:left;'>
							<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cupom de Desconto
						</td>
					</tr>
					<tr>
						<td align='left' valign='top' >
							<table width='90%' align='center' height='220'>
								<tr>
									<td>
										<a href='$propertyData->linksite'>
											Refer&ecirc;ncia do Im&oacute;vel: $propertyData->referencia
										</a>
									</td>
								</tr>
									<tr>
									<td>Nome: $voucher[name]</td>
								</tr>
								<tr>
									<td>E-mail: $voucher[email]</td>
								</tr>
								<tr>
									<td>Telefone: $voucher[fone]</td>
								</tr>
								<tr>
									<td>Desejo receber novidades da Apolar: $voucher[newsletter]</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</body>
		</html>";


	//$msg = utf8_encode($msg);
	$headers[] = "Content-type: text/html";
	$headers[] = "From: Apolar Imoveis <$propertyData->lojaemail>";
	//$propertyData->lojaemail
	wp_mail($propertyData->lojaemail,"Apolar Imoveis - Cupom de Desconto",$msg,$headers, $pdfFile);

}


function getVoucher($id = null, $voucherData = null) {

	if ( $id && !is_null($id) ) {
		global $wpdb;
		$voucherData = (Array) $wpdb->get_row($wpdb->prepare ("SELECT * FROM ". APO_VOUCHER_TABLE ." WHERE id = '%d' LIMIT 1",$id));
	} 

	require_once dirname(__FILE__) . DS . "templates/voucher.php";

}

function exportCsv() {
	

	$Vouchers = $_SESSION['vouchers'];

	// output headers so that the file is downloaded rather than displayed
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename="Vouchers.csv"');

	// create a file pointer connected to the output stream
	$output = fopen('php://output', 'w');

	$header = array_keys((Array)$Vouchers[0]);

	// output the column headings
	fputcsv($output, $header);

	if ( isset($Vouchers) && !empty($Vouchers) ) {

		foreach ($Vouchers AS $line) {
			$row = array();

			foreach ($line as $col => $val) {
				$row[] = $val;
			}				

			fputcsv($output, $row);

		}

	}

}

function saveVoucherConfig($data) {

	$configFile = fopen(dirname(__FILE__) . DS . "templates/default.texts.json", "w") or die("0");
	fwrite($configFile, json_encode($data));
	fclose($configFile);

	echo "1";

}