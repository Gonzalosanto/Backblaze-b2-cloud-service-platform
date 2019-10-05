function descargar(idFile, nameFile) {
    alert("Descargar " + nameFile);
//    $("#eliminar" + id_boton).attr("disabled", true);
    $.ajax({
        url: 'DownloadController/downloadFile',
        data: {fileName: nameFile},
        type: 'POST',
        success: function (dataobject) {
            alert("Archivo Descarga");
//            location.reload();
//            $("#eliminar" + id_boton).attr("disabled", false);
        },
        complete: function (data) {
        },
        error: function (data) {
            console.log(data.responseText);
        }
    });

}

function descarga(idFile, nameFile){

    $.ajax({
        url:'DownloadController/authToken',
        type: 'post',
        success: function (dataobject){
            var objeto=JSON.parse(dataobject);
            console.log(objeto, "autorization "+objeto.authorizationToken);
            var autorization = objeto.authorizationToken;
            var dataURL = 'https://f000.backblazeb2.com/file/bucketPruebas/'+nameFile+"?Authorization="+autorization+"&b2ContentDisposition=attachment";
           
            download(dataURL,nameFile);
           
            
            function download(dataURL,filename) {
                var element = document.createElement('a');                            
                element.href=dataURL;            
                element.setAttribute('download', filename);
                element.style.display = 'none';           
                element.click();
                
            }
                       
        },
        error: function(dataobject){
            console.log("ERROR: "+ dataobject);
        }
    });

}
