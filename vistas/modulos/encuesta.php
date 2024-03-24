<div class="alert alert-success p-5 shadow">
    <h3 class="text-success">Lista de encuestas</h3>
</div>
<div class="container">
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">Crear encuesta</button>
    </div>
    <div class="shadow-sm card p-3 mb-4">
        <table class="table table-hover" id="miTabla">

            <?php
            $lista_encuestas =  new Controlador();
            $lista_encuestas->Obtener_encuesta();
            ?>

        </table>
    </div>
</div>
<?php
include 'vistas/modulos/partials/modal_agregar_encuesta.php';
include 'vistas/modulos/partials/modal_mostrar_preguntas.php';

