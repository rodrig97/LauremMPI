<form class="row pt-2" name="FormDemeritos">

  <label class="col-sm-12 col-form-label text-uppercase font-weight-bold">Sancion:</label>
  <div class="col-sm-12">
    <input type="text" name="sancion" id="sancion" value="<?php echo trim($demeritos[0]['sancion']); ?>" class="form-control form-control-sm" />
  </div>

  <label class="col-sm-12 col-form-label text-uppercase font-weight-bold">Documento Resolucion/Carta:</label>
  <div class="col-sm-12">
    <input type="text" name="cdocumentoresolucion" id="cdocumentoresolucion" value="<?php echo trim($demeritos[0]['cdocumentoresolucion']); ?>" class="form-control form-control-sm" />
  </div>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Documento Nro:
  </label>
  <div class="col-sm-6">
    <input type="text" name="cdocumentonro" id="cdocumentonro" value="<?php echo trim($demeritos[0]['cdocumentonro']); ?>" class="form-control form-control-sm" />
  </div>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Fecha inical:</label>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Fecha de Término:</label>

  <div class="col-sm-6">
    <input type="date" name="cfecha_ini" id="cfecha_ini" class="form-control form-control-sm" value="<?php echo trim($demeritos[0]['cfecha_ini']); ?>" />
  </div>
  <div class="col-sm-6">
    <input type="date" name="cfecha_fin" id="cfecha_fin" class="form-control form-control-sm" value="<?php echo trim($demeritos[0]['cfecha_fin']); ?>" />
  </div>

  <div class="col-sm-12" align="center">
    <br><br>
    <?php
    switch ($accion) {
      case 'agregar':
    ?>
        <button onclick="Persona.Ui.btn_demeritos_accion(<?php echo $indiv_id; ?>,null,<?php echo $ipersid; ?>,'agregar',this.form); " type="button" class="btn btn-primary">
          GUARDAR
        </button>
      <?php
        break;
      case 'actualizar':
      ?>
        <button onclick="Persona.Ui.btn_demeritos_accion(<?php echo $indiv_id; ?>,<?php echo $idemeritosid; ?>,<?php echo $ipersid; ?>,'actualizar',this.form); " type="button" class="btn btn-primary">
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