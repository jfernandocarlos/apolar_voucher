<div class="wrap">
	<h2 style="font-weight: bold; margin-bottom: 15px;"> <span style="font-size: 30px;" class="dashicons dashicons-tickets-alt"></span>&nbsp;&nbsp;Apolar Voucher</h2>

	<div class="admin-voucher-content">

		<p>O plugin <strong>Apolar Voucher</strong> foi desenvolvido unicamente para atender as necessidades e estrutura do Site Apolar,
			sua chamada se da através do shortcode <strong>[apovoucherbuttom]</strong>.</p>

		<ul id="voucher-menu">
			<li data-tab="voucher-default" class="active">Vouchers</li>
			<li data-tab="voucher-config" class="">Configuração</li>
			<div class="clear"></div>
		</ul>

		<div class="voucher-tab" id="voucher-default">

			<?php  if ( !empty($vouchers) ) : ?>
			<a target="_blank" href="<?php echo PLUGIN_VOUCHER_URL ?>actions.php?action=exportcsv" class="voucher-csv">EXPORTAR .CSV</a>
			<?php endif; ?>

			<form id="filter" method="POST">
				<h2>Filtro</h2>
				<input type="text"  value="<?php echo $referencia ?>" name="referencia" placeholder="Referência" />

				<input type="text"  value="<?php echo $dateBegin ?>" name="date_ini" placeholder="Data Inicial" />

				<input type="text"  value="<?php echo $dateEnd ?>" name="date_fim" placeholder="Data Final" />

				<input type="submit" value="Filtrar"/>
			</form>

			<table id="voucher-list">
				<thead>
					<tr>
						<th>Nome</th>
						<th>Telefone</th>
						<th>Imóvel</th>
						<th>Valor</th>
						<th>Desconto</th>
						<th>Criado</th>
					</tr>
				</thead>

				<?php  if ( !empty($vouchers) ) : ?>
					<tbody>
						<?php foreach ($vouchers AS $voucher) : ?>
							<tr>
								<td><?php echo $voucher->name ?></td>
								<td><?php echo $voucher->fone ?></td>
								<td><?php echo $voucher->property_reference ?></td>
								<td> R$ <?php echo number_format($voucher->original_price,2,',','.') ?></td>
								<td> R$ <?php echo number_format($voucher->original_price - $voucher->promotional_price,2,',','.') ?></td>
								<td><?php echo date("d/m/Y H:i:s",strtotime($voucher->created)) ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				<?php endif; ?>

			</table>

		</div>

		<div style="display:none" class="voucher-tab" id="voucher-config">

			<form id="VoucherConfig" >

				<p>Para renderizar o botão de solicitação de voucher basta usar a shortcode <b>[apovoucherbuttom]</b>, esse shortcode espera alguns parametros,
					o parâmetro <b>referencia (referência do imóvel em questão)</b> é obrigatório.</p>

				<h3>Possíveis Paramêtros:</h3>

				<ul class="possiveis">
					<li><strong>referencia</strong> - Referência do imóvel que deseja solicitar o voucher - Obrigatório</li>
					<li><strong>size</strong> - O tamanho que deseja que o countdown apareça, pode ser: <b>md</b> (médio), <b>sm</b> (pequeno) ou <b>lg</b> (grande)</li>
					<li><strong>texto</strong> - Texto que irá aparecer no botão, por padrão aparecerá <b>SOLICITAR VOUCHER</b>.</li>
				</ul>

				<h3>Exemplo de uso:</h3>

				<div class="exemplo">
					<span class="exemplo-code">[apovoucherbuttom referencia="127619"]</span>
					<?php do_shortcode("[apovoucherbuttom referencia='127619']"); ?>
				</div>

				<h3>Parametrização de Texto</h3>

				<?php 

					$defaultsText = json_decode(file_get_contents(PLUGIN_VOUCHER_URL . "templates/default.texts.json"));

				 ?>

				<div class="voucher-param-field">
					<label>Titulo Fomulário Voucher</label>
					<input type="text" value="<?php echo $defaultsText->title_voucher_form ?>" name="title_voucher_form"/>
				</div>

				<div class="voucher-param-field">
					<label>Botão Fomulário Voucher</label>
					<input type="text" value="<?php echo $defaultsText->button_voucher_form ?>" name="button_voucher_form"/>
				</div>

				<div class="voucher-param-field">
					<label>Titulo Voucher Solicitado</label>
					<input type="text" value="<?php echo $defaultsText->title_voucher_ok ?>" name="title_voucher_ok"/>
				</div>

				<div class="voucher-param-field">
					<label>Mensagem Voucher Solicitado</label>
					<textarea cols="60" name="msg_voucher_ok"><?php echo $defaultsText->msg_voucher_ok ?></textarea>
				</div>

				<div class="voucher-param-field">
					<label>Mensagem E-mail Voucher Solicitado</label>
					<textarea cols="60" name="msg_email_voucher_ok"><?php echo $defaultsText->msg_voucher_ok ?></textarea>
				</div>

				<div class="voucher-param-field">
					<label>Mensagem Processando Voucher</label>
					<input type="text" value="<?php echo $defaultsText->msg_processing_voucher ?>" name="msg_processing_voucher"/>
				</div>

				<button id="voucher-save-config" >SALVAR CONFIGURAÇÕES</button>

			</form>

		</div>

		<div class="countdown-footer">

			Apolar Voucher - <?php echo date("Y") ?>

		</div>
		

	</div>

	
</div>