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
        <tbody id="dataFiles">
            
        </tbody>
    </table>

    <br>

    <button type='button' onclick='copiar_formulario();'>+</button>
    <div id='form_subir_archivo'>

    </div>

    <script src='<?php echo base_url("assets/js/vieja/jquery-3.2.1.min.js"); ?>'></script>
    <script src='<?php echo base_url("assets/controladores/VistaArchivosController.js"); ?>'></script>

</body>
