<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
     <meta charset="utf-8">

     <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

     <title>Laurem Recursos Humanos Suite</title>
     <meta name="description" content="">
     <meta name="author" content="Ideartic Labs | Ideartic Systems E.I.R.L ">

     <meta name="viewport" content="width=device-width,initial-scale=1">

     <!-- DOJO STYLE -->

     <link id="themeStyles" rel="stylesheet" href="<?PHP echo $this->resources->url('js', false); ?>Libs/dojo/dijit/themes/<?PHP echo UI_FRONTEND; ?>/<?PHP echo UI_FRONTEND; ?>.css" />
     <link id="themeStyles_dijit" rel="stylesheet" href="<?PHP echo $this->resources->url('js', false); ?>Libs/dojo/dojox/widget/Dialog/Dialog.css" />
     <link id="expandocss" rel="stylesheet" href="<?PHP echo $this->resources->url('js', false); ?>Libs/dojo/dojox/layout/resources/ExpandoPane.css" />
     <link id="grid1css" rel="stylesheet" href="<?PHP echo $this->resources->url('js', false); ?>Libs/dojo/dojox/grid/resources/Grid.css" />
     <link id="grid2css" rel="stylesheet" href="<?PHP echo $this->resources->url('js', false); ?>Libs/dojo/dojox/grid/resources/claroGrid.css" />
     <link id="wcalendar" rel="stylesheet" href="<?PHP echo $this->resources->url('js', false); ?>Libs/dojo/dojox/widget/Calendar/Calendar.css" />

     <link rel="stylesheet" href="<?PHP echo $this->resources->url('js', false); ?>Libs/dojo/dojo/resources/dojo.css" />
     <link rel="stylesheet" href="<?PHP echo $this->resources->url('js', false); ?>Libs/dojo/dgrid/css/dgrid.css" />
     <link rel="stylesheet" href="<?PHP echo $this->resources->url('js', false); ?>Libs/dojo/dgrid/css/skins/<?PHP echo UI_FRONTEND; ?>.css" />

     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
     <script src="https://unpkg.com/jquery@2.2.4/dist/jquery.js"></script>
     <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
     <link href="https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css" />
     <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

     <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
     <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

     <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
     <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
     <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

     <script>
  // tabbed interface using pure JS ES5, make multiple tabbed ui:s on one page.

  // make any amount of tabbed interfaces and activate through this function.

  // uses onclick"tabsFromClasses(event, input_class)" where 'event' is the originating link or element and a data-tab attribute on the link corresponding to the 'id' on the tab display div:s. 

  // if the tab links have the class 'myClass', the divs need the class 'myClass-div'. This, together with the corresponding classes, is how we tie links to display containers and distinguish between different tabbed groups on the same page.

  function tabsFromClasses(event, input_class) {

    // get the initiating element (a, button or other) and also the id, 
    // here we are using the attribute 'data-tab', a custom attribute
    var clickedTab = event.currentTarget;
    var clickedDataTabId = clickedTab.getAttribute('data-tab');


    var tabs_nav_elements = document.getElementsByClassName(input_class);

    for (var i = 0; i < tabs_nav_elements.length; i++) {

      // use this to check all found elements in console:
      // console.log(tabs_nav_elements[i]);

      if (tabs_nav_elements[i] === clickedTab) {

        // highlight the active tab
        tabs_nav_elements[i].classList.add('active');

      } else {

        // remove highlighing on the non-clicked tabs.
        tabs_nav_elements[i].classList.remove('active');

      }
    }


    // now get the divs that correspond to the current set of tabs. 
    var tabs_div_elements = document.getElementsByClassName(input_class + '-div');

    for (var i = 0; i < tabs_div_elements.length; i++) {

      // now get the id of the div, which has same id as data-tab on the links.
      var id = tabs_div_elements[i].getAttribute('id');

      // check against the originating tab link element's data-tab value
      if (id === clickedDataTabId) {

        tabs_div_elements[i].classList.add('active'); // show the corresponding div

      } else {

        tabs_div_elements[i].classList.remove('active'); // hide the corresponding div

      }

    }

  }

  // the downside to not searching for siblings of the initial div-classes is the amount of HTML code edits required if you have a lot of elements. The upside is a low amount of included functionality in the JS, it would produce less issues in older browsers.
</script>
     <script>
          var triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'))
          triggerTabList.forEach(function(triggerEl) {
               var tabTrigger = new bootstrap.Tab(triggerEl)

               triggerEl.addEventListener('click', function(event) {
                    event.preventDefault()
                    tabTrigger.show()
               })
          })
     </script>
     <script>
          $('#myModal').on('shown.bs.modal', function() {
               $('#myInput').trigger('focus')
          })
     </script>
     <style>
          .pagination {
               display: inline-block;

          }

          .pagination li {
               color: black;
               float: left;
               padding: 8px 16px;
               text-decoration: none;
          }




          .pagination li a {
               color: black;
               float: left;
               padding: 8px 16px;
               text-decoration: none;
          }

          .pagination li.active {
               background-color: #4CAF50;
               color: white;
          }


          .header_wrap {
               padding: 30px 0;
          }

          .num_rows {
               width: 20%;
               float: left;
          }

          .tb_search {
               width: 20%;
               float: right;
          }

          .pagination-container {
               width: 100%;
               float: left;

          }

          .rows_count {
               width: 20%;
               float: right;
               text-align: right;
               color: #999;
          }
     </style>
     <!-- APP Source Styles -->
     <?PHP

     $this->resources->getCss('ge_base', 'ge_ui_1', 'style', 'superTables'); //,'superTables'
     $this->resources->getJs(array('Libs/modernizr-2.0.min', 'Libs/respond.min', 'Libs/superTables')); // ,'Libs/superTables'

     $this->resources->getJs(array('Libs/dojo/dojo/dojo'), array('data-dojo-config' => 'parseOnLoad: true, isDebug: true'));

     // DOJO REQUIRES
     if ($_PAGE == 'LOGIN') {
          $this->resources->getJs('pk_users/module_requires');
     } else {
          $this->resources->getJs('app/dojo_requires');

          //,'App/metas','App/objetivos','App/tareas','App/articulacion','main'
     }

     // PRINT MENU MAPS

     //    $this->system->map_menu();

     $this->resources->getJs(array('laugo/request', 'laugo/helpers', 'laugo/app', 'laugo/model', 'laugo/window', 'laugo/form', 'laugo/selectchain'));
     $this->resources->getJs(array('global', 'app/constants'));

     if ($_PAGE == 'LOGIN') {
          $this->resources->getJs('pk_users/login');
          $this->resources->getJs('pk_users/login_main');
     } else {

          $this->resources->getJs(array(
               'app/sisgedo',
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
               'app/planillas/permisos'
          ));

          $this->resources->getJs('main');
     }

     ?>



</head>