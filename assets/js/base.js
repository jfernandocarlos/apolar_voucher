jQuery(document).ready(function(){

	jQuery("button.apo-voucher-button").click(function(e){
	  e.preventDefault();

	  jQuery("#modalFormVoucher").remove();

	  var referencia = jQuery.trim(jQuery(this).attr("data-referencia"));

	  jQuery.ajax({
	  	url: PLUGIN_VOUCHER_URL + 'actions.php?action=formvoucher',
	  	type: 'Post',
	  	dataType: 'html',
	  	data: {
	  		referencia: referencia
	  	},
	  	success: function(response) {
	  		jQuery("body").append(response);

	  		jQuery("form#formVoucher input[name='document']").mask("999.999.999-99");

	  		jQuery("form#formVoucher input[name='fone']").focusout(function(){
			    var phone, element;
			    element = jQuery(this);
			    element.unmask();
			    phone = element.val().replace(/\D/g, '');
			    if(phone.length > 10) {
			        element.mask("(99) 99999-999?9");
			    } else {
			        element.mask("(99) 9999-9999?9");
			    }
			}).trigger('focusout');


	  		jQuery("#modalFormVoucher").modal("show");
	  	}
	  });


	});


	jQuery("body").delegate("button#receber-voucher-bt","click",function(e){
		e.preventDefault();

		jQuery("#modalVoucherFinish").remove();
		jQuery("form#formVoucher div").removeClass("input-error");

		var error = false;

		jQuery("form#formVoucher input[type='text']").each(function(){
			if ( jQuery.trim(jQuery(this).val()) == "" ) {
				jQuery(this).parent().addClass("input-error");
				error = true;
			}
		});


		if ( !validateEmail( jQuery.trim(jQuery("form#formVoucher input[name='email']").val()) ) ) {
			jQuery("form#formVoucher input[name='email']").parent().addClass("input-error");
			error = true;
		}

		if ( !validaCPF( jQuery.trim(jQuery("form#formVoucher input[name='document']").val()) ) ) {
			jQuery("form#formVoucher input[name='document']").parent().addClass("input-error");
			error = true;
		}

		if ( error == true ) {
			return false;
		}

		jQuery("form#formVoucher").fadeOut("fast",function(){
			jQuery("#msg_processing_voucher").fadeIn("fast");
		});


		var data = jQuery("form#formVoucher").serialize();

		jQuery.ajax({
		  	url: PLUGIN_VOUCHER_URL + 'actions.php?action=registervoucher',
		  	type: 'Post',
		  	dataType: 'html',
		  	data: data,
		  	success: function(response) {


		  		
		  		//


		  		jQuery("#modalFormVoucher").modal("hide");
		  		jQuery("body").append(response);
		  		jQuery("#modalVoucherFinish").modal("show");

		  		//modalVoucherFinish
		  	}
		  });


	});

});


function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

 function validaCPF(cpf)
  {

  	cpf = cpf.replace(/[^0-9]/g,"");

    var numeros, digitos, soma, i, resultado, digitos_iguais;
    digitos_iguais = 1;
    if (cpf.length < 11)
          return false;
    for (i = 0; i < cpf.length - 1; i++)
          if (cpf.charAt(i) != cpf.charAt(i + 1))
                {
                digitos_iguais = 0;
                break;
                }
    if (!digitos_iguais)
          {
          numeros = cpf.substring(0,9);
          digitos = cpf.substring(9);
          soma = 0;
          for (i = 10; i > 1; i--)
                soma += numeros.charAt(10 - i) * i;
          resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
          if (resultado != digitos.charAt(0))
                return false;
          numeros = cpf.substring(0,10);
          soma = 0;
          for (i = 11; i > 1; i--)
                soma += numeros.charAt(11 - i) * i;
          resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
          if (resultado != digitos.charAt(1))
                return false;
          return true;
          }
    else
        return false;
  }