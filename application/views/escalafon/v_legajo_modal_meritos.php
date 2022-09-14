<form class="row pt-2" name="FormMeritos">

  <label class="col-sm-12 col-form-label text-uppercase font-weight-bold">Tipo de Merito:</label>
  <div class="col-sm-12">
    <input type="text" name="ctipomerito" id="ctipomerito" value="<?php echo trim($meritos[0]['ctipomerito']);?>" class="form-control form-control-sm" />
  </div>

  <label class="col-sm-12 col-form-label text-uppercase font-weight-bold">Documento Tipo:</label>
  <div class="col-sm-12">
    <input type="text" name="cdocumentotipo" id="cdocumentotipo" value="<?php echo trim($meritos[0]['cdocumentotipo']);?>" class="form-control form-control-sm" />
  </div>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Doc Nro:</label>
  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Documento Fecha:</label>

  <div class="col-sm-6">
      <input type="text" name="cdocumentonro" id="cdocumentonro" value="<?php echo trim($meritos[0]['cdocumentonro']);?>" class="form-control form-control-sm" />
  </div>
  <div class="col-sm-6">
      <input type="date" name="cdocumentofecha" id="cdocumentofecha" value="<?php echo trim($meritos[0]['cdocumentofecha']);?>" class="form-control form-control-sm" />
  </div>

  <label class="col-sm-12 col-form-label text-uppercase font-weight-bold">Motivo:</label>
  <div class="col-sm-12">
      <input type="text" name="cmotivo" id="cmotivo" value="<?php echo trim($meritos[0]['cmotivo']);?>" class="form-control form-control-sm" />
    </div>

  <div class="col-sm-12" align="center">
    <br><br>
    <?php
    switch ($accion) {
      case 'agregar':
        
    ?>
        <button onclick="Persona.Ui.btn_meritos_accion(<?php echo $indiv_id; ?>,null,<?php echo $ipersid; ?>,'agregar',this.form); " type="button" class="btn btn-primary">
          GUARDAR
        </button>
      <?php
        break;
      case 'actualizar':
      ?>
        <button onclick="Persona.Ui.btn_meritos_accion(<?php echo $indiv_id; ?>,<?php echo $imeritosid; ?>,<?php echo $ipersid; ?>,'actualizar',this.form); " type="button" class="btn btn-primary">
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