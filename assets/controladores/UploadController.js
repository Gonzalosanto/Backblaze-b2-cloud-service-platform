var numero_formulario = 0;
function subir(id) {

    $('#botonUpload' + id).attr("disabled", true);
    $.ajax({
        url: 'UploadController/uploadFile2',
        type: 'POST',
        success: function (dataobject) { //Funcion que retorna los datos procesados del script PHP .

            var fileInput = document.getElementById(id);
            var file = fileInput.files[0];

//            console.log(file.name.replace(" ", "_")); // Cambiar nombre

            var data = $('input[type=file]#' + id)[0].files[0];
            console.log(data.name);
            //data.name = filename.replace(/ /g, "_");
            var name = encodeURIComponent(file.name);// Cambiar nombre
            console.log(name);
//            var data = $('')[0].files[0]; 
//            var data = new FormData();
//            data.append("file", file);
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
                    "X-Bz-File-Name": name,
                    "Content-Type": file.type,
                    "Content-Lenght": file.size,
                    "X-Bz-Content-Sha1": "do_not_verify",
                    "X-Bz-Info-Author": 'unknown'
                },
                success: function (datos) { //Funcion que retorna los datos procesados del script PHP .
                    alert("Subido exitosamente. Recuerde recargar la página");
//                    $('#botonUpload' + numero_formulario).attr("disabled", false);
//                    location.reload();
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
    var formulario = "<br><div id='fileform'><fieldset><legend>Select file to upload:</legend><label for='userfile'>Archivo:</label><input type='file' name='" + numero_formulario + "' id='" + numero_formulario + "'><div id='progress" + numero_formulario + "' class='progress" + numero_formulario + "'> </div><button type='button' id='botonUpload" + numero_formulario + "' onclick='subir(" + numero_formulario + ");'>Enviar</button></fieldset></div>";
//    numero_formulario++;
//    $("#form_subir_archivo").html(formulario);
    $("#form_subir_archivo").append($(formulario));
//    $(this).appendTo("#form_subir_archivo");
}