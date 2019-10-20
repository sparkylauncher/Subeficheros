<html><head><title>subeficheros.php</title> </head>
<body>
<?php
const DIR = "/home/alummo2019-20/Escritorio/imgusers";
// muestra formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST'){  
    $codigosErrorSubida= [
        0 => 'Subida correcta',
        1 => 'El tamaño del archivo excede el admitido por el servidor',  // directiva upload_max_filesize en php.ini
        2 => 'El tamaño del archivo excede el admitido por el cliente',  // directiva MAX_FILE_SIZE en el formulario HTML
        3 => 'El archivo no se pudo subir completamente',
        4 => 'No se seleccionó ningún archivo para ser subido',
        6 => 'No existe un directorio temporal donde subir el archivo',
        7 => 'No se pudo guardar el archivo en disco',  // permisos
        8 => 'Una extensión PHP evito la subida del archivo'  // extensión PHP
    ]; 
    
    //comprobar si ha subido ficheros
    if($_FILES['fichero1']['error'] == 4 && $_FILES['fichero2']['error'] == 4){
        echo "ERROR: Seleccione ficheros para subir<br/>";
        echo "<a href='subeficheros.php'>Volver a la página de subida</a>";
        return;
    }
    
    //comprobar que no ha habido error al subir
    if($_FILES['fichero1']['error'] == UPLOAD_ERR_OK){   
        $nombreFichero1   =   $_FILES['fichero1']['name'];
        $tipoFichero1     =   $_FILES['fichero1']['type'];
        $tamanioFichero1  =   $_FILES['fichero1']['size'];
        $temporalFichero1 =   $_FILES['fichero1']['tmp_name'];
        $errorFichero1    =   $_FILES['fichero1']['error'];
    }else{
        if($_FILES['fichero1']['error'] != 4)
            echo "ERROR: ".$codigosErrorSubida[$_FILES['fichero1']['error']]."<br/>";
    }
    
    //comprobar que no ha habido error al subir
    if($_FILES['fichero2']['error'] == UPLOAD_ERR_OK){
        $nombreFichero2   =   $_FILES['fichero2']['name'];
        $tipoFichero2     =   $_FILES['fichero2']['type'];
        $tamanioFichero2  =   $_FILES['fichero2']['size'];
        $temporalFichero2 =   $_FILES['fichero2']['tmp_name'];
        $errorFichero2    =   $_FILES['fichero2']['error'];
    }else{
        if($_FILES['fichero2']['error'] != 4)
            echo "ERROR: ".$codigosErrorSubida[$_FILES['fichero2']['error']]."<br/>";
    }
    
    if(!isset($tamanioFichero1)){
        $tamanioFichero1 = 0;
    }
    if(!isset($tamanioFichero2)){
        $tamanioFichero1 = 0;
    }
    
    //comprobar que el tamaño sumado no supere los 300kb
    if($tamanioFichero1 + $tamanioFichero2 > 307200){
        echo "ERROR: la suma de los tamaños no puede superar los 300kb<br/>";
        echo "<a href='subeficheros.php'>Volver a la página de subida</a>";
        return;
    }
    
    //intentar subir los ficheros
    if($_FILES['fichero1']['error'] == UPLOAD_ERR_OK){
        if($tipoFichero1 == 'image/jpeg' || $tipoFichero1 == 'image/png'){
            $mensaje1 .= 'Intentando subir el archivo: ' . ' <br />';
            $mensaje1 .= "- Nombre: $nombreFichero1" . ' <br />';
            $mensaje1 .= '- Tamaño: ' . ($tamanioFichero1 / 1024) . ' KB <br />';
            $mensaje1 .= "- Tipo: $tipoFichero1" . ' <br />' ;         
            $mensaje1 .= "- Nombre archivo temporal: $temporalFichero1" . ' <br />';
            $mensaje1 .= "- Código de estado: $errorFichero1" . ' <br />';
            echo $mensaje1;
            //comprobacion de permisos de escritura en server
            if ( is_dir(DIR) && is_writable (DIR)) {
                $directorio1 =   DIR .'/'. $nombreFichero1;
                //si el fichero ya existe en directorio devolvemos error
                if(file_exists($directorio1)){
                    echo "<br/>ERROR: el fichero ya existe en el servidor<br/>";
                }
                //movemos el fichero de la carpeta temporal a la designada por nostros
                if (!move_uploaded_file($temporalFichero1,  DIR .'/'. $nombreFichero1)) {
                    echo 'ERROR: Archivo no guardado correctamente <br />';
                }
            }else {
                echo 'ERROR: No es un directorio correcto o no se tiene permiso de escritura <br />';
                echo "<a href='subeficheros.php'>Volver a la página de subida</a>";
            }
        }else{
            echo "<br/> error en el tipo del fichero 1";
        }
    }
    //intentar subir los ficheros
    if($_FILES['fichero2']['error'] == UPLOAD_ERR_OK){
        if($tipoFichero2 == 'image/jpeg' || $tipoFichero2 == 'image/png'){
            $mensaje2 .= 'Intentando subir el archivo: ' . ' <br />';
            $mensaje2 .= "- Nombre: $nombreFichero2" . ' <br />';
            $mensaje2 .= '- Tamaño: ' . ($tamanioFichero2 / 1024) . ' KB <br />';
            $mensaje2 .= "- Tipo: $tipoFichero2" . ' <br />' ;
            
            $mensaje2.= "- Nombre archivo temporal: $temporalFichero2" . ' <br />';
            $mensaje2 .= "- Código de estado: $errorFichero2" . ' <br />';
            echo $mensaje2;
            //comprobacion de permisos de escritura en server
            if ( is_dir(DIR) && is_writable (DIR)) {
                $directorio2 =   DIR .'/'. $nombreFichero2;
                //si el fichero ya existe en directorio devolvemos error
                if(file_exists($directorio2)){
                    echo "<br/>ERROR: el fichero ya existe en el servidor<br/>";
                }
                //movemos el fichero de la carpeta temporal a la designada por nostros
                if (!move_uploaded_file($temporalFichero2,$directorio2)) {
                    echo 'ERROR: Archivo no guardado correctamente <br />';
                }
            }else {
                echo 'ERROR: No es un directorio correcto o no se tiene permiso de escritura <br />';
            }
        }else{
            echo "<br/> error en el tipo del fichero 2";
        }
    }
    echo "<a href='subeficheros.php'>Volver a la página de subida</a>";
    
}

if ( $_SERVER['REQUEST_METHOD']=='GET') {
    echo "<form action='subeficheros.php' method='POST' ENCTYPE='multipart/form-data'>";
    echo "<input type='hidden' name='MAX_FILE_SIZE' value='204800' accept='.jpg,.png'>";
    echo "Fichero 1:  <input type='file' name='fichero1'>";
    echo "<input type='hidden' name='MAX_FILE_SIZE' value='204800' accept='.jpg,.png'>";
    echo "<br/>Fichero 2:  <input type='file' name='fichero2'>";
    echo "<br/><input type='submit' value='Enviar Formulario' >";
}
?>
</body>