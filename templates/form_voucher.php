<div id="modalFormVoucher" style="display:none" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">

		<div class="modal-header">
	    	<h4 class="modal-title"><?php echo $_SESSION['voucher_default_text']->title_voucher_form ?></h4>
	  	</div>

      <!-- dialog body -->
      <div class="modal-body">
        
        <form id="formVoucher" >

			<input type="hidden" name="referencia" value="<?php echo $referencia ?>" />

			<div class="form-input">
				<label>Nome</label>
				<input type="text" name="name" />
			</div>

			<div class="form-input">
				<label>E-mail</label>
				<input type="text" name="email" />
			</div>

			<div class="form-input">
				<label>Telefone</label>
				<input type="text" name="fone" />
			</div>

			<div class="form-input">
				<label>CPF</label>
				<input type="text" name="document" />
			</div>

			<div class="form-input">				
				<input style="float:left; margin-top: 5px" type="checkbox" value="1" name="newsletter" />
				<label style="float:left">Desejo receber novidades da Apolar</label>
				<div class="clear"></div>
			</div>


		</form>

		<div id="msg_processing_voucher"><?php echo $_SESSION['voucher_default_text']->msg_processing_voucher ?></div>


      </div>
      <!-- dialog buttons -->
      <div class="modal-footer"><button type="button" id="receber-voucher-bt" class="btn btn-primary"><?php echo $_SESSION['voucher_default_text']->button_voucher_form ?></button></div>
    </div>
  </div>
</div>


