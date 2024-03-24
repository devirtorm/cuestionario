<div class="container-fluid align-items-center">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="login d-flex align-items-center py-5">
                <div class="container">
                    <div class="row">
                        <div class="card p-5 shadow-sm">
                            <div class="col-md-9 col-lg-8 mx-auto">
                                <h3 class="login-heading mb-4 text-center">Regístrate</h3>

                                <!-- Registration Form -->
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre">
                                    </div>
                                    <div class="mb-3">
                                        <label for="primer_apellido">Primer Apellido</label>
                                        <input type="text" class="form-control" name="primer_apellido" id="primer_apellido" placeholder="Primer Apellido">
                                    </div>
                                    <div class="mb-3">
                                        <label for="segundo_apellido">Segundo Apellido</label>
                                        <input type="text" class="form-control" name="segundo_apellido" id="segundo_apellido" placeholder="Segundo Apellido">
                                    </div>
                                    <div class="mb-3">
                                        <label for="formFile" class="form-label">Selecciona una imagen</label>
                                        <input class="form-control" type="file" id="formFile" name="foto" accept="image/*">
                                    </div>
                                    <div class="mb-3">
                                        <label for="correo">Correo electrónico</label>
                                        <input type="text" class="form-control" name="correo" id="correo" placeholder="Correo">
                                    </div>
                                    <div class="mb-3">
                                        <label for="clave">Contraseña</label>
                                        <input type="password" class="form-control" name="clave" id="clave" placeholder="Clave">
                                    </div>
                                    <div class="">
                                        <button class="btn btn-success mb-2" type="submit">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php

    $registro = new Controlador();
    $registro->ingresarregistro();
    ?>