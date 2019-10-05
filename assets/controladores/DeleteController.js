function eliminar(id_boton, idArchivo, nombreArchivo) {
    var respuesta = confirm("Seguro desea eliminar el archivo " + nombreArchivo);
    if (respuesta == true) {
        $("#eliminar" + id_boton).attr("disabled", true);
        $.ajax({
            url: 'DeleteController/deleteFile',
            data: {fileId: idArchivo, fileName: nombreArchivo},
            type: 'POST',
            success: function (dataobject) {
                alert("Archivo eliminado. Se recargará la página");
                location.reload();
//                $("#eliminar" + id_boton).attr("disabled", false);
            },
            error: function (data) {
                console.log(data.responseText);
            }
        });

    }
}
