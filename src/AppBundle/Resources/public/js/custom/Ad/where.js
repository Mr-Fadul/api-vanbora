$(document).ready(function() {

    // Aplica o select2 nos campos definidos
    var applySelectTag = function(){
      $(".selectTag").select2();
    }

    // InicializaÃ§Ã£o das tabelas
    initiateAgencyTag();

    // Adiciona novo item ao horÃ¡rio
    $("#agency-addTag").click(function(e){
        e.preventDefault();

        addAgencyItemTag();
        applySelectTag();
    });

    // Seta a funÃ§Ã£o de exclusÃ£o nos itens da tabela de disponibilidade
    $("#agency-tableTag tbody").children().each(function() {
        var agencyItem1 = $(this).closest('tr.agency-itemTag');
        removeAgencyItemTag(agencyItem1);

    });

    applySelectTag();

});

/**
 * FunÃ§Ã£o para inicializar o indice da tabela de disponibilidade
 */
function initiateAgencyTag(){
    // Atualiza o indice do prototype com a quantidade de itens disponÃ­veis
    $('#agency-tableTag').attr('data-index-countTag', $(".agency-itemTag").length);
}

/**
 * MÃ©todo que adiciona um novo dia e horÃ¡rio de disponibilidade.
 */
function addAgencyItemTag() {
    var collectionHolder1 = $('#agency-tableTag');
    var prototype1 = collectionHolder1.attr('data-prototypeTag');
    var count1 = collectionHolder1.attr('data-index-countTag');
    var newForm1 = $(prototype1.replace(/\_\_name\_\_/g, count1));

    collectionHolder1.find('tbody').append(newForm1);
    collectionHolder1.attr('data-index-countTag', ++count1);

    // Adiciona a acao de remover o item
    removeAgencyItemTag(newForm1);

    // Adiciona a validacao de licensas escolhidas
    validaAgencyItemTag(newForm1);

    return newForm1;
}

/**
 * Remove item da tabela de disponibilidade.
 */
function removeAgencyItemTag(agencyItem1){
    agencyItem1.find("a.licences-remove-itemTag").click(function(e){
        e.preventDefault();
        var item1 = this;
        var title1 = $(this).data('confirm-titleTag');
        var text1 = $(this).data('confirm-textTag');
        var line1 = $(item1).closest('tr.agency-itemTag');
        $(line1).remove();

        // Verifica se o botÃ£o de adicionar pode ser habilitado novamente
        if($(".agency-itemTag").length < 7){
            $("#agency-addTag").removeAttr('disabled');
        }

    });
}

/**
 * Valida se existem dias repetidos na tabela de disponibilidade
 */
function validaAgencyItemTag(agencyItem1){
    console.log('teste');
    agencyItem1.find(".agencyTag").change(function(e){

        console.log('Dentro do change agencyTag');

        e.preventDefault();
        var agency1 = $(this);
        var choicedAgency1 = $(this).val();
        var choicedId1 = $(this).attr('id');

        $(".agencyTag").each(function( i ){
            console.log('2');
            if(choicedAgency1 == $(this).val() && ($(this).attr('id') != choicedId1) ){
                alert ("Este item já foi selecionado!");
                agency1.val("");
            }
        });
    });
}