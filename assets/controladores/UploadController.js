function subir() {

    $('#botonUpload').attr("disabled", true);
    $.ajax({
        url: 'UploadController/uploadFile2',
        type: 'POST',
        success: function (dataobject) { //Funcion que retorna los datos procesados del script PHP .
            var fileInput = document.getElementById("userfile");
            var file = fileInput.files[0];
            var data = new FormData();
            data.append("file", file);
            $.ajax({
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            console.log(percentComplete);
                            $('#progress').css({
                                width: percentComplete * 100 + '%'
                            });
                            if (percentComplete === 1) {
                                $('#progress').addClass('hide');
                            }
                        }
                    }, false);
                    xhr.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            console.log(percentComplete);
                            $('#progress').css({
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
                    alert("Subido exitosamente");
                    $('#botonUpload').attr("disabled", false);
                    location.reload();
                },
                error: function (data) {
                    alert("Hubo un error al subir el archivo " + file.name);
                    $('#botonUpload').attr("disabled", false);
                    $('#progress').removeClass('hide');
                }
            });
        },
        complete: function (data) {
        },
        error: function (data) {
            console.log(data.responseText);
        }
    });


// https://stackoverflow.com/questions/15410265/file-upload-progress-bar-with-jquery

//    var bar = $('.bar');
//    var percent = $('.percent');
//    var status = $('#status');
//
//    $('form').ajaxForm({
//        beforeSend: function () {
//            status.empty();
//            var percentVal = '0%';
//            bar.width(percentVal);
//            percent.html(percentVal);
//        },
//        uploadProgress: function (event, position, total, percentComplete) {
//            var percentVal = percentComplete + '%';
//            bar.width(percentVal);
//            percent.html(percentVal);
//        },
//        complete: function (xhr) {
//            status.html(xhr.responseText);
//        }
//    });
}

var numero_formulario = 0;

function copiar_formulario() {

    var formulario = "<div id='fileform'><fieldset><legend>Select file to upload:</legend><label for='userfile'>Archivo:</label><input type='file' name='userfile" + numero_formulario + "' id='userfile" + numero_formulario + "'><div id='progress" + numero_formulario + "'></div><button type='button' id='botonUpload" + numero_formulario + "' onclick='subir();'>Enviar</button></fieldset></div>";
    numero_formulario++;
    $("#form_subir_archivo").html(formulario);
//    $(this).appendTo("#form_subir_archivo");
}