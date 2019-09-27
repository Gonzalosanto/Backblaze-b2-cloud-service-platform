<?php
//header('Access-Control-Allow-Origin: *');
include('application/dataAccessObjects/ListFiles.php');
?>
<html>

    <head>  
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Panel</title>
        <!--<link href="assets/js/jquery-3.4.1.min"/>-->

        <script src='<?php echo base_url("assets/js/vieja/jquery-3.2.1.min.js"); ?>'></script>

        <link href='<?php echo base_url("assets/css/vieja/bootstrap.min.css"); ?> 'type="text/css" rel="stylesheet" />
        <script src='<?php echo base_url("assets/js/vieja/bootstrap.min.js"); ?>'></script>
        <link href='<?php echo base_url("assets/css/vieja/modal.css"); ?>' type="text/css" rel="stylesheet" />

        <script src='<?php echo base_url("assets/js/vieja/sha1.js"); ?>'></script>
        <script src='<?php echo base_url("assets/js/vieja/sha1-min.js"); ?>'></script>
        <script src='<?php echo base_url("assets/js/vieja/lib-typedarrays-min.js"); ?>'></script>
        <script src='<?php echo base_url("assets/js/vieja/aes.js"); ?>'></script>
        <!------ Include the above in your HEAD tag ---------->

    </head>

    <body>

        <div class="container-fluid">
            <ul class="nav nav-pill nav-fill justify-content-center bg-light">
                <li class="nav-item">
                    <button class="btn" id="home">Home</button>
                </li>
                <li class="nav-item">        
                    <button class="btn" id="myUplBtn">Upload</button>
                </li>
            </ul>
        </div>

        <div class="container-fluid">

            <div class="row">

                <div class="col-md-12 text-center">
                    <h2>File list</h2>
                    <div class="table-responsive">

                        <table id="mytable" class="table table-bordred table-striped">
                            <thead>
                            <th><input type="checkbox" id="checkall" /></th>
                            <th>File Name</th>
                            <th>File size</th>
                            <th>Upload date</th>
                            <th>Download</th>                      
                            <th>Delete</th>
                            </thead>
                            <tbody>

                                <?php
                                $i = 0;
                                while ($i < $countFileName) {

                                    $nomArchivo = $objetoObj['files'][$i]['fileName'];
                                    $filesize = $objetoObj['files'][$i]['contentLength'];
                                    $timeStamp = $objetoObj['files'][$i]['uploadTimestamp'];
                                    $fechaDeSubida = date_create_from_format("U", $timeStamp / 1000);
                                    $fecha = date_format($fechaDeSubida, 'Y-m-d');

                                    echo '<tr>
            <td><input type="checkbox" class="checkthis" /></td>
            <td>' . $nomArchivo . '</td>
            <td>' . formatSizeUnits($filesize) . '</td>
            <td>' . $fecha . '</td>
     
     
     
     
     
            <td><form action="index.php/downloadFile/' . $nomArchivo . '" method="GET">
             <input type="hidden" name="filename" value="' . $nomArchivo . '">
             <input type="submit" value="Descargar"></form></p></td>

            <td><p title="Delete"><button class="btn btn-danger btn-xs" ><span class="glyphicon glyphicon-trash"></span></button></p></td>
            </tr>';


                                    $i++;
                                }
                                /* <button  
                                  class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-download"></span></button> */
                                ?>

                            </tbody>

                        </table>

                        <div class="clearfix"></div>
                        <ul class="pagination pull-right">
                            <li class="disabled"><a href="#"><span class="glyphicon glyphicon-chevron-left"></span></a></li>
                            <li class="active"><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">5</a></li>
                            <li><a href="#"><span class="glyphicon glyphicon-chevron-right"></span></a></li>
                        </ul>

                    </div>

                </div>
            </div>
        </div>


        <div id="MyModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <span class="close">&times;</span>
                <form  id="fileform"  method="post" enctype="multipart/form-data" action="">
                    Select file to upload:
                    <input type="file" name="userfile" id="userfile" ><br>
                    <button type="button" class="btn btn-primary" name="botonUpload" id="botonUpload" onclick="subir();" >Enviar</button>
                </form>
            </div>

        </div>
    </body>
    <script>
        // Get the modal
        var modal = document.getElementById("MyModal");

        // Get the button that opens the modal
        var btn = document.getElementById("myUplBtn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on the button, open the modal
        btn.onclick = function () {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };

        function subir() {

            $.ajax({
                url: '<?php echo base_url("index.php/uploadFile"); ?>',
                type: 'POST',
                success: function (dataobject) { //Funcion que retorna los datos procesados del script PHP .
                    var fileInput = document.getElementById("userfile");
                    var file = fileInput.files[0];
                    var data = new FormData();
                    data.append("file", file);
                    $.ajax({
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
                            location.reload();
                        },
                        error: function (data) {
                            alert("Hubo un error al subir");
                        }
                    });
                },
                complete: function (data) {
                },
                error: function (data) {
                    console.log(data.responseText);
                }
            });

        }

    </script>