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
        <!------ Include the above in your HEAD tag ---------->
        <!--<link href="assets/css/progress_bar.css" type="text/css" rel="stylesheet" />-->

    </head>

    <body>

        <h2>File list</h2>

        <table id="mytable" class="table table-bordred table-striped">
            <thead>
            <th>File Name</th>
            <th>File size</th>
            <th>Upload date</th>
            <th>Operaciones</th>                      
        </thead>
        <tbody>

            <?php
            $i = 0;
            while ($i < $countFileName) {

                $nombreArchivo = $objetoObj['files'][$i]['fileName'];
                $idArchivo = $objetoObj['files'][$i]['fileId'];
                $filesize = $objetoObj['files'][$i]['contentLength'];
                $timeStamp = $objetoObj['files'][$i]['uploadTimestamp'];
                $fechaDeSubida = date_create_from_format("U", $timeStamp / 1000);
                $fecha = date_format($fechaDeSubida, 'Y-m-d');
                ?>
                <tr>
                    <td><?= $objetoObj['files'][$i]['fileName'] ?></td>
                    <td><?= formatSizeUnits($filesize) ?></td>
                    <td><?= $fecha ?></td>
                    <td>
                        <button type="button" id="descargar<?= $i ?>"  onclick="descarga(<?= $i ?>, '<?= $nombreArchivo ?>');" download >Descargar </button>
                        <!--<a href="http://localhost/PlataformaWEB/DownloadController/downloadFile?filename=<?= $nombreArchivo ?>" download >Descargar Archivo</a>-->
                        <!--<button type="button" href="http://localhost/PlataformaWEB/DownloadController/downloadFile?filename=7.jpeg" download="7.jpeg" >Descargar </button>-->
                        <button type="button" id="eliminar<?= $i ?>" onclick='eliminar("<?= $i ?>", "<?= $idArchivo ?>", "<?= $nombreArchivo ?>")'>Eliminar </button>
                    </td>
                </tr>
                <?php
                $i++;
            }
            ?>
        </tbody>
    </table>

    <br>

    <button type='button' onclick='copiar_formulario();'>+</button>
    <div id='form_subir_archivo'>
    </div>

                <form  id="fileform" class="fileform" method="post" action="uploadLargeFile" enctype="multipart/form-data">
                    Select a large file to upload:
                    <input type="file" name="archivo" id="archivo" style="width:100%;"><br>
                    <input type="submit" name="botonUpload" id="botonUpload" value="Upload">

                </form> 
   <!-- <button type='button' onclick='copiar_formulario_subida_partes();'>Subir archivo por partes (1gb +)</button>
    <div id='form_subir_archivo_partes'>
    </div>-->
    

    <script src='<?php echo base_url("assets/js/vieja/jquery-3.2.1.min.js"); ?>'></script>
    <script src='<?php echo base_url("assets/controladores/UploadController.js"); ?>'></script>
    <script src='<?php echo base_url("assets/controladores/DownloadController.js"); ?>'></script>
    <script src='<?php echo base_url("assets/controladores/DeleteController.js"); ?>'></script>
    <script src='<?php echo base_url("assets/controladores/UploadByParts.js"); ?>'></script>

</body>
