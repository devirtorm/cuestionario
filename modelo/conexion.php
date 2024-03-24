<?php
class Conexion
{
    static public function conectar(){
        try {
            $link = new PDO('mysql:host=localhost;port=3306;dbname=cuestionario', 'root', '');
            
            $link->exec("SET NAMES utf8");
           
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           /*  echo "Conexión establecida correctamente"; */
       
            return $link;
        } catch(PDOException $e) {
          
            echo "Error: " . $e->getMessage();
           
            return null;
        }
    }
}

/* 
$conexion = Conexion::conectar();
 */


?>
