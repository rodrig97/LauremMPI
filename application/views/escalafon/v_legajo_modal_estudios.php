<form class="row pt-2">


  <label class="col-sm-12 col-form-label text-uppercase font-weight-bold">Nombre de Centro de Estudios:
  </label>
  <div class="col-sm-12">
    <input type="text" name="ccentroestudios" id="ccentroestudios" value="<?php echo trim($estudios[0]['ccentroestudios']);?>" class="form-control form-control-sm" />
  </div>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Fecha Inicio:
  </label>
  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Fecha de Término:
  </label>

  <div class="col-sm-6">
    <input type="date" name="dfechainicio" id="dfechainicio" value="<?php echo trim($estudios[0]['dfechainicio']);?>" class="form-control form-control-sm" />
  </div>

  <div class="col-sm-6">
    <input type="date" name="dfechatermino" id="dfechatermino" value="<?php echo trim($estudios[0]['dfechatermino']);?>" class="form-control form-control-sm" />
  </div>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Grado o titulo:
  </label>
  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Colegiatura Nro:
  </label>
  <div class="col-sm-6">
    <input type="text" name="cgrado_titulo" id="cgrado_titulo" value="<?php echo trim($estudios[0]['cgrado_titulo']);?>" class="form-control form-control-sm" />
  </div>

  <div class="col-sm-6">
    <input type="text" name="ccolegiaturanro" id="ccolegiaturanro" value="<?php echo trim($estudios[0]['ccolegiaturanro']);?>" class="form-control form-control-sm" />
  </div>

  <div class="col-sm-12" align="center">
    <br><br>
    <?php
    switch ($accion) {
      case 'agregar':
    ?>
        <button onclick=" Persona.Ui.btn_estudios_accion(<?php echo $indiv_id; ?>,<?php echo $iperstipoestudid; ?>,<?php echo $ipersid; ?>,'agregar'); " type="button" class="btn btn-primary">
          GUARDAR  
        </button>
      <?php
        break;
      case 'actualizar':
      ?>
        <button onclick=" Persona.Ui.btn_estudios_accion(<?php echo $indiv_id; ?>,<?php echo $iperstipoestudid; ?>,<?php echo $ipersid; ?>,'actualizar'); " type="button" class="btn btn-primary">
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