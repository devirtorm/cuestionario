<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="card p-5 mx-auto" style="width: 70vh;">
            <div class="card-body">
                <h5 class="card-title mb-2 text-center fw-bold">Agregar Categoría</h5>
                <form method="post">
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre</label>
                        <input type="text" name="nombre" class="form-control" id="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="color" class="form-label fw-bold">Color</label>
                        <input type="color" name="color" value="#563d7c" class="form-control form-control-color" id="color" required>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-success w-25" name="agregarCategoria">Enviar</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>


<?php
$categoria = new Controlador();
$categoria->guardarcategoria();
?>