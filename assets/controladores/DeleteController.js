$(document).ready(function () {
    listarArchivos();
});

var lista_archivos = [];

function listarArchivos() {

    $.ajax({
        url: 'ListFilesController/listFiles',
        type: 'POST',
        success: function (dataobject) {

            $("#dataFiles").empty();
            lista_archivos = dataobject.files;

            $.each(dataobject.files, function (index, value) {
                var fileName = "<td>" + value.fileName + "</td>";
                var uploadTimestamp = "<td>" + value.uploadTimestamp + "</td>";
                var botonDescargar = "<button type='button' id=" + index + " onclick='descargar(this.id);' download > Descargar </button>";
                var botonEliminar = "<button type='button' id=" + index + " onclick='eliminar(this.id);' > Eliminar </button>";
                $("#dataFiles").append("<tr id='" + index + "'>" + fileName + uploadTimestamp + uploadTimestamp + "<td id='botonera'> "
                        + botonDescargar + " " + botonEliminar + "</td></tr>");
            });
        },
        error: function (data) {
            console.log(data.responseText);
        }
    });
}

function descargar(id) {

    var nameFile = lista_archivos[id].fileName;

    $.ajax({
        url: 'DownloadController/authToken',
        type: 'post',
        success: function (dataobject) {
            var objeto = JSON.parse(dataobject);
            console.log(objeto, "autorization " + objeto.authorizationToken);
            var autorization = objeto.authorizationToken;
            var dataURL = 'https://f000.backblazeb2.com/file/bucketPruebas/' + nameFile + "?Authorization=" + autorization + "&b2ContentDisposition=attachment";
            download(dataURL, nameFile);
            function download(dataURL, filename) {
                var element = document.createElement('a');
                element.href = dataURL;
                element.setAttribute('download', filename);
                element.style.display = 'none';
                element.click();
            }
        },
        error: function (dataobject) {
            console.log("ERROR: " + dataobject);
        }
    });

}

var numero_formulario = 0;

function subir(id) {

    $('#botonUpload' + id).attr("disabled", true);
    $.ajax({
        url: 'UploadController/uploadFile2',
        type: 'POST',
        success: function (dataobject) { //Funcion que retorna los datos procesados del script PHP .

//  input type='file' name='" + numero_formulario + "' id='" + numero_formulario + "'

            var fileInput = document.querySelector('#'+id+' .agregarFile');

//            var fileInput = document.getElementById(id);
            var file = fileInput.files[0];

            var data = $('input[type=file]#' + id)[0].files[0];
            data.name = file.name.replace(" ", "_"); // Cambiar nombre
            $.ajax({

                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    $('#progress' + id).css({"width": "0%"});
                    $('#progress' + id).css({"background-color": "red"});
                    $('#progress' + id).css({"height": "3px"});
                    $('#progress' + id).css({"text-align": "center"});
                    $('#progress' + id).css({"transition": "width .3s"});
                    $('#progress' + id).css({"margin": "10px;"});
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            console.log(percentComplete);
                            $('#progress' + id).css({
                                width: percentComplete * 100 + '%'
                            });

                        }
                    }, false);
                    return xhr;
                },

                url: dataobject.uploadUrl,
                type: 'POST',
                data: data,
                cache: false,
                processData: false,
                contentType: false,
                headers: {"Authorization": dataobject.authorizationToken,
                    "X-Bz-File-Name": file.name,
                    "Content-Type": file.type,
                    "Content-Lenght": file.size,
                    "X-Bz-Content-Sha1": "do_not_verify",
                    "X-Bz-Info-Author": 'unknown'
                },
                success: function (datos) { //Funcion que retorna los datos procesados del script PHP .
                    listarArchivos();
                    alert("Subido exitosamente");

                },
                error: function (data) {
                    alert("Hubo un error al subir el archivo " + file.name);
                    $('#botonUpload' + id).attr("disabled", false);
                    $('#progress' + id).removeClass('hide');
                }
            });
        },
        complete: function (data) {
            console.log(data);
        },
        error: function (data) {
            console.log(data.responseText);
        }
    });

// https://stackoverflow.com/questions/15410265/file-upload-progress-bar-with-jquery

}

function copiar_formulario() {
    numero_formulario++;
    var formulario = "<br><div id='fileform'><fieldset><legend>Select file to upload:</legend><label for='userfile'>Archivo:</label><input type='file' name='" + numero_formulario + "' id='" + numero_formulario + "' class='agregarFile' ><div id='progress" + numero_formulario + "' class='progress" + numero_formulario + "'> </div><button type='button' id='botonUpload" + numero_formulario + "' onclick='subir(" + numero_formulario + ");'>Enviar</button></fieldset></div>";
    $("#form_subir_archivo").append($(formulario));
}

function eliminar(id) {

    var idArchivo = lista_archivos[id].fileId;
    var nombreArchivo = lista_archivos[id].fileName;
    var respuesta = confirm("Seguro desea eliminar el archivo " + nombreArchivo);

    if (respuesta === true) {
        $.ajax({
            url: 'DeleteController/deleteFile',
            data: {fileId: idArchivo, fileName: nombreArchivo},
            type: 'POST',
            success: function (dataobject) {
                alert("Archivo eliminado. Se recargará la página");
                listarArchivos();
            },
            error: function (data) {
                console.log(data.responseText);
            }
        });

    }
}


//var fileInput = document.getElementsByName(id);
//$('input[name=' + id + ']').val();
//console.log($('input[name=' + id + ']').val());
//var file = $('input[name=' + id + ']').val();
//
//subCadena = file[0].split("/", 1);
//console.log(subCadena);
//
//
//
//var data = $('input[name=' + id + ']');
//data.name = $('input[name=' + id + ']').val().replace(" ", "_"); // Cambiar nombre