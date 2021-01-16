<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
  
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>Laurem Recursos Humanos Suite</title>
	<meta name="description" content=""> 
	<meta name="author" content="Ideartic Labs | Ideartic Systems E.I.R.L ">
 
	<meta name="viewport" content="width=device-width,initial-scale=1">
        
        <!-- DOJO STYLE --> 
        
        <link id="themeStyles" rel="stylesheet" href="<?PHP echo $this->resources->url('js',false); ?>Libs/dojo/dijit/themes/<?PHP echo UI_FRONTEND; ?>/<?PHP echo UI_FRONTEND; ?>.css"/>
        <link id="themeStyles_dijit" rel="stylesheet" href="<?PHP echo $this->resources->url('js',false); ?>Libs/dojo/dojox/widget/Dialog/Dialog.css"/>
        <link id="expandocss" rel="stylesheet" href="<?PHP echo $this->resources->url('js',false); ?>Libs/dojo/dojox/layout/resources/ExpandoPane.css"/>
        <link id="grid1css" rel="stylesheet" href="<?PHP echo $this->resources->url('js',false); ?>Libs/dojo/dojox/grid/resources/Grid.css"/>
        <link id="grid2css" rel="stylesheet" href="<?PHP echo $this->resources->url('js',false); ?>Libs/dojo/dojox/grid/resources/claroGrid.css"/>
        <link id="wcalendar" rel="stylesheet" href="<?PHP echo $this->resources->url('js',false); ?>Libs/dojo/dojox/widget/Calendar/Calendar.css"/> 
        
        <link rel="stylesheet" href="<?PHP echo $this->resources->url('js',false); ?>Libs/dojo/dojo/resources/dojo.css"/> 
        <link rel="stylesheet" href="<?PHP echo $this->resources->url('js',false); ?>Libs/dojo/dgrid/css/dgrid.css"/>
        <link rel="stylesheet" href="<?PHP echo $this->resources->url('js',false); ?>Libs/dojo/dgrid/css/skins/<?PHP echo UI_FRONTEND; ?>.css"/> 
 
       
        <!-- APP Source Styles -->
         <?PHP
         
            $this->resources->getCss('ge_base','ge_ui_1','style','superTables'); //,'superTables'
            $this->resources->getJs(array('Libs/modernizr-2.0.min','Libs/respond.min','Libs/superTables')); // ,'Libs/superTables'

            $this->resources->getJs(array('Libs/dojo/dojo/dojo'), array('data-dojo-config' => 'parseOnLoad: true, isDebug: true' ));
            
            // DOJO REQUIRES
            if($_PAGE=='LOGIN')
            {
                 $this->resources->getJs('pk_users/module_requires');
            }
            else{
                  $this->resources->getJs('app/dojo_requires');
                 
                 //,'App/metas','App/objetivos','App/tareas','App/articulacion','main'
            }
            
            // PRINT MENU MAPS
            
        //    $this->system->map_menu();
            
            $this->resources->getJs(array('laugo/request','laugo/helpers','laugo/app','laugo/model','laugo/window','laugo/form', 'laugo/selectchain'));
            $this->resources->getJs(array('global','app/constants'));
            
            if($_PAGE=='LOGIN')
            {
                 $this->resources->getJs('pk_users/login');
                 $this->resources->getJs('pk_users/login_main');
            }
            else
            {
             
                $this->resources->getJs(array('app/sisgedo',
                                              'app/users', 
                                              'app/escalafon/persona',
                                              'app/escalafon/persona_ui', 
                                              'app/planillas/tareas',
                                              'app/planillas/planillas',
                                              'app/planillas/tipoplanilla', 
                                              'app/planillas/exporter',
                                              'app/planillas/variables', 
                                              'app/planillas/conceptos', 
                                              'app/planillas/impresiones',
                                              'app/planillas/trabajadores',
                                              'app/planillas/asistencias',
                                              'app/planillas/estadodia',
                                              'app/planillas/afps',
                                              'app/planillas/tabla_variables_montos', 
                                              'app/planillas/importacionxls',
                                              'app/planillas/catalogos',
                                              'app/planillas/biometrico',
                                              'app/planillas/impuestos',
                                              'app/impuestos/quintacategoria',
                                              'app/planillas/calculos',
                                              'app/planillas/historiallaboral',
                                              'app/planillas/permisos'  ));

                $this->resources->getJs('main');
            }
  
       ?>
 
 
       
</head>