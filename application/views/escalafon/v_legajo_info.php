<style>
  .b-tab {
    padding: 20px;

    display: none;
  }

  .b-tab.active {
    display: block;
  }

  .b-nav-tab {
    display: inline-block;
    line-height: 1;
    padding: 10px;
    color: black;
  }

  .b-nav-tab.active {
    color: #ff4200;
  }
</Style>

<div class="tabs-2">

  <a  data-tab="personal" onclick="tabsFromClasses(event, 'myOtherTabsClass')" class="b-nav-tab myOtherTabsClass active">DATOS PERSONALES</a>
  <a  data-tab="informe" onclick="tabsFromClasses(event, 'myOtherTabsClass')" class="b-nav-tab myOtherTabsClass">INFORME</a>
  <a  data-tab="estudios" onclick="tabsFromClasses(event, 'myOtherTabsClass')" class="b-nav-tab myOtherTabsClass">ESTUDIOS</a>
  <a  data-tab="capacitacion" onclick="tabsFromClasses(event, 'myOtherTabsClass')" class="b-nav-tab myOtherTabsClass">CAPACITACION</a>
  <a  data-tab="experiencia" onclick="tabsFromClasses(event, 'myOtherTabsClass')" class="b-nav-tab myOtherTabsClass">EXPERIENCIA LABORAL</a>
  <a  data-tab="meritos" onclick="tabsFromClasses(event, 'myOtherTabsClass')" class="b-nav-tab myOtherTabsClass">MERITOS</a>
  <a  data-tab="demeritos" onclick="tabsFromClasses(event, 'myOtherTabsClass')" class="b-nav-tab myOtherTabsClass">DEMERITOS</a>
  <a  data-tab="familiar" onclick="tabsFromClasses(event, 'myOtherTabsClass')" class="b-nav-tab myOtherTabsClass">CARGA FAMILIAR</a>


  <div id="personal" class="b-tab myOtherTabsClass-div active">

    <?php
   
    $this->load->view('escalafon/v_legajo_datosPers', array('pers_info' =>  $datos_personales));
    ?>
  </div>

  <div id="informe" class="b-tab myOtherTabsClass-div">
  <?php
   
   $this->load->view('escalafon/v_legajo_cartaPDF', array('pers_info' =>  $informe));
   ?>
  </div>
  <div id="estudios" class="b-tab myOtherTabsClass-div">
  <?php
   $this->load->view('escalafon/v_legajo_estudios', array('indiv_id'=>$indiv_id,'ipersid'=>$ipersid,'estudios' =>  $estudios));
   ?>
  </div>
  <div id="capacitacion" class="b-tab myOtherTabsClass-div">
  <?php
   
   $this->load->view('escalafon/v_legajo_capacitacion', array('indiv_id'=>$indiv_id,'ipersid'=>$ipersid,'capacitacion' =>  $capacitacion));
   ?>
  </div>
  <div id="experiencia" class="b-tab myOtherTabsClass-div">
  <?php
   
   $this->load->view('escalafon/v_legajo_expLaboral', array('experiencia' =>  $experiencia));
   ?>
  </div>
  <div id="meritos" class="b-tab myOtherTabsClass-div">
  <?php
   
   $this->load->view('escalafon/v_legajo_meritos', array('meritos' =>  $meritos));
   ?>
  </div>
  <div id="demeritos" class="b-tab myOtherTabsClass-div">
  <?php
   
   $this->load->view('escalafon/v_legajo_demeritos', array('demeritos' =>  $demeritos));
   ?>
  </div>
  <div id="familiar" class="b-tab myOtherTabsClass-div">
  <?php
   
   $this->load->view('escalafon/v_legajo_cargaFam', array('familiar' =>  $familiar));
   ?>
  </div>

</div>