<?php
class Paginas
{
    static public function enlacesPaginas($enlaces)
    {
        try {
            $link = new PDO('mysql:host=localhost; dbname=cuestionario; charset=UTF8', 'root','',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        
        // Verificar si el enlace está en la tabla de enlaces
        $stmt = $link->prepare('SELECT * FROM enlaces WHERE referencia = :enlace');
        $stmt->bindParam(':enlace', $enlaces);
        $stmt->execute();
        $enlace = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Si el enlace no está en la base de datos pero sí existe un archivo con ese nombre, agregarlo a la base de datos
        if (!$enlace && file_exists("vistas/modulos/$enlaces.php")) {
            $stmt = $link->prepare('INSERT INTO enlaces (referencia, enlace) VALUES (:enlace, :ruta)');
            $ruta = "vistas/modulos/$enlaces.php";  
            $stmt->bindParam(':enlace', $enlaces);
            $stmt->bindParam(':ruta', $ruta);
            $stmt->execute();
        } elseif (!$enlace && file_exists("vista/modulos/ejemplo/$enlaces.php")) {
            $stmt = $link->prepare('INSERT INTO enlaces (referencia, enlace) VALUES (:enlace, :ruta)');
            $ruta = "vistas/modulos/ejemplo/$enlaces.php";  
            $stmt->bindParam(':enlace', $enlaces);
            $stmt->bindParam(':ruta', $ruta);
            $stmt->execute();
        } else { // Si el enlace no se encuentra ni en la base de datos ni en el directorio de vistas, redirigir a principal.php
            $ruta = 'vistas/modulos/principal.php';
        }
        
        // Redirigir a la página correspondiente
        if ($enlace && $enlace['referencia'] == 'principal' ) {
            $ruta = 'vistas/modulos/principal.php';
        } elseif ($enlace) {
            $ruta = $enlace['enlace'];
        }
        return $ruta;
    }
}
?>
