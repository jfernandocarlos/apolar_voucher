jQuery(document).ready(function(){


	jQuery("form#filter input[name='date_ini'], form#filter input[name='date_fim']").mask("99/99/9999",{placeholder:"_"});

	jQuery("ul#voucher-menu li").click(function(){

		if ( jQuery(this).hasClass("active") ) {
			return false;
		}

		jQuery("ul#voucher-menu li").removeClass("active");
		jQuery(this).addClass("active");

		var tab = jQuery(this).attr("data-tab");
		jQuery(".voucher-tab").css("display","none");
		jQuery("#" + tab).css("display","block");

	});

	jQuery('table#voucher-list').DataTable( {
	    "scrollX": false,
	    "scrollCollapse": false,
		"paging":   true,
		"ordering": false,
		"searching": false,
		"info":     false,
		"language": {
	        "emptyTable":"Nenhum Voucher encontrado no filtro acima. :("
	    }
	} );

	jQuery("#voucher-save-config").click(function(e){
		e.preventDefault();

		var data = jQuery("form#VoucherConfig").serialize();

		jQuery.ajax({
			url: PLUGIN_VOUCHER_URL + 'actions.php?action=savevoucherconfig',
			type: 'Post',
			dataType: 'json',
			data: data,
			success: function(response) {
				console.log(response);

				if (response == "1") {
					var msg = "Configurações aplicadas com sucesso!";
				} else {
					var msg = "Erro ao aplicar as configurações :(";
				}

				alert(msg);

				//modalVoucherFinish
			}
		});


	});

});