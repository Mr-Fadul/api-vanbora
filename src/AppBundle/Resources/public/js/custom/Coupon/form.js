$(document).ready(function(){
  //Para rodar quando a página carregar
  changeValueInput();
  $( "#coupon_typeValue" ).change(function() {
    changeValueInput();
  });

});

function changeValueInput() {
  var optionSelected = $( "#coupon_typeValue option:selected" ).text();
  if(optionSelected == 'Porcentagem'){
    $(".percentValue").attr("style", "display: block");
    $(".monetaryValue").attr("style", "display: none");

    $("#coupon_percentValue").attr("required", true);
    $("#coupon_monetaryValue").attr("required", false);
  }
  else if(optionSelected == 'Monetário'){
    $(".monetaryValue").attr("style", "display: block");
    $(".percentValue").attr("style", "display: none");

    $("#coupon_monetaryValue").attr("required", true);
    $("#coupon_percentValue").attr("required", false);
  }
}

$("#applyCoupon").click(function(event){
  event.preventDefault();
  var code = $("#code").val();
  var route = Routing.generate('validate_coupon_ajax', { code: code })
      $.ajax({ 
          method: "POST",
          url: route,
          success:function(data) {
              // console.log(data.message)
              if(data.responseCode == 400){
                  swal("Erro!", data.message, "error");
              }else if(data.responseCode == 404){
                alert('Cupom inexistente');
              }else{
                  location.reload();
              }
          }
      })
});