<?php 
	setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
	date_default_timezone_set('America/Sao_Paulo');
	$desconto = $voucherData['original_price'] - $voucherData['promotional_price'];

	
 ?>

<html>
<head>
	<title></title>
	<style type="text/css">
		#img-stage {
			display: block;
			height: 327px;
			position: relative;
			width: 801px;
			margin: 0 auto;
		}
		img	{
			display: block;
		}

		.fields {
			position: absolute;
			font-weight: bold;
			font-family: arial;
			font-size: 14px;
			color: #252525;
		}

		#cupom {
			left: 374px;
    		top: 9px;
		}

		#desconto {
			left: 566px;
    		top: 9px;
		}

		#extenso {
			left: 140px;
			top: 60px;
		}

		#validade {
			left: 93px;
			top: 102px;
		}

		#referencia {
			left: 315px;
			top: 102px;
		}

		#tipo {
			left: 486px;
			top: 102px;
		}

		#tamanho {
			left: 698px;
			top: 102px;
		}

		#nome {
			left: 48px;
			top: 139px;
		}

		#email {
			left: 100px;
			top: 172px;
		}

		#fone {
			left: 100px;
			top: 200px;
		}

		#documento {
			left: 100px;
			top: 229px;
		}

		#preco {
			left: 100px;
			top: 258px;
		}

		#promocional {
			left: 100px;
			top: 285px;
		}

		#data {
			left: 480px;
			top: 207px;
		}


	</style>
</head>
<body>
	<div id="img-stage">
		<img src="<?php echo PLUGIN_VOUCHER_URL ?>assets/img/voucher-template-1.jpg">

		<div id="cupom" class="fields">#<?php echo $voucherData['id'] ?></div>
		<div id="desconto" class="fields"><?php echo number_format($desconto,2,',','.') ?></div>
		<div id="extenso" class="fields"><?php echo extenso($desconto,1) ?></div>
		<div id="validade" class="fields"><?php echo date("d/m/Y", strtotime($voucherData['validity'])) ?></div>
		<div id="referencia" class="fields"><?php echo $voucherData['property_reference'] ?></div>
		<div id="tipo" class="fields"><?php echo substr($voucherData['property_type'],0,13) ?>.</div>
		<div id="tamanho" class="fields"><?php echo $voucherData['property_size'] ?> m²</div>
		<div id="nome" class="fields"><?php echo $voucherData['name'] ?></div>
		<div id="email" class="fields"><?php echo $voucherData['email'] ?></div>
		<div id="fone" class="fields"><?php echo $voucherData['fone'] ?></div>
		<div id="documento" class="fields"><?php echo $voucherData['document'] ?></div>
		<div id="preco" class="fields"><?php echo number_format($voucherData['original_price'],2,',','.') ?></div>
		<div id="promocional" class="fields"><?php echo number_format($voucherData['promotional_price'],2,',','.') ?></div>
		<div id="data" class="fields"><?php echo strftime('%A, %d de %B de %Y', strtotime($voucherData['created'])) ?></div>

	</div>
</body>
</html>

<?php 



function extenso($valor=0,$tipo=0, $caixa="alta") {
		$valor = strval($valor);
		$valor = str_replace(",",".",$valor);
		if($tipo==1){
			$singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
			$plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
		}
		else{
			$pos   = strpos($valor,".");
			$valor = substr($valor,0,$pos);
			$singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
			$plural = array("", "", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
		}
		$c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
		$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
		$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
		$u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
		$z=0;
		$valor = number_format($valor, 2, ".", ".");
		$inteiro = explode(".", $valor);
		for($i=0;$i<count($inteiro);$i++)
			for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
				$inteiro[$i] = "0".$inteiro[$i];
		$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
		for ($i=0;$i<count($inteiro);$i++) {
			$valor = $inteiro[$i];
			$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
			$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
			$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

			$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru;
			$t = count($inteiro)-1-$i;
			$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
			if ($valor == "000")$z++; elseif ($z > 0) $z--;
			if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t];
			if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? " e " : " e ") : " ") . $r;
		}
		if($caixa=="alta"){
			$rt = strtoupper($rt);
		}
		$maiusculas = array("Á","À","Â","Ã","É","Ê","Í","Ó","Ô","Õ","Ú","Û");
		$minusculas = array("á","à","â","ã","é","ê","í","ó","ô","õ","ú","û");
		for($i=0;$i<count($maiusculas);$i++){
			$rt = ereg_replace($minusculas[$i],$maiusculas[$i],$rt);
		}
		return $rt;
	}