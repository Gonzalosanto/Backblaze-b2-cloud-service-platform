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
               
                <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
                crossorigin="anonymous"></script>

                <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
               <!-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
                <link href="<?php echo base_url(); ?>assets/css/bootstrap.css" type="text/css" rel="stylesheet"/>
                <link href="<?php echo base_url(); ?>assets/css/modal.css" type="text/css" rel="stylesheet"/>
                <link href="<?php echo base_url();?>assets/css/progress-bar.css" type="text/css" rel="stylesheet"/>
                
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
  
  
   $i=0;
   while ($i<$countFileName){

    $nomArchivo = $objetoObj['files'][$i]['fileName'];    
    $filesize = $objetoObj['files'][$i]['contentLength'];
    $timeStamp = $objetoObj['files'][$i]['uploadTimestamp'];
    $fechaDeSubida = date_create_from_format ( "U" , $timeStamp/1000);
    $fecha = date_format($fechaDeSubida, 'Y-m-d');
    

     echo '<tr>
     <td><input type="checkbox" class="checkthis" /></td>
     <td>'.$nomArchivo.'</td>
     <td>'.formatSizeUnits($filesize).'</td>
     <td>'.$fecha.'</td>
     
     
     
     
     
     <td><form action="index.php/downloadFile/'.$nomArchivo.'" method="GET">
      <input type="hidden" name="filename" value="'.$nomArchivo.'">
      <input type="submit" value="Descargar"></form></p></td>

     <td><p title="Delete"><button class="btn btn-danger btn-xs" ><span class="glyphicon glyphicon-trash"></span></button></p></td>
     </tr>';
    
    
$i++;
}
/*<button  
     class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-download"></span></button>*/
    
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

  <!-- Modal content for upload-->
  <div class="modal-content" >
    <span class="close">&times;</span>
    <form  id="fileform" class="fileform" method="post" enctype="multipart/form-data">
      Select file to upload:
      <input type="file" name="userfile" id="userfile" style="width:100%;" multiple><br>
      <input type="submit" name="botonUpload" id="botonUpload" value="Upload">
      
    </form> 
    <div class='progress' id="progress_div">
      <div class='bar' id='bar1'></div>
      <div class='percent' id='percent1'>0%</div>
    </div>
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
      btn.onclick = function() {
        modal.style.display = "block";
      }

      // When the user clicks on <span> (x), close the modal
      span.onclick = function() {
        modal.style.display = "none";
      }

      // When the user clicks anywhere outside of the modal, close it
      window.onclick = function(event) {
        if (event.target == modal) {
          modal.style.display = "none";
        }
      }
</script>   

<script >


$(document).ready(function(){ 



  var filenameR;
  var filename;
  var filetype;
  var filesize;
  var arrayJSON;  

  function CB( callback){
    
    return (callback(xmlhttp.responseText))
  }

 

  
//llamada al upload.php



        $("#fileform").submit(function(e){
            e.preventDefault();
                      
          //var index=0;
          var file=document.getElementById("userfile");                          
                                                     
          /*for(index=0; index<files.length; index++){     

            filename = $('input[type=file]')[0].files[index].name ;
            filenameR= filename.replace(/ /g, "_");
            filetype = $('input[type=file]')[0].files[index].type ;
            filesize = $('input[type=file]')[0].files[index].size ;               
                //POST del archivo
                
             
            var file = document.getElementById("userfile");
            //var file = fileInput.files[index];
            
            }*/

            sendAllFiles(file);

            
              

  });
             


});


function sendAllFiles(file) { //Sube todos los archivos encadenados con iteracion manual
    var index = 0;

    function next() {
        var data;
        if (index < file.length) {
            

            data = new FormData();
            data.append("file", file);
              
            console.log(arrayJSON);
            console.log(filenameR);
            console.log(filetype);
               
            console.log(filesize);
            for (let [key, value] of data.entries()) {
                console.log(key, ':', value);
              } 
            ++index;
            // send this file and when done, do the next iteration
            permisosURL();
            CB(function(x){
              var JSONObject = JSON.parse(x);                                  
              arrayJSON = Object.values(JSONObject);                                
              console.log(arrayJSON[2]);
                                
            });
            subirArchivo(data).then(next);
        } else {
            
        }
    }
    // start the first iteration
    next();
}

//peticion para pedir las credenciales(URL y authToken) para subir archivos
function permisosURL(){
  var xmlhttp = new XMLHttpRequest();  
  xmlhttp.onreadystatechange=function()
  {//Datos del input file
    //var arrayJSON;
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
    { 
    // Parse the JSON data structure contained in xmlhttp.responseText using the JSON.parse function.
                    /*CB(function(x){
                                    JSONObject = JSON.parse(x);
                                    arrayJSON = Object.values(JSONObject);                                  
                                  });*/
    }

  }

  
  xmlhttp.open("post","<?php echo base_url("index.php/uploadFile")?>",true);
  xmlhttp.send(); 
} 
//peticion AJAX para subir el archivo
function subirArchivo(data){
                $.ajax({                            
                                             
                     url: arrayJSON[2],                      
                     type: 'POST',
                     
                     headers: {   "Authorization": arrayJSON[0], 
                                  "X-Bz-File-Name": filenameR,
                                  "Content-Type": filetype, 
                                  "Content-Lenght": filesize,                                 
                                  "X-Bz-Content-Sha1": "do_not_verify",
                                  "X-Bz-Info-Author": 'unknown'                                                                
                                  },
                                  /*xhrFields: {
                                  withCredentials: true
                                  },     */          
                      data: data,
                      cache:false,
                      processData: false,
                      contentType: false,
                      xhr: function() {
                                          var xhr = new window.XMLHttpRequest();
                                          xhr.upload.addEventListener("progress", function(evt) {
                                              if (evt.lengthComputable) {
                                                  var percentComplete = evt.loaded / evt.total;
                                                  var porcentaje = percentComplete.toFixed(2) * 100;
                                                  //Do something with upload progress here
                                                  document.getElementById("percent1").innerHTML=porcentaje+"%";
                                                  document.getElementById("bar1").style.width=porcentaje+"%";
                                                 // percent.html(percentVal);

                                              }
                                        }, false);                                
                                      
                                        return xhr;
                                      },
                      complete: function(data){
                        console.log('succes: '+data.responseText);
                        alert("Refresh Page");
                    },
                     error:function(data){
                      console.log(data.responseText);
                    }
                    });
              }
</script>






    