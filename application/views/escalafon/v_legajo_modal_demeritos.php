<form  class="row pt-2">
  <div class="col-sm-12">
    <input type="hidden" name="ipersid" id="ipersid" value="<?PHP echo trim($ipersid); ?>" />
  </div>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Sancion:
    <div class="col-sm-8">
      <input type="text" name="sancion" id="sancion" class="form-control form-control-sm" />
    </div>
  </label>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Documento Resolucion/Carta:
    <div class="col-sm-6">
      <input type="date" name="cdocumentoresolucion" id="cdocumentoresolucion" class="form-control form-control-sm" />
    </div>
  </label>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Documento Nro:
    <div class="col-sm-8">
      <input type="text" name="cdocumentonro" id="cdocumentonro" class="form-control form-control-sm" />
    </div>
  </label>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Fecha inical:
    <div class="col-sm-8">
      <input type="text" name="cfecha_ini" id="cfecha_ini" class="form-control form-control-sm" />
    </div>
  </label>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Fecha de Término:
    <div class="col-sm-6">
      <input type="date" name="cfecha_fin" id="cfecha_fin" class="form-control form-control-sm" />
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