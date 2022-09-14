<style>
    .scroll-bg {
    background: rgb(255,255,255);
    width: 1000px;
    margin: 10% auto;
    padding: 10px;
}

.scroll-div {
    width: 930px;
    background: rgb(255,255,255);
    height:500px;
    overflow: hidden;
    overflow-y: scroll;
}

.scroll-object {
    font-family: cursive;
    font-size: 12px;
    padding: 5px;
}
</style>

<form class="row" name="FormDatosPers">
<div class="scroll-bg">
    <div class="scroll-div">
        <div class="scroll-object">
        <div class="col-sm-12 alert alert-primary" role="alert">
            <b>DATOS PERSONALES</b>
        </div>
    <label class="col-sm-1 col-form-label text-uppercase font-weight-bold"></label>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="upload">
        <input type="submit" value="Submit">
    </form>
    <label class="col-sm-12 col-form-label text-uppercase font-weight-bold"></label>
    <label class="col-sm-1 col-form-label text-uppercase font-weight-bold">Nombres
    </label>
    <input type="text" name="indiv_nombres" id="indiv_nombres" value="<?php echo trim($datos_personales[0]['indiv_nombres']); ?>" class="col-sm-3 col-form-label">
    </input>
    <label class="col-sm-1 col-form-label text-uppercase font-weight-bold">Apellidos
    </label>
    <input type="text" name="" id="" value="<?php echo $datos_personales[0]['indiv_appaterno'] . ' ' . $datos_personales[0]['indiv_apmaterno']; ?>" class="col-sm-3 col-form-label">
    </input>
    <label class="col-sm-1 col-form-label text-uppercase font-weight-bold">DNI
    </label>
    <input type="text" name="indiv_dni" id="indiv_dni" value="<?php echo trim($datos_personales[0]['indiv_dni']);?>" class="col-sm-2 col-form-label">
    </input>
    <label class="col-sm-12 col-form-label text-uppercase font-weight-bold"></label>
    <label class="col-sm-1 col-form-label text-uppercase font-weight-bold">RUC
    </label>
    <input type="text" name="cpersruc" id="cpersruc" value="<?php echo trim($datos_personales[0]['cpersruc']);?>" class="col-sm-2 col-form-label">
    </input>
    <label class="col-sm-2 col-form-label text-uppercase font-weight-bold">Direccion
    </label>
    <input type="text" name="cpersdireccion" id="cpersdireccion" value="<?php echo trim($datos_personales[0]['cpersdireccion']);?>" class="col-sm-2 col-form-label">
    </input>
    
    <label class="col-sm-1 col-form-label text-uppercase font-weight-bold">Celular
    </label>
    <input type="text" name="indiv_celular" id="indiv_celular" value="<?php echo trim($datos_personales[0]['indiv_celular']);?>" class="col-sm-2 col-form-label">
    </input>
    
    <label class="col-sm-12 col-form-label text-uppercase font-weight-bold"></label>
    <label class="col-sm-1 col-form-label text-uppercase font-weight-bold">Email
    </label>
    <input type="text" name="indiv_email" id="indiv_email" value="<?php echo trim($datos_personales[0]['indiv_email']);?>" class="col-sm-2 col-form-label">
    </input>
    <label class="col-sm-1 col-form-label text-uppercase font-weight-bold">Fecha Nacimiento
    </label>
    <input type="date" name="dfechanac" id="dfechanac" value="<?php echo trim($datos_personales[0]['dfechanac']);?>" class="col-sm-2 col-form-label">
    </input>
    <label class="col-sm-1 col-form-label text-uppercase font-weight-bold">Libreta Militar
    </label>
    <input type="text" name="cperslibreta" id="cperslibreta" value="<?php echo trim($datos_personales[0]['cperslibreta']);?>" class="col-sm-2 col-form-label">
    </input>
    <label class="col-sm-1 col-form-label text-uppercase font-weight-bold">Licencia Conducir
    </label>
    <input type="text" name="cperslicencia" id="cperslicencia" value="<?php echo trim($datos_personales[0]['cperslicencia']);?>" class="col-sm-2 col-form-label">
    </input>

    <label class="col-sm-4 col-form-label text-uppercase font-weight-bold">Grupo Sanguineo:</label>
    <div class="col-sm-4">
        <select name="igruposangid" id="igruposangid" class="form-control form-control-sm">
            <?PHP
            foreach ($grupo_sanguineo as $sanguineo) {
            ?>
                <option value="<?PHP echo $sanguineo['igruposangid'] ?>" <?php if ($datos_personales[0]['igruposangid'] == $sanguineo['igruposangid']) echo ' selected="selected"'; ?>> <?PHP echo $sanguineo['cgruposangdsc'] ?></option>
            <?PHP
            }
            ?>
        </select>
    </div>
    <label class="col-sm-4 col-form-label text-uppercase font-weight-bold">Tipo de Seguro:</label>
    <div class="col-sm-4">
        <select name="itiposegid" id="itiposegid" class="form-control form-control-sm">
            <?PHP
            foreach ($tipo_seguro as $tipseg) {
            ?>
                <option value="<?PHP echo $tipseg['itiposegid'] ?>" <?php if ($datos_personales[0]['itiposegid'] == $tipseg['itiposegid']) echo ' selected="selected"'; ?>> <?PHP echo $tipseg['ctiposegdsc'] ?></option>
            <?PHP
            }
            ?>
        </select>
    </div>
    <label class="col-sm-4 col-form-label text-uppercase font-weight-bold">Nro de Seguro:</label>
    <div class="col-sm-4">
        <input type="text" name="cnroseg" id="cnroseg" value="<?php echo trim($datos_personales[0]['cnroseg']); ?>" class="form-control form-control-sm" />
        <br>
    </div>

    <hr>
    <br><br>
    <div class="col-sm-12 alert alert-primary" role="alert">
        <b>INFORME</b>
    </div>
    <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Informe Dirigido a:</label>
    <div class="col-sm-6">
        <input type="text" name="dirigidoa" id="dirigidoa" value="<?php echo trim($datos_personales[0]['dirigidoa']); ?>" class="form-control form-control-sm" />
    </div>
    <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Trabajo de:</label>
    <div class="col-sm-6">
        <input type="text" name="area" id="area" value="<?php echo trim($datos_personales[0]['area']); ?>" class="form-control form-control-sm" />
    </div>

    <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Referencia:</label>
    <div class="col-sm-6">
        <input type="text" name="creferencia" id="creferencia" value="<?php echo trim($datos_personales[0]['creferencia']); ?>" class="form-control form-control-sm" />
    </div>
    <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Reposicion:</label>
    <div class="col-sm-6">
        <input type="text" name="reposicion" id="reposicion" value="<?php echo trim($datos_personales[0]['reposicion']); ?>" class="form-control form-control-sm" />
    </div>

    <label class="col-sm-3 col-form-label text-uppercase font-weight-bold">Ingreso por:</label>
    <div class="col-sm-3">
        <input type="text" name="ingr_por" id="ingr_por" value="<?php echo trim($datos_personales[0]['ingr_por']); ?>" class="form-control form-control-sm" />
    </div>
    <label class="col-sm-9 col-form-label text-uppercase font-weight-bold">Acta de Reposicion Judic:</label>
    <div class="col-sm-9">
        <input type="text" name="acta_repos" id="acta_repos" value="<?php echo trim($datos_personales[0]['acta_repos']); ?>" class="form-control form-control-sm" />
    </div>

    <label class="col-sm-2 col-form-label text-uppercase font-weight-bold">Condicion lab.:</label>
    <div class="col-sm-2">
        <input type="text" name="cond_labor" id="cond_labor" value="<?php echo trim($datos_personales[0]['cond_labor']); ?>" class="form-control form-control-sm" />
    </div>
    <label class="col-sm-3 col-form-label text-uppercase font-weight-bold">Mediante:</label>
    <div class="col-sm-3">
        <input type="text" name="mediante1" id="mediante1" value="<?php echo trim($datos_personales[0]['mediante1']); ?>" class="form-control form-control-sm" />
    </div>
    <label class="col-sm-3 col-form-label text-uppercase font-weight-bold">De Fecha:</label>
    <div class="col-sm-3">
        <input type="date" name="de_fecha" id="de_fecha" value="<?php echo trim($datos_personales[0]['de_fecha']); ?>" class="form-control form-control-sm" />
    </div>
    <label class="col-sm-4 col-form-label text-uppercase font-weight-bold">Cargo en el que se nombro:</label>
    <div class="col-sm-4">
        <input type="text" name="cargo_nombro" id="cargo_nombro" value="<?php echo trim($datos_personales[0]['cargo_nombro']); ?>" class="form-control form-control-sm" />
    </div>

    <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Unidad organica se nombro:</label>
    <div class="col-sm-6">
        <input type="text" name="unidad_org_nomb" id="unidad_org_nomb" value="<?php echo trim($datos_personales[0]['unidad_org_nomb']); ?>" class="form-control form-control-sm" />
    </div>
    <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Dependiente:</label>
    <div class="col-sm-6">
        <input type="text" name="dependiente" id="dependiente" value="<?php echo trim($datos_personales[0]['dependiente']); ?>" class="form-control form-control-sm" />
    </div>

    <label class="col-sm-3 col-form-label text-uppercase font-weight-bold">Regimen Laboral:</label>
    <div class="col-sm-3">
        <input type="text" name="regimen_lab" id="regimen_lab" value="<?php echo trim($datos_personales[0]['regimen_lab']); ?>" class="form-control form-control-sm" />
    </div>
    <label class="col-sm-3 col-form-label text-uppercase font-weight-bold">A partir del:</label>
    <div class="col-sm-3">
        <input type="date" name="apartirdel" id="apartirdel" value="<?php echo trim($datos_personales[0]['apartirdel']); ?>" class="form-control form-control-sm" />
    </div>
    <label class="col-sm-2 col-form-label text-uppercase font-weight-bold">Rotado a:</label>
    <div class="col-sm-2">
        <input type="text" name="rotadoa" id="rotadoa" value="<?php echo trim($datos_personales[0]['rotadoa']); ?>" class="form-control form-control-sm" />
    </div>
    <label class="col-sm-4 col-form-label text-uppercase font-weight-bold">Mediante:</label>
    <div class="col-sm-4">
        <input type="text" name="mediante2" id="mediante2" value="<?php echo trim($datos_personales[0]['mediante2']); ?>" class="form-control form-control-sm" />
    </div>
    <label class="col-sm-4 col-form-label text-uppercase font-weight-bold">Profesion:</label>
    <div class="col-sm-4">
        <input type="text" name="profesion" id="profesion" value="<?php echo trim($datos_personales[0]['profesion']); ?>" class="form-control form-control-sm" />
    </div>
    <label class="col-sm-6 col-form-label text-uppercase font-weight-bold">Comentario:</label>
    <div class="col-sm-6">
        <input type="text" name="comentario" id="comentario" value="<?php echo trim($datos_personales[0]['comentario']); ?>" class="form-control form-control-sm" />
    </div>
    <label class="col-sm-2 col-form-label text-uppercase font-weight-bold"></label>
    <div class="col-sm-2">
        <button onclick="Persona.Ui.btn_personal_accion(<?php echo $indiv_id; ?>,<?php echo $ipersid; ?>,'actualizar',this.form); " type="button" class="btn btn-primary">
            Guardar
        </button>
        <button onclick="Persona.Ui.crearPDFDatos();" type="button" class="btn btn-primary">
            Informe PDF
        </button>
    </div>
        </div>
    </div>
</div>
    
</form>