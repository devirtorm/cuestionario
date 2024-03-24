<?php
//incluimos el archivo conexion
include("conexion.php");
class Datos extends Conexion
{

    //método que registra el evento
    static public function guardarevento($datosControlador, $tabla)
    {

        $todo_dia = "true";

        $consulta = Conexion::conectar()->prepare("
        	INSERT INTO $tabla (titulo_evento,
        	 					hora_inicio,
        	 					hora_fin,
        	 					fecha,
        	 					todo_dia, 
        	 					nota, 
        	 					notificacion, 
        	 					fk_prioridad, 
        	 					fk_categoria, 
        	 					fk_usuario) 
        	VALUES (:titulo_evento, 
        			:hora_inicio,  
        			:hora_fin, 
        			:fecha, 
        			:todo_dia, 
        			:nota,
        			:notificacion,
        			:fk_prioridad,
        			:fk_categoria,
        			:fk_usuario
        			)");
        $consulta->bindParam(":titulo_evento",       $datosControlador["0"], PDO::PARAM_STR);
        $consulta->bindParam(":hora_inicio",        $datosControlador["1"], PDO::PARAM_STR);
        $consulta->bindParam(":hora_fin",           $datosControlador["2"], PDO::PARAM_STR);
        $consulta->bindParam(":fecha",               $datosControlador["7"], PDO::PARAM_STR);
        $consulta->bindParam(":todo_dia",             $todo_dia,                 PDO::PARAM_STR);
        $consulta->bindParam(":nota",                 $datosControlador["4"], PDO::PARAM_STR);
        $consulta->bindParam(":notificacion",       $datosControlador["3"], PDO::PARAM_STR);
        $consulta->bindParam(":fk_prioridad",       $datosControlador["5"], PDO::PARAM_STR);
        $consulta->bindParam(":fk_categoria",       $datosControlador["6"], PDO::PARAM_STR);
        $consulta->bindParam(":fk_usuario",         $_SESSION['pk_usuario'], PDO::PARAM_INT);

        if ($consulta->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
    }

    static public function verificar($datoModelo, $tabla)
    {
        $consulta = "SELECT * FROM usuarios WHERE email = :email";
        $resultado = Conexion::conectar()->prepare($consulta);
        $resultado->bindParam(":email", $datoModelo['0'], PDO::PARAM_STR);
        $resultado->execute();

        $cantidad_resultado  = $resultado->rowCount();

        echo $cantidad_resultado;

        $data = $resultado->fetch(PDO::FETCH_ASSOC);

        if (!isset($_SESSION)) {
            session_start();
        }
        // EXISTE USUARIO ?

        if ($cantidad_resultado == 1) {
            if ($datoModelo['1'] == $data['clave']) {
                $_SESSION['id_usuario'] = $data['id_usuario'];
                $_SESSION['email'] = $data['email'];
                $_SESSION['apellidos'] = $data['apellidos'];
                $_SESSION['nombres'] = $data['nombres'];
                $_SESSION['rol'] = $data['id_tipo_usuario'];
                return 'ok';
            } else {
                return 'error';
            }
        } else {
            return 'error';
        }
    }



    //Metodo que obtiene la prioridad
    static public function obtenerprioridad($tabla)
    {
        $peticion = Conexion::conectar()->prepare("SELECT pk_prioridad, tipo_prioridad FROM $tabla");
        $peticion->execute();
        return $peticion;
        $peticion = null;
    }

    //Metodo que obtiene la prioridad
    static public function obtenercategoria($tabla)
    {
        $peticion = Conexion::conectar()->prepare("SELECT pk_categoria, nombre_categoria FROM $tabla");
        $peticion->execute();
        return $peticion;
        $peticion = null;
    }

    //metodo para ingresar datos de registro
    static public function guardarUsuarioRegistro($datosControlador)
    {
        $conexion = Conexion::conectar();

        // Inserción en la tabla 'persona'
        $consultaPersona = $conexion->prepare("INSERT INTO persona (nombre, primer_apellido, segundo_apellido, imagen) 
                                               VALUES (:nombre, :primer_apellido, :segundo_apellido, :imagen)");
        $consultaPersona->bindParam(":nombre", $datosControlador["0"], PDO::PARAM_STR);
        $consultaPersona->bindParam(":primer_apellido", $datosControlador["1"], PDO::PARAM_STR);
        $consultaPersona->bindParam(":segundo_apellido", $datosControlador["2"], PDO::PARAM_STR);
        $consultaPersona->bindParam(":imagen", $datosControlador["3"], PDO::PARAM_STR);

        if ($consultaPersona->execute()) {
            // Obtener el ID de la persona insertada
            $idPersona = $conexion->lastInsertId();

            // Inserción en la tabla 'usuario'
            $consultaUsuario = $conexion->prepare("INSERT INTO usuario (correo, clave, fk_persona) 
                                                   VALUES (:correo, :clave, :fk_persona)");
            $consultaUsuario->bindParam(":correo", $datosControlador["4"], PDO::PARAM_STR);
            $consultaUsuario->bindParam(":clave", $datosControlador["5"], PDO::PARAM_STR);
            $consultaUsuario->bindParam(":fk_persona", $idPersona, PDO::PARAM_INT);

            if ($consultaUsuario->execute()) {
                return 'ok';
            } else {
                return 'error al insertar en la tabla usuario';
            }
        } else {
            return 'error al insertar en la tabla persona';
        }
    }

    static public function loginmodelo($datosModelo)
    {
        try {
            $query = "SELECT u.pk_usuario, p.pk_persona FROM usuario u INNER JOIN persona p ON u.fk_persona = p.pk_persona WHERE u.correo = :correo AND u.clave = :clave";
            $stmt = Conexion::conectar()->prepare($query);
            $stmt->bindParam(":correo", $datosModelo["correo"], PDO::PARAM_STR);
            $stmt->bindParam(":clave", $datosModelo["clave"], PDO::PARAM_STR);
            $stmt->execute();


            if ($stmt->rowCount() == 1) {

                $datosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);
                // guardar sesion
                $_SESSION['pk_usuario'] = $datosUsuario['pk_usuario'];
                $_SESSION['pk_persona'] = $datosUsuario['pk_persona'];
                return "ok";
            } else {
                return "nt";
            }
        } catch (PDOException $e) {
            // Error al ejecutar la consulta
            return "error: " . $e->getMessage();
        }
    }

    static public function CategoriaModelo($datosModelo)
    {
        try {
            $query = "INSERT INTO categoria (nombre_categoria, color_categoria, fk_usuario) 
                  VALUES (:nombre, :color, :fk_usuario)";
            $stmt = Conexion::conectar()->prepare($query);
            $stmt->bindParam(":nombre", $datosModelo["nombre"], PDO::PARAM_STR);
            $stmt->bindParam(":color", $datosModelo["color"], PDO::PARAM_STR);
            $stmt->bindParam(":fk_usuario", $_SESSION['pk_usuario'], PDO::PARAM_INT);
            $stmt->execute();

            return "ok";
        } catch (PDOException $e) {
            return "error: " . $e->getMessage();
        }
    }

    static public function obtener_encuesta_modelo($tabla)
    {
        /*  SELECT
        p.id_pregunta,
        p.texto_pregunta AS pregunta,
        p.tipo_pregunta,
        GROUP_CONCAT(o.texto_opcion SEPARATOR ', ') AS opciones
    FROM
        preguntas p
    LEFT JOIN
        opciones o ON p.id_pregunta = o.id_pregunta
    GROUP BY
        p.id_pregunta; */
        $query = "select * from encuestas";
        $stmt = Conexion::conectar()->prepare($query);
        //$stmt->bindParam(":usuario", $usuario, PDO::PARAM_INT);
        $stmt->execute();

        // Devolvemos el resultado de la consulta
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function obtener_pregunta_modelo($idEncuesta)
    {
        // Asegúrate de sanitizar el input si es necesario
        $query = "SELECT
                p.id_pregunta,
                p.texto_pregunta AS pregunta,
                p.tipo_pregunta,
                GROUP_CONCAT(o.texto_opcion SEPARATOR ', ') AS opciones
            FROM
                preguntas p
            LEFT JOIN
                opciones o ON p.id_pregunta = o.id_pregunta
            WHERE
                p.id_encuesta = :idEncuesta
            GROUP BY
                p.id_pregunta;";

        // Preparamos la consulta
        $stmt = Conexion::conectar()->prepare($query);

        // Vinculamos el parámetro idEncuesta
        $stmt->bindParam(":idEncuesta", $idEncuesta, PDO::PARAM_INT);

        // Ejecutamos la consulta
        $stmt->execute();

        // Devolvemos el resultado de la consulta
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    static public function guardar_encuesta_modelo($datosControlador, $tabla)
    {
        $conexion = Conexion::conectar();

        // Obtener la fecha y hora actual en Mazatlán y guardarla en una variable
        $fecha_hora = new DateTime();
        $fecha_hora->setTimezone(new DateTimeZone('America/Mazatlan'));

        // Formatear la fecha y hora en el formato adecuado para MySQL TIMESTAMP
        $fecha_creacion = $fecha_hora->format('Y-m-d H:i:s');

        // Preparar la consulta para insertar la encuesta
        $consulta_encuesta = $conexion->prepare("
            INSERT INTO $tabla (titulo, descripcion, fecha_creacion) 
            VALUES (:titulo, :descripcion, :fecha_creacion)
        ");
        $consulta_encuesta->bindParam(":titulo", $datosControlador[0], PDO::PARAM_STR);
        $consulta_encuesta->bindParam(":descripcion", $datosControlador[1], PDO::PARAM_STR);
        $consulta_encuesta->bindParam(":fecha_creacion", $fecha_creacion, PDO::PARAM_STR);

        // Ejecutar la consulta para insertar la encuesta
        if ($consulta_encuesta->execute()) {
            $id_encuesta = $conexion->lastInsertId();

            // Preparar la consulta para insertar las preguntas
            $consulta_pregunta = $conexion->prepare("
                INSERT INTO preguntas (id_encuesta, texto_pregunta, tipo_pregunta) 
                VALUES (:id_encuesta, :texto_pregunta, :tipo_pregunta)
            ");
            $consulta_pregunta->bindParam(":id_encuesta", $id_encuesta, PDO::PARAM_INT);
            $consulta_pregunta->bindParam(":texto_pregunta", $texto_pregunta, PDO::PARAM_STR);
            $consulta_pregunta->bindParam(":tipo_pregunta", $tipo_pregunta, PDO::PARAM_STR);

            // Recorrer los datos de las preguntas y ejecutar la consulta para cada pregunta
            foreach ($datosControlador[2] as $index => $texto_pregunta) {
                $tipo_pregunta = $datosControlador[3][$index];

                if ($consulta_pregunta->execute()) {
                    $id_pregunta = $conexion->lastInsertId();

                    foreach ($datosControlador[4] as $inciso) { // Cambio aquí
                        $consulta_inciso = $conexion->prepare("INSERT INTO opciones (id_pregunta, texto_opcion) VALUES (:id_pregunta, :texto_opcion)");
                        $consulta_inciso->bindParam(":id_pregunta", $id_pregunta, PDO::PARAM_INT);
                        $consulta_inciso->bindParam(":texto_opcion", $inciso, PDO::PARAM_STR);

                        $consulta_inciso->execute();
                    }
                }
            }



            return 'ok';
        } else {
            return 'error';
        }
    }
}
