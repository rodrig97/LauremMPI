<form  class="row pt-2">
  <div class="col-sm-12">
    <input type="hidden" name="ipersid" id="ipersid" value="<?PHP echo trim($ipersid); ?>" />
  </div>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Tipo de Merito:
    <div class="col-sm-8">
      <input type="text" name="ctipomerito" id="ctipomerito" class="form-control form-control-sm" />
    </div>
  </label>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Documento Tipo:
    <div class="col-sm-6">
      <input type="date" name="cdocumentotipo" id="cdocumentotipo" class="form-control form-control-sm" />
    </div>
  </label>
  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Doc Nro:
    <div class="col-sm-6">
      <input type="date" name="cdocumentonro" id="cdocumentonro" class="form-control form-control-sm" />
    </div>
  </label>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Documento Fecha:
    <div class="col-sm-7">
      <input type="text" name="cdocumentofecha" id="cdocumentofecha" class="form-control form-control-sm" />
    </div>
  </label>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Motivo:
    <div class="col-sm-4">
      <input type="text" name="cmotivo" id="cmotivo" class="form-control form-control-sm" />
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