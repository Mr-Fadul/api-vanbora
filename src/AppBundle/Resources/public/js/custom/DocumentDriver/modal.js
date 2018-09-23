var idDocument;
function show(id){
	idDocument = id;
	$('#modalDocumentDriver').modal('show');	
}

function sendDescription(){
	var description;
	description = $('#descriptionDocument').val();
	var route = '/backend/documents/pending/document/'+description+'/'+idDocument;
    $.ajax({ 
        method: "GET",
        url: route,
        success:function(data) {
            description = null;
            idDocument = null;
            var redirect = Routing.generate('backend_document_driver');
            window.location = redirect;
        }
    });
}