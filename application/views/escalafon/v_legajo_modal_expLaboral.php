<form class="row pt-2" name="FormLaboral">


  <label class="col-sm-12 col-form-label text-uppercase font-weight-bold">Cargo Desempeñado:</label>
  <div class="col-sm-12">
    <input type="text" name="ccargos_desempenados" id="ccargos_desempenados" value="<?php echo trim($laboral[0]['ccargos_desempenados']);?>" class="form-control form-control-sm" />
  </div>

  <label class="col-sm-12 col-form-label text-uppercase font-weight-bold">Lugar de Laburo:</label>
  <div class="col-sm-12">
      <input type="text" name="lugar_laburo" id="lugar_laburo" value="<?php echo trim($laboral[0]['lugar_laburo']);?>" class="form-control form-control-sm" />
  </div>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Fecha Inicio:</label>
  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Fecha de Término:</label>

  <div class="col-sm-6">
      <input type="date" name="dfechainicio" id="dfechainicio" value="<?php echo trim($laboral[0]['dfechainicio']);?>" class="form-control form-control-sm" />
    </div>

    <div class="col-sm-6">
      <input type="date" name="dfechatermino" id="dfechatermino" value="<?php echo trim($laboral[0]['dfechatermino']);?>" class="form-control form-control-sm" />
    </div>

  <div class="col-sm-12" align="center">
    <br><br>
    <?php
    switch ($accion) {
      case 'agregar':
    ?>
        <button onclick="Persona.Ui.btn_laboral_accion(<?php echo $indiv_id; ?>,null,<?php echo $ipersid; ?>,'agregar',this.form); " type="button" class="btn btn-primary">
          GUARDAR
        </button>
      <?php
        break;
      case 'actualizar':
      ?>
        <button onclick="Persona.Ui.btn_laboral_accion(<?php echo $indiv_id; ?>,<?php echo $iexp_laboralid; ?>,<?php echo $ipersid; ?>,'actualizar',this.form); " type="button" class="btn btn-primary">
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