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
            lista_archivos = dataobject;
            $.each(dataobject, function (index, value) {
                var fileName = "<td>" + value.nombreArchivo + "</td>";
                var uploadTimestamp = "<td>" + value.peso + "</td>";
                var peso = "<td>" + value.fecha + "</td>";
                var botonDescargar = "<button type='button' id=" + index + " onclick='descargar(this.id);' download > Descargar </button>";
                var botonEliminar = "<button type='button' id=" + index + " onclick='eliminar(this.id);' > Eliminar </button>";
                $("#dataFiles").append("<tr id='" + index + "'>" + fileName + peso + uploadTimestamp + "<td id='botonera'> "
                        + botonDescargar + " " + botonEliminar + "</td></tr>");
            });
        },
        error: function (data) {
            console.log(data.responseText);
        }
    });
}

function descargar(id) {

    var nameFile = lista_archivos[id].nombreArchivo;
    $.ajax({
        url: 'DownloadController/authToken',
        type: 'post',
        success: function (dataobject) {
            var objeto = JSON.parse(dataobject);
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
    var start = Date.now();
    console.log(start);
    $('#botonUpload' + id).attr("disabled", true);
    $.ajax({
        url: 'UploadController/uploadFile2',
        type: 'POST',
        success: function (dataobject) { //Funcion que retorna los datos procesados del script PHP .

            var fileInput = document.getElementById('id' + id);

            if (fileInput.files && fileInput.files[0]) {

                var file = fileInput.files[0];

                file.name = encodeURIComponent(file.name.replace(" ", "_")); // Cambiar nombre
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
                                var tiempoActual = Date.now();
                                console.log('Tiempo actual: ' + tiempoActual);
                                console.log('Tiempo Transcurrido');
                                console.log(tiempoActual - start)/1000;
                                console.log('Tiempo Restante');
                                console.log((tiempoActual - start) / (percentComplete * 1000));
                                console.log('FORMATO HORA');
                                console.log(secondsToTime((tiempoActual - start) / (percentComplete * 1000)));
                                console.log('Porcentaje: ' + (percentComplete*100));
                                $('#progress' + id).css({
                                    width: percentComplete * 100 + '%'
                                });
                            }
                        }, false);
                        return xhr;
                    },

                    url: dataobject.uploadUrl,
                    type: 'POST',
                    data: file,
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
                        $('#progress' + id).css({"background-color": "green"});
                    },
                    error: function (data) {
                        alert("Hubo un error al subir el archivo " + file.name);
                        $('#botonUpload' + id).attr("disabled", false);
                        $('#progress' + id).removeClass('hide');
                    }
                });
            } else {
                alert("Debe seleccionar un archivo");
            }

        },
        error: function (data) {
            console.log(data.responseText);
        }
    });

// https://stackoverflow.com/questions/15410265/file-upload-progress-bar-with-jquery

}

function copiar_formulario() {
    numero_formulario++;
    var formulario = "<br><div id='fileform'><fieldset><legend>Select file to upload:</legend><label for='userfile'>Archivo:</label><input type='file' name='" + numero_formulario + "' id='id" + numero_formulario + "' class='agregarFile' ><div id='progress" + numero_formulario + "' class='progress" + numero_formulario + "'> </div><button type='button' id='botonUpload" + numero_formulario + "' onclick='subir(" + numero_formulario + ");'>Enviar</button></fieldset></div>";
    $("#form_subir_archivo").append($(formulario));
}

function eliminar(id) {

    var idArchivo = lista_archivos[id].idFile;
    var nameFile = lista_archivos[id].nombreArchivo;

    var respuesta = confirm("Seguro desea eliminar el archivo " + nameFile);

    if (respuesta === true) {
        $.ajax({
            url: 'DeleteController/deleteFile',
            data: {fileId: idArchivo, fileName: nameFile},
            type: 'POST',
            success: function (dataobject) {
                listarArchivos();
            },
            error: function (data) {
                console.log(data.responseText);
            }
        });

    }
}


function secondsToTime(s) {

    function addZ(n) {
        return (n < 10 ? '0' : '') + n;
    }

    var ms = s % 1000;
    s = (s - ms) / 1000;
    var secs = s % 60;
    s = (s - secs) / 60;
    var mins = s % 60;
    var hrs = (s - mins) / 60;

    return addZ(hrs) + ':' + addZ(mins) + ':' + addZ(secs) + '.' + addZ(ms);
}