<div class="alert alert-success p-5 shadow">
    <h3 class="text-success">Modulo de encuestas</h3>
</div>
<div class="container">
    <div class="shadow-sm card p-3 mb-4">
        <table class="table table-hover" id="miTabla">
            <?php
            $lista_encuestas =  new Controlador();
            $lista_encuestas->Obtener_lista_encuesta();
            ?>
        </table>
    </div>
</div>