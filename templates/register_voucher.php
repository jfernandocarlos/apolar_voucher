<div id="modalVoucherFinish" style="display:none" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

		<div class="modal-header">
	    	<h4 class="modal-title"><?php echo $_SESSION['voucher_default_text']->title_voucher_ok ?></h4>
	  	</div>

      <!-- dialog body -->
      <div class="modal-body">

        <?php 

          $mensagem = $_SESSION['voucher_default_text']->msg_voucher_ok;
          $mensagem = str_replace("%dpto%", $propertyData->transacao, $mensagem);
          $mensagem = str_replace("%lojafone%", $propertyData->lojatelefone, $mensagem);
          $mensagem = str_replace("%loja%", $propertyData->loja, $mensagem);

          $mensagem = htmlentities($mensagem);

         ?>

		<p><?php echo $mensagem ?></p>

		<?php echo $voucher ?>

      </div>      
    </div>
  </div>
</div>