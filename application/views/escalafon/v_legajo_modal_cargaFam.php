<form  class="row pt-2">
  <div class="col-sm-12">
    <input type="hidden" name="ipersid" id="ipersid" value="<?PHP echo trim($ipersid); ?>" />
  </div>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Apellidos y Nombres de Conyugue:
    <div class="col-sm-8">
      <input type="text" name="cape_nom_conyug" id="cape_nom_conyug" class="form-control form-control-sm" />
    </div>
  </label>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Celular de conyugue:
    <div class="col-sm-6">
      <input type="date" name="ccel_conyug" id="ccel_conyug" class="form-control form-control-sm" />
    </div>
  </label>
  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Apellidos y Nombres del hijo:
    <div class="col-sm-6">
      <input type="date" name="cape_nom_hijos" id="cape_nom_hijos" class="form-control form-control-sm" />
    </div>
  </label>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Fecha de Nacimiento hijos:
    <div class="col-sm-7">
      <input type="text" name="cfechanac_hijos" id="cfechanac_hijos" class="form-control form-control-sm" />
    </div>
  </label>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Apellidos y Nombres del padre:
    <div class="col-sm-4">
      <input type="text" name="ape_nom_padre" id="ape_nom_padre" class="form-control form-control-sm" />
    </div>
  </label>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Celular del padre:
    <div class="col-sm-4">
      <input type="text" name="cel_padre" id="cel_padre" class="form-control form-control-sm" />
    </div>
  </label>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Apellidos y Nombres de la madre:
    <div class="col-sm-4">
      <input type="text" name="ape_nom_madre" id="ape_nom_madre" class="form-control form-control-sm" />
    </div>
  </label>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Celular de la madre:
    <div class="col-sm-4">
      <input type="text" name="cel_madre" id="cel_madre" class="form-control form-control-sm" />
    </div>
  </label>

  <div class="col-sm-12" align="center">
  <br><br>
    <?php
    switch ($accion) {
      case 'agregar':
    ?>
    <button onclick=" Persona.btn_estudios_accion_legajo(<?php echo $ipersid; ?>,'agregar'); " type="button" class="btn btn-primary">
      GUARDAR
    </button>
    <?php
        break;
      case 'actualizar':
      ?>
        <button onclick=" Persona.Ui.btn_estudios_accion(<?php echo $indiv_id; ?>,<?php echo $ipersid; ?>,'actualizar'); " type="button" class="btn btn-primary">
          ACTUALIZAR
        </button>
    <?php
        break;
      default:
        break;
      }
    ?>
  </div>
</form>