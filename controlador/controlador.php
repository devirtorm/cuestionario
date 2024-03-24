<?php
class Controlador
{
  //metodo que carga la plantilla
  static public function pagina()
  {
    include("vistas/plantilla.php");
  }

  // Método que gestiona los enlaces
  static public function enlacespagina()
  {

    if (isset($_GET['accion'])) {
      $enlaces = $_GET['accion'];
    } else {
      $enlaces = "principal";
    }
    $respuesta = paginas::enlacesPaginas($enlaces);
    include $respuesta;
  }

  static public function sesion()
  {
    if (isset($_POST['botonson'])) {
      $tabla = "usuario";
      $datosControlador = array(
        "0" => $_POST['correo'],
        "1" => $_POST['contrasena']
      );
      /*
        para imprimircosas com una variable le pone el echo nomas echo '$tabla';
        para imprimir cosa de array 
        echo "<pre>";
        print_r($datosControlador);
        echo "</pre>";
        */
      $respuesta = Datos::verificar($datosControlador, $tabla);
      if ($respuesta == "ok") {
        echo "
            <script>
            Swal.fire({
                title: 'Bienvenido',
                text: 'Sesión Iniciada',
                icon: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Continuar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location='index.php?accion=principal'    
                    }
                    })
                    </script>
                    ";
      } else {
        echo "
                    <script>
                    Swal.fire({
                        title: 'Datos incorrectos',
                        text: 'Sesión inválida',
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Continuar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location='index.php?accion=login'
                            }
                            })
                            </script>
                            ";
      }
    }
  }

  static public function guardar_encuesta()
  {
    if (isset($_POST['titulo'])) {
      $data = array(
        0 => $_POST['titulo'],
        1 => $_POST['descripcion'],
        2 => $_POST['texto_pregunta'],
        3 => $_POST['tipo_pregunta'],
        4 => $_POST['inciso'],
      );


      $respuesta = Datos::guardar_encuesta_modelo($data, 'encuestas');
      echo $respuesta;


      if ($respuesta == 'ok') {
        echo 'se ha guardado';
      } else {
        echo 'error';
      }
    }
  }

  static public function Obtener_encuesta()
  {

    $resultados = Datos::Obtener_encuesta_modelo('encuestas');
    echo $json_resultados = json_encode($resultados);

?>



    <script>
      $(document).ready(function() {
        $('#miTabla').DataTable({
          data: <?php echo $json_resultados ?>,
          scrollY: 400,
          paging: true,
          columns: [{
              data: 'titulo',
              title: 'Titulo'
            },
            {
              data: 'descripcion',
              title: 'Descripción'
            },
            {
              data: 'id_encuesta',
              title: 'Descripción'
            },
            {
              data: 'fecha_creacion',
              title: 'Fecha de creación'
            },
            {
              data: null,
              orderable: false,
              render: function(data, type, row) {
                var rowData = {
                  id: row.id_encuesta,
                };
                return '<button  class="btn btn-primary editar detallesEncuesta" data-bs-toggle="modal" data-bs-target="#modalEditar" data-row=\'' + JSON.stringify(rowData) + '\'><i class="fa-solid fa-eye"></i></button>';
              },

            }
          ],
          language: {
            url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
          }
        });
        $('#miTabla tbody').on('click', 'button.editar', function() {
          var rowData = $(this).data('row'); // Obtiene los datos almacenados en el botón
          var idEncuesta = rowData.id;

          // Realiza una llamada AJAX para obtener los datos de las preguntas
          $.ajax({
            url: 'controlador/obtener_respuestas.php', // La URL donde se obtienen los datos de las preguntas
            type: 'POST',
            dataType: 'json', // Asegúrate de que jQuery espere y parsee la respuesta como JSON
            data: {
              id_encuesta: idEncuesta
            },
            success: function(data) {
              // Ahora `data` debería ser un arreglo si el servidor envía una respuesta en formato JSON válido
              // Genera HTML basado en los datos proporcionados
var preguntasHtml = '<ul>'; // Comienza una lista no ordenada

data.forEach(function(pregunta) {
    preguntasHtml += '<li>';
    preguntasHtml += '<strong>ID Pregunta:</strong> ' + pregunta.id_pregunta + '<br>';
    preguntasHtml += '<strong>Pregunta:</strong> ' + pregunta.pregunta + '<br>';
    preguntasHtml += '<strong>Tipo Pregunta:</strong> ' + pregunta.tipo_pregunta + '<br>';
    preguntasHtml += '<strong>Opciones:</strong> ' + pregunta.opciones + '<br>';
    preguntasHtml += '</li>';
});

preguntasHtml += '</ul>'; // Cierra la lista no ordenada

// Inserta el HTML generado en el elemento con el ID 'preguntasContenido'
$('#preguntasContenido').html(preguntasHtml);

              console.log(data);

              // Abre el modal
              $('#modalEditar').modal('show');
            },
            error: function(xhr, status, error) {

              // Maneja posibles errores
              console.log(error);

            }
          });

        });

      });




      function alertaEliminar(pk) {
        let timerInterval
        Swal.fire({
          title: 'Estás seguro de eliminar?',
          text: "Eliminará permanente el registro",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sí, eliminar!',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire({
              title: 'El regsitro fue eliminado!',
              icon: 'success',
              html: 'Redirigiendo<br>',
              timer: 1500,
              timerProgressBar: true,
              didOpen: () => {
                Swal.showLoading()
                const b = Swal.getHtmlContainer().querySelector('b')
                timerInterval = setInterval(() => {
                  b.textContent = Swal.getTimerLeft()
                }, 100)
              },
              willClose: () => {
                clearInterval(timerInterval)
              }
            }).then((result) => {
              /* Read more about handling dismissals below */
              if (result.dismiss === Swal.DismissReason.timer) {
                window.location = 'index.php?accion=papelera_persona&pk=' + pk;
              }
            })
          }
        })
      }
    </script>



<?php
  }

  static public function Obtener_lista_encuesta()
  {

    $resultados = Datos::Obtener_encuesta_modelo('encuestas');
    $json_resultados = json_encode($resultados);

?>



    <script>
      $(document).ready(function() {
        $('#miTabla').DataTable({
          data: <?php echo $json_resultados ?>,
          scrollY: 400,
          paging: true,
          columns: [{
              data: 'titulo',
              title: 'Titulo'
            },
            {
              data: 'descripcion',
              title: 'Descripción'
            },
            {
              data: 'fecha_creacion',
              title: 'Fecha de creación'
            },
            {
              data: null,
              orderable: false,
              render: function(data, type, row) {
                var rowData = {
                  id: row.id_encuesta,
                };
                return '<a href="index.php?accion=responder&=' + rowData.id + '" class="btn btn-success editar">Responder <i class="fa-solid fa-pencil"></i></a>';
              },

            }
          ],
          language: {
            url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
          }
        });

      });

    </script>



<?php
  }
}
