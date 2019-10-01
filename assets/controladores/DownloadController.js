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
