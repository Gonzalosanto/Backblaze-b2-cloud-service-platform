function eliminar(id_boton, idArchivo, nombreArchivo) {

    $("#eliminar" + id_boton).attr("disabled", true);
    $.ajax({
        url: 'DeleteController/deleteFile',
        data: {fileId: idArchivo, fileName: nombreArchivo},
        type: 'POST',
        success: function (dataobject) {
            alert("Archivo eliminado");
            location.reload();
            $("#eliminar" + id_boton).attr("disabled", false);
        },
        complete: function (data) {
        },
        error: function (data) {
            console.log(data.responseText);
        }
    });

}
