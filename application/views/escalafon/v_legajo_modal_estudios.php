<form class="row pt-2" name="FormEstudios">


  <label class="col-sm-12 col-form-label text-uppercase font-weight-bold">Nombre de Centro de Estudios:
  </label>
  <div class="col-sm-12">
    <input type="text"  name="e_ccentroestudios"  id="e_ccentroestudios" value="<?php echo trim($estudios[0]['ccentroestudios']);?>" class="form-control form-control-sm" />
  </div>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Fecha Inicio:
  </label>
  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Fecha de Término:
  </label>

  <div class="col-sm-6">
    <input type="date"  name="e_dfechainicio" id="e_dfechainicio" value="<?php echo trim($estudios[0]['dfechainicio']);?>"  class="form-control form-control-sm" />
  </div>

  <div class="col-sm-6">
    <input type="date"  name="e_dfechatermino" id="e_dfechatermino" value="<?php echo trim($estudios[0]['dfechatermino']);?>"  class="form-control form-control-sm" />
  </div>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Grado o titulo:
  </label>
  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Colegiatura Nro:
  </label>
  <div class="col-sm-6">
    <input type="text"  name="e_cgrado_titulo" id="e_cgrado_titulo" value="<?php echo trim($estudios[0]['cgrado_titulo']);?>"   class="form-control form-control-sm" />
  </div>

  <div class="col-sm-6">
    <input type="text"  name="e_ccolegiaturanro" id="e_ccolegiaturanro" value="<?php echo trim($estudios[0]['ccolegiaturanro']);?>"  class="form-control form-control-sm" />
  </div>

  <div class="col-sm-12" align="center">
    <br><br>
    <?php
    switch ($accion) {
      case 'agregar':
    ?>
        <button onclick=" Persona.Ui.btn_estudios_accion(<?php echo $indiv_id; ?>,null,<?php echo $ipersid; ?>,'agregar',this.form); " type="button" class="btn btn-primary">
          GUARDAR  
        </button>
      <?php
        break;
      case 'actualizar':
      ?>
        <button onclick=" Persona.Ui.btn_estudios_accion(<?php echo $indiv_id; ?>,<?php echo $iperstipoestudid; ?>,<?php echo $ipersid; ?>,'actualizar',this.form); " type="button" class="btn btn-primary">
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