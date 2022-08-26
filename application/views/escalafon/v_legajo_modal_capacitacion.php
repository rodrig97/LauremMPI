<form class="row pt-2" >
 <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Tipo de Capacitación:</label>
  <div class="col-sm-6">
    <select name="c_itipocapacid" id="c_itipocapacid" class="form-control form-control-sm" >
      <?PHP
      foreach ($tipo_capacitacion as $tipcap) {
      ?>
        <option value="<?PHP echo $tipcap['itipocapacid'] ?>"> <?PHP echo $tipcap['ctipocapacdsc'] ?></option>
      <?PHP
      }
      ?>
    </select>
  </div>


  <label class="col-sm-12 col-form-label text-uppercase font-weight-bold">Nombre de Centro de Estudios:</label>
  <div class="col-sm-12">
    <input type="text" name="c_ccentroestudios" id="c_ccentroestudios" class="form-control form-control-sm" />
  </div>


  <label class="col-sm-12 col-form-label text-uppercase font-weight-bold">Denominacion:</label>
  <div class="col-sm-12">
    <input type="text" name="c_cdenominacion" id="c_cdenominacion" class="form-control form-control-sm" />
  </div>

  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Fecha Inicio:</label>
  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Fecha de Término:</label>

  <div class="col-sm-6">
    <input type="date" name="c_dfechainicio" id="c_dfechainicio" class="form-control form-control-sm" />
  </div>
  <div class="col-sm-6">
    <input type="date" name="c_dfechatermino" id="c_dfechatermino" class="form-control form-control-sm" />
  </div>
  <br>
  <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Horas:</label>
  <div class="col-sm-2">
      <input type="number" name="c_ihoras" id="c_ihoras" class="form-control form-control-sm" />
     
    </div>

  <div class="col-sm-12" align="center">
  <br><br>
    <?php
    switch ($accion) {
      case 'agregar':
    ?>
        <button onclick="Persona.Ui.btn_capacitacion_accion(<?php echo $indiv_id; ?>,'',<?php echo $ipersid; ?>,'agregar'); " type="button" class="btn btn-primary">
          GUARDAR
        </button>
      <?php
        break;
      case 'actualizar':
      ?>
        <button onclick="Persona.Ui.btn_capacitacion_accion(<?php echo $indiv_id; ?>,<?php echo $iperstipocapacid; ?>,<?php echo $ipersid; ?>,'actualizar'); " type="button" class="btn btn-primary">
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