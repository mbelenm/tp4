<?php   

$_SERVER["DOCUMENT_ROOT"]=dirname(__DIR__);
 ini_set('display_errors', 1);


 function conectarDB()
 {

    $dsn = 'mysql:host=localhost;dbname=sgu; port=3306';
    $usuario= 'root';
    $contrasenia= '';
   
     $dbh = new PDO($dsn, $usuario, $contrasenia);
     $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
     return $dbh;


 }

//  var_dump(conectarDB());

 

