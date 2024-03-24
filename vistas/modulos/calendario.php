    
    <div id="calendar" class="mt-5"></div>
    <script>
      $(document).ready(function() {
        $("#calendar").fullCalendar({
          header: {
            left: "prev,next today",
            right: "title",
            center: "month,agendaWeek,agendaDay"
          },
          locale: 'es',
          defaultView: "month",
          navLinks: true,
          editable: true,
          eventLimit: true,
          selectable: true,
          selectHelper: true,
          
          select: function(start, end) {
            $("#exampleModal").modal(); // Abre un modal con id "exampleModal"
            $("input[name=fecha_inicio]").val(start.format('YYYY-MM-DD')); // Establece el valor de un input con nombre "fecha_inicio" con la fecha de inicio en formato 'YYYY-MM-DD'
            var valorFechaFin = end.format("DD-MM-YYYY"); // Obtiene el valor de la fecha de fin en formato "DD-MM-YYYY"
            var F_final = moment(valorFechaFin, "DD-MM-YYYY").subtract(1, 'days').format('DD-MM-YYYY'); // Calcula un día antes de la fecha de fin y la formatea en "DD-MM-YYYY"
            $('input[name=fecha_fin').val(F_final); // Establece el valor de un input con nombre "fecha_fin" con la fecha final modificada
          },

          events: [

            <?php
            $eventos = Datos::obtenerEventosPorUsuario();
            foreach ($eventos as $evento) :
            ?> {
                _id: '<?php echo $evento["pk_evento"]; ?>',
                title: '<?php echo $evento["titulo_evento"]; ?>',
                <?php if ($evento["todo_dia"] == '') { ?>
                  start: '<?php echo $evento["fecha"] . "T" . $evento["hora_inicio"]; ?>',
                  end: '<?php echo $evento["fecha"] . "T" . $evento["hora_fin"]; ?>',
                <?php } ?>
                <?php if ($evento["todo_dia"] == '1') { ?>
                  start: '<?php echo $evento["fecha"]  ?>',
                  end: '<?php echo $evento["fecha"]  ?>',
                <?php } ?>
                color: '<?php echo $evento["color_prioridad"]; ?>',
                borderColor: '<?php echo $evento["color_categoria"]; ?>',
                allDay: '<?php
                          if ($evento["todo_dia"] == '') {
                            echo "false";
                          } else if ($evento["todo_dia"] == '1') {
                            echo "true";
                          }
                          ?>',
                extendedProps: {
                  horaInicio: '<?php echo $evento["hora_inicio"] ?>',
                  horaFin: '<?php echo $evento["hora_fin"] ?>',
                  todoDia: '<?php echo $evento["todo_dia"] ?>',
                  nota: '<?php echo $evento["nota"] ?>',
                  idPrioridad: '<?php echo $evento["pk_prioridad"] ?>',
                  idCategoria: '<?php echo $evento["pk_categoria"] ?>',
                  notificacion: '<?php echo $evento["notificacion"] ?>',
                  titulo: '<?php echo $evento["titulo_evento"] ?>',
                  fecha: '<?php echo $evento["fecha"] ?>'

                }
              },
            <?php endforeach ?>
          ],
          eventRender: function(event, element) {
            element
              .find(".fc-content")
              .prepend("<span id='btnCerrar'; class='closeon material-icons'>&#xe5cd;</span>");
            element.find(".closeon").on("click", function() {
              var pregunta = confirm("Deseas Borrar este Evento?");
              if (pregunta) {
                $("#calendar").fullCalendar("removeEvents", event._id);
                $.ajax({
                  type: "POST",
                  url: 'vistas/modulos/deleteEvento.php',
                  data: {
                    id: event._id
                  },
                  success: function(datos) {
                    $(".alert-danger").show();
                    setTimeout(function() {
                      $(".alert-danger").slideUp(500);
                    }, 3000);
                  }
                });
              }
            });
          },
          eventDrop: function(event, delta) {
            var idEvento = event._id;
            var start = (event.start.format('DD-MM-YYYY'));
            var end = (event.end.format("DD-MM-YYYY"));
            $.ajax({
              url: 'modelo/drag_drop_evento.php',
              data: 'start=' + start + '&end=' + end + '&idEvento=' + idEvento,
              type: "POST",
              success: function(response) {
                // $("#respuesta").html(response);
              }
            });
          },
          eventClick: function(event) {
            var idEvento = event._id;
            $('input[name=idEvento').val(idEvento);
            $('input[name=evento').val(event.title);
            $('input[name=fecha_inicio').val(event.start.format('DD-MM-YYYY'));
            $('input[name=fecha_fin').val(event.end.format("DD-MM-YYYY"));
            $("#modalUpdateEvento").modal();
          },
          //Modificar Evento del Calendario 
          eventClick: function(event) {

            $("#modalEditarEvento").modal('show');
            var id = event._id;
            var horaInicio = event.extendedProps.horaInicio;
            var horaFin = event.extendedProps.horaFin;
            var todoDia = event.extendedProps.todoDia;
            var nota = event.extendedProps.nota;
            var idPrioridad = event.extendedProps.idPrioridad;
            var idCategoria = event.extendedProps.idCategoria;
            var notificacion = event.extendedProps.notificacion;
            var titulo = event.extendedProps.titulo;
            var fecha = event.extendedProps.fecha;

            if (todoDia === '') {
              todoDia = 'false';
            } else if (todoDia == '1') {
              todoDia = 'true';
            }

            $("#modalEditarEvento #idEvento").val(id);
            $("#modalEditarEvento #hora_inicio").val(horaInicio);
            $("#modalEditarEvento #hora_fin").val(horaFin);
            $("#modalEditarEvento #titulo").val(titulo);
            $("#modalEditarEvento #tituloAgregarEvento").text('Modificar evento: ' + fecha);
            $("#modalEditarEvento #fecha").val(fecha);
            $("#modalEditarEvento #todo_dia").val(todoDia);
            $("#modalEditarEvento #notificacion").val(notificacion);
            $("#modalEditarEvento #nota").val(nota);
            $("#modalEditarEvento #fk_prioridad").val(idPrioridad);
            $("#modalEditarEvento #fk_categoria").val(idCategoria);

          }
        });
        setTimeout(function() {
          $(".alert").slideUp(300);
        }, 3000);
      });
    </script>




    <!-- modal para registrar nuevo evento -->
    <?php include "vistas/modulos/partials/modal_evento.php" ?>
    <!-- termina modal registrar nuevo evento -->




    <!-- Modal editar evento -->
    <?php include "vistas/modulos/partials/modal_editar_evento.php" ?>
    <!-- termina modal editar evento -->



    <!-- formulario para eliminar el evento -->
    <form class="d-none" method="post" id="eliminarEventoForm">
      <input type="hidden" name="eventoEliminar" id="eventoEliminar" value="0">
      <input type="hidden" name="borrarEvento">
    </form>

    <script type="text/javascript">
      $("#eliminarEvento").click(function(e) {
        e.preventDefault();
        $("#eventoEliminar").val($("#idEvento").val());
        Swal.fire({
          title: "¡Confirmar eliminación!",
          text: "",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#3085d6",
          confirmButtonText: "Sí, eliminar",
          cancelButtonText: "Cancelar"
        }).then((result) => {
          if (result.isConfirmed) {

            $("#eliminarEventoForm").submit();
          }
        });
      });
    </script>

    <!-- termina eliminar evento -->


    <?php
    $evento = new Controlador();
    $evento->registroevento();
    $evento->editarevento();
    $evento->borrarEvento();
    ?>