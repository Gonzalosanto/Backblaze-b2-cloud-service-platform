var numero_formulario = 0;
function subir(id) {

    $('#botonUpload' + numero_formulario).attr("disabled", true);
    $.ajax({
        url: 'UploadController/uploadFile2',
        type: 'POST',
        success: function (dataobject) { //Funcion que retorna los datos procesados del script PHP .

////            var fileInput = $("#userfile" +   numero_formulario);
////            console.log("userfile" + numero_formulario, fileInput);
////            var file = $("#userfile" + numero_formulario).val();
//            var file = document.getElementById("#userfile" + numero_formulario);
//            console.log(file);
//            if (file) {
//                var reader = new FileReader();
//                reader.readAsText(file);
//                reader.onload = function (e) {
//                    alert(e.target.result);
//                };
//            }

            var fileInput = document.getElementById(numero_formulario);
            var file = fileInput.files[0];

            console.log(file);
            var data = new FormData();
            data.append("file", file);
            $.ajax({
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            console.log(percentComplete);
                            $('#progress' + numero_formulario).css({
                                width: percentComplete * 100 + '%'
                            });
                            if (percentComplete === 1) {
                                $('#progress' + numero_formulario).addClass('hide');
                            }
                        }
                    }, false);
                    xhr.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            console.log(percentComplete);
                            $('#progress' + numero_formulario).css({
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
                    $('#botonUpload' + numero_formulario).attr("disabled", false);
//                    location.reload();
                },
                error: function (data) {
                    alert("Hubo un error al subir el archivo " + file.name);
                    $('#botonUpload' + numero_formulario).attr("disabled", false);
                    $('#progress' + numero_formulario).removeClass('hide');
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

}



function copiar_formulario() {
    numero_formulario++;
    var formulario = "<div id='fileform'><fieldset><legend>Select file to upload:</legend><label for='userfile'>Archivo:</label><input type='file' name='" + numero_formulario + "' id='" + numero_formulario + "'><div id='progress" + numero_formulario + "'></div><button type='button' id='botonUpload" + numero_formulario + "' onclick='subir(" + numero_formulario + ");'>Enviar</button></fieldset></div>";
//    numero_formulario++;
//    $("#form_subir_archivo").html(formulario);
    $("#form_subir_archivo").append($(formulario));
//    $(this).appendTo("#form_subir_archivo");
}