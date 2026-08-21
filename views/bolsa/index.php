        <header class="masthead2 py-5">
            <div class="container px-4 px-lg-5 my-5">
                <div class="text-center text-white">
                  <!--   <h1 class="display-4 fw-bolder">Shop in style</h1> -->
                    <p class="lead fw-normal mb-0 txt-tittle-sect"> = Bolsa de Trabajo =</p>
                </div>
            </div>
        </header>
<div class="container">
<form>
  <!-- 2 column grid layout with text inputs for the first and last names -->
  <div class="row mb-4">
    <div class="col">
      <div data-mdb-input-init class="form-outline">
            <labe class="form-label" for="form6Example1">Nombre(s):</label>
        <input type="text" id="form6Example1" class="form-control" />
      </div>
    </div>
    <div class="col">
      <div data-mdb-input-init class="form-outline">
           <label class="form-label" for="form6Example2">Apellido(s):</label>
        <input type="text" id="form6Example2" class="form-control" />
      </div>
    </div>
  </div>

  <!-- Text input -->
  <div data-mdb-input-init class="form-outline mb-4">
     <label class="form-label" for="form6Example3">Compañia:</label>
    <input type="text" id="form6Example3" class="form-control" />
   
  </div>

  <!-- Text input -->
  <div data-mdb-input-init class="form-outline mb-4">
    <input type="text" id="form6Example4" class="form-control" />
    <label class="form-label" for="form6Example4">Direccion:</label>
  </div>

  <!-- Email input -->
  <div data-mdb-input-init class="form-outline mb-4">
    <input type="email" id="form6Example5" class="form-control" />
    <label class="form-label" for="form6Example5">Correo Eléctronico:</label>
  </div>

  <!-- Number input -->
  <div data-mdb-input-init class="form-outline mb-4">
    <input type="number" id="form6Example6" class="form-control" />
    <label class="form-label" for="form6Example6">Telefono:</label>
  </div>

  <!-- Message input -->
  <div data-mdb-input-init class="form-outline mb-4">
    <textarea class="form-control" id="form6Example7" rows="4"></textarea>
    <label class="form-label" for="form6Example7">Additional information</label>
  </div>


  <!-- Submit button -->
  <button data-mdb-ripple-init type="button" class="btn btn-primary btn-block mb-4">Place order</button>
  
</form>
</div>

        <section class="py-5 ">
            <div class="container px-4 px-lg-5 mt-5">
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                    <div>
                        <form>
                            <div> <!-- Datos pesonales -->
                                <div><p>Datos Personales</p></div>
                                <div>
                                    <label for="Nombre_bolsa">Nombre(s):</label>
                                    <input type="text" id="Nombre_bolsa">
                                </div>
                                <div>
                                    <label for="ApellidoP_bolsa">Apellido Paterno:</label>
                                    <input type="text" id="ApellidoP_bolsa">
                                </div>
                                <div>
                                    <label for="ApellidoM_bolsa">Apellido Materno:</label>
                                    <input type="text" id="ApellidoM_bolsa">
                                </div>
                                <div>
                                    <label for="FechaN_bolsa">Fecha de Nacimiento:</label>
                                    <input type="Date" id="FechaN_bolsa">
                                </div>
                                <div>
                                    <label for="Telefono_bolsa">Numero Telefonico:</label>
                                    <input type="tel" id="Telefono_bolsa">
                                </div>
                                <div>
                                    <label for="WhatsApp_bolsa">WhatsApp:</label>
                                    <input type="text" id="WhatsApp_bolsa">
                                </div>
                                <div>
                                    <label for="Correo_bolsa">Correo:</label>
                                    <input type="email" id="Correo_bolsa">
                                </div>
                            </div>
                            <div> <!-- Direccion -->
                                <div><p>Direccion</p></div>
                                <div>
                                    <label for="Calle_bolsa">Calle:</label>
                                    <input type="text" id="Calle_bolsa">
                                </div>
                                <div>
                                    <label for="NoExterior_bolsa">No. Exterior:</label>
                                    <input type="text" id="NoExterior_bolsa">
                                </div>
                                <div>
                                    <label for="NoInterior_bolsa">No. Interior:</label>
                                    <input type="text" id="NoInterior_bolsa">
                                </div>
                                <div>
                                    <label for="Mza_bolsa">Mza:   </label>
                                    <input type="text" id="Mza_bolsa">
                                </div>
                                <div>
                                    <label for="Lt_bolsa">Lt:      </label>
                                    <input type="text" id="Lt_bolsa">
                                </div>
                                <div>
                                    <label for="Colonia_bolsa">Colonia:</label>
                                    <input type="text" id="Colonia_bolsa">
                                </div>
                                <div>
                                    <label for="CodigoP_bolsa">Codigo Postal:</label>
                                    <input type="text" id="CodigoP_bolsa">
                                </div>
                            </div>
                            <div> <!-- Direccion -->
                                <div><p>Datos Laborales</p></div>
                                <div>
                                    <label for="Ocupacion_bolsa">Ocupacion:</label>
                                    <input type="text" id="Ocupacion_bolsa">
                                </div>
                                <div>
                                    <label for="PuestoOcu_bolsa">Puestos a Ocupar:</label>
                                    <select name="PuestoOcu_bolsa" id="PuestoOcu_bolsa" required>
                                        <option value="" selected disabled>Selecciona el tipo de mensaje</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="PuestosAnt_bolsa">Puestos Anteriores:</label>
                                    <textarea id="PuestosAnt_bolsa" name="PuestosAnt_bolsa"></textarea>
                                </div>
                                <div>
                                    <label for="Curriculum_bolsa">Curriculum:</label>
                                    <label for="Curriculum_bolsa" class="custom-file-upload">. . .</label>
                                    <input type="file" class="form-control" name="Curriculum_bolsa" id="Curriculum_bolsa">
                                </div>
                            </div>
                        </form>
                        <div> <!-- Botones de accion -->
                            <button id="Enviar_bolsa"> Enviar </button>
                            <button id="limpiar_bolsa"> Limpiar </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>