$(document).ready(function() {

   $('#user_confirmpassword').focusout(function(){
        var pass = $('#user_plainpassword').val();
        var confirmPass = $('#user_confirmpassword').val();
        if(confirmPass.length != 0){
	        if(pass != confirmPass){
	            alert('As senhas não estão iguais!');
	            $('#user_confirmpassword').val("");
	            $('#user_confirmpassword').focus();
	        }
        }
    });

});
