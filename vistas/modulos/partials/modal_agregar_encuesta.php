<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="post">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="exampleModalLabel">Crear una encuesta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="Titulo">Titulo</label>
                        <input type="text" class="form-control" name="titulo">
                    </div>
                    <div class="mb-3">
                        <label for="Titulo">Descripción</label>
                        <input type="text" class="form-control" name="descripcion">
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="fw-bolder fs-5">Agregar pregunta</p>
                        <button class="btn btn-success" id="add-question">+</button>
                    </div>
                    <hr class="border-success">
                </div>
                <div class="row">
                    <div id="question-container"></div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#add-question').click(function() {
            var newQuestion = `
            <div class="question-group mb-3 border rounded-3 shadow-sm p-3 m-2">
                <div class="form-group">
                    <label>Información de la pregunta:</label>
                    <input type="text" name="texto_pregunta[]" class="form-control" placeholder="Introduce tu pregunta" required>
                </div>
                <div class="form-group mb-3">
                    <label>Tipo de pregunta:</label>
                    <select class="form-control question-type" name="tipo_pregunta[]" required>
                        <option disabled selected value="">Selecciona una opción...</option>
                        <option value="opcion_multiple">Opción múltiple</option>
                        <option value="texto">Texto</option>
                        <option value="verdadero_falso">Verdadero o falso</option>
                    </select>
                </div>
                <div class="additional-input"></div>
                <button class="btn btn-danger remove-question">-</button>
            </div>
        `;
            $('#question-container').append(newQuestion);
        });

        $('#question-container').on('click', '.remove-question', function() {
            $(this).closest('.question-group').remove();
        });

        $('#question-container').on('change', '.question-type', function() {
            var selectedType = $(this).val();
            var $additionalInputContainer = $(this).closest('.question-group').find('.additional-input');
            $additionalInputContainer.empty(); // Limpia el contenedor adicional antes de agregar un nuevo campo

            if (selectedType === 'opcion_multiple') {
                var multipleChoiceField = `
                <div class="form-group mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <label>Opciones:</label>
                        <button type="button" class="badge bg-primary add-option">Agregar opción</button>
                    </div>
                    <div class="options-container">
                        <!-- Añadir aquí el campo de opción por defecto -->
                        <div class="d-flex justify-content-between mb-2 option-field">
                            <input type="text" name="inciso[]" class="form-control me-2" placeholder="Introduce una opción" required>
                            <button type="button" class="btn btn-danger remove-option">-</button>
                        </div>
                    </div>
                </div>
            `;
                $additionalInputContainer.append(multipleChoiceField);
            }
        });

        // Evento para agregar una nueva opción
        $('#question-container').on('click', '.add-option', function() {
            var optionField = `
            <div class="d-flex justify-content-between mb-2 option-field">
                <input type="text" name="inciso[]" class="form-control me-2" placeholder="Introduce una opción" required>
                <button type="button" class="btn btn-danger remove-option">-</button>
            </div>
        `;
            $(this).closest('.form-group').find('.options-container').append(optionField);
        });

        // Evento para eliminar una opción específica
        $('#question-container').on('click', '.remove-option', function() {
            $(this).closest('.option-field').remove();
        });
    });
</script>

<?php
$guardar_encuesta = new Controlador();
$guardar_encuesta->guardar_encuesta();
?>