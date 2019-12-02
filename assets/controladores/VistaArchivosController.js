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

/*function subir (){

var fileInput = document.getElementById('idArchivo');
var file = fileInput.files[0];
//var filename = encodeURIComponent(file.name); // Cambiar nombre
$('#botonUpload').attr("disabled", true);
$.ajax({
    url: 'subirArchivo',
    type:'POST',
    data:file,
    cache: false,
    processData: false,
    contentType: false,
    success: function(data){
        console.log("Archivo subido al server");
        alert(data);
    },
    error: function(data){
     console.log("Error al subir el archivo al server");   
     alert(data);
    }

});

}*/
var numero_formulario = 0;

function subir(id) {

    $('#botonUpload' + id).attr("disabled", true);
    var fileInput = document.getElementById('id'+id);
    var file = fileInput.files[0];
    var formdata = new FormData();
    formdata.append('file', file);
    

    $.ajax({
        url: 'UploadToServerController/subirArchivo',
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,

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
                    /*var tiempoActual = Date.now();
                    console.log('Tiempo actual: ' + tiempoActual);
                    console.log('Tiempo Transcurrido');
                    console.log(tiempoActual - start)/1000;
                    console.log('Tiempo Restante');
                    console.log((tiempoActual - start) / (percentComplete * 1000));
                    console.log('FORMATO HORA');
                    console.log(secondsToTime((tiempoActual - start) / (percentComplete * 1000)));*/
                    console.log('Porcentaje: ' + (percentComplete*100));
                    $('#progress' + id).css({
                        width: percentComplete * 100 + '%'
                    });
                }
            }, false);
            return xhr;
        },            
        success: function (dataobject) {                  
            alert("El archivo se subio exitosamente al servidor");
            listarArchivos();
            $('#progress' + id).css({"background-color": "green"});
        },        
        error: function (data) {
            alert("Hubo un error al subir el archivo " + file.name);
            $('#botonUpload' + id).attr("disabled", false);
            $('#progress' + id).removeClass('hide');
        }
    });
}
// https://stackoverflow.com/questions/15410265/file-upload-progress-bar-with-jquery



function copiar_formulario() {
    var phpscript = '<?php echo ini_get("session.upload_progress.name");?>';
    numero_formulario++;
    var formulario = "<br><div id='fileform'><fieldset><legend>Select file to upload:</legend><label for='userfile'>Archivo:</label><input type='hidden' value='upload_progress' name='"+phpscript+"' ><input type='file' name='" + numero_formulario + "' id='id" + numero_formulario + "' class='agregarFile' ><div id='progress" + numero_formulario + "' class='progress" + numero_formulario + "'> </div><button type='button' id='botonUpload" + numero_formulario + "' onclick='subir(" + numero_formulario + ");'>Enviar</button></fieldset></div>";
    $("#form_subir_archivo").append($(formulario));
}

/*function copiar_formulario() {
   // numero_formulario++;
    var formulario = "<br><form action='subirArchivo' method='POST' enctype='multipart/form-data'> <div id='fileform'><fieldset><legend>Select file to upload:</legend><label for='archivo'>Archivo:</label><input type='file' name='archivo' id='archivo' class='agregarFile' ><div id='progress' class='progress'> </div><button type='submit'>Enviar</button></fieldset></div></form>";
    $("#form_subir_archivo").append($(formulario));
}*/

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
