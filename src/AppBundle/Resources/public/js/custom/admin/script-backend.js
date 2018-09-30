jQuery(document).ready(function($) {

	//Inclui a função on click ao excluir um registro
	$('.delete_item').click(function(event) {
	    event.preventDefault();
	    var user = confirm("Tem certeza que deseja excluir esse registro?");

	    if (user === true) {
	        window.location.href = $(this).data('url');
	    }
	});

	//Inclui a função on click ao cancelar um registro
	$('.cancel_item').click(function(event) {
		event.preventDefault();
		var user = confirm("Tem certeza que deseja cancelar esse registro?");

		if (user === true) {
				window.location.href = $(this).data('url');
		}
	});

	$(".select2").select2();

//Tipo de pessoa (cadastro novo plano)
	$("#plain_typePlan").click(function(e) { 
		if($(this).is(':checked'))
			$('#plain_qntdPerson').parent().show();
		else 
			$('#plain_qntdPerson').parent().hide();
	});

	//Máscara de telefone com o nono dígito
	$("input.telefone")
	        .mask("(99) 9999-9999?9")
	        .focusout(function (event) {
	            var target, phone, element;
	            target = (event.currentTarget) ? event.currentTarget : event.srcElement;
	            phone = target.value.replace(/\D/g, '');
	            element = $(target);
	            element.unmask();
	            if(phone.length > 10) {
	                element.mask("(99) 99999-999?9");
	            } else {
	                element.mask("(99) 9999-9999?9");
	            }
	        });
	//Máscara de CPF com validação assim que o campo perde o foco
	$("input.cpf").mask("999.999.999-99")
			.focusout(function (event) {
				var target, cpf, element;
				target = (event.currentTarget) ? event.currentTarget : event.srcElement;
				cpf = target.value.replace(/\D/g, '');
				element = $(target);
				if(cpf == ""){
					return true;
				}
				// Elimina CPFs invalidos conhecidos
				if (cpf.length != 11 ||
				cpf == "00000000000" ||
				cpf == "11111111111" ||
				cpf == "22222222222" ||
				cpf == "33333333333" ||
				cpf == "44444444444" ||
				cpf == "55555555555" ||
				cpf == "66666666666" ||
				cpf == "77777777777" ||
				cpf == "88888888888" ||
				cpf == "99999999999"){
					alert("CPF inválido");
					element.focus();
					return false;
				}
				// Valida 1o digito
				add = 0;
				for (i=0; i < 9; i ++)
						add += parseInt(cpf.charAt(i)) * (10 - i);
						rev = 11 - (add % 11);
						if (rev == 10 || rev == 11)
								rev = 0;
						if (rev != parseInt(cpf.charAt(9))){
							alert("CPF inválido");
							element.focus();
							return false;
						}
				// Valida 2o digito
				add = 0;
				for (i = 0; i < 10; i ++)
						add += parseInt(cpf.charAt(i)) * (11 - i);
				rev = 11 - (add % 11);
				if (rev == 10 || rev == 11)
						rev = 0;
				if (rev != parseInt(cpf.charAt(10))){
					alert("CPF inválido");
					element.focus();
					return false;
				}
				return true;
		    
	});
	//Máscara de CEP
	$("input.cep").mask("99999-999");
	//Máscara de porcentagem
	$('.percent').mask('99%');
	//Máscara de money
	$('.money').maskMoney({prefix:'R$ ', allowNegative: false, thousands:'.', decimal:',', affixesStay: false});
	//Aplica o colorpicker ao component

	$("input.datepicker").mask("99/99/9999");

	$('.colorpicker-component').colorpicker();
	//Aplica o colorpicker ao input
	$('.colorpicker-input').colorpicker();

	// // BulletedList

 //   CKEDITOR.config.toolbar = [
 //       [],
 //       '/',
 //       ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo', 'BulletedList', 'Spellchecker'],
 //       '/',
 //       [],
 //       []
 //    ] ;	

});