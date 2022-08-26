<div id="dvViewName" class="dv_view_name">

    <table class="_tablepadding2" border="0">

        <tr>
            <td>

            </td>

            <td>
                Legajo
            </td>
        </tr>
    </table>

</div>


<div class="class">
    <div id="legajoregistro_container" data-dojo-type="dijit.layout.BorderContainer" data-dojo-props='design:"sidebar", liveSplitters:true'>

        <div class="wrapper">
            <div class="content-wrapper">
                <div class="page-title">

                    <div>
                        <h3>Lista de Personas</h3>
                        <ul class="breadcrumb side">
                            <li><i class="fa fa-home fa-lg"></i></li>
                            <li>&nbsp;&nbsp;PERSONAS&nbsp;</li>
                            <li class="active">&nbsp;/&nbsp;&nbsp;ACTIVOS</li>
                        </ul>
                    </div>

                    <!-- <div>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                        Launch demo modal
                    </button>
                </div>-->



                </div>


            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="header_wrap">
        <div class="num_rows">

            <div class="form-group">
                <!--		Show Numbers Of Rows 		-->
                <select class="form-control" name="state" id="maxRows" style="visibility:hidden">


                    <option value="50">50</option>
                    <option value="5000">Show ALL Rows</option>
                </select>

            </div>
        </div>
        <div class="tb_search">
            <input type="text" id="search_input_all" onkeyup="FilterkeyWord_all_table()" placeholder="Buscar.." class="form-control">
        </div>
    </div>

    <table cellpadding="0" cellspacing="0" border="0" id="table-id" class="table table-hover table-bordered results">
        <thead>
            <tr>
                <th>#</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Nombres</th>
                <th>DNI</th>
                <th>Celular</th>
                <th>Correo</th>
                <th>Operaciones</th>

            </tr>
        </thead>
        <tbody>

            <?PHP
          
            foreach ($personas as $key => $indiv) {
            ?>
                <tr>

                    <td>
                        <?PHP
                        echo ($indiv['indiv_appaterno']);
                        ?>
                    </td>

                    <td>
                        <?PHP
                        echo ($indiv['indiv_apmaterno']);
                        ?>
                    </td>
                    <td> <?PHP
                            echo ($indiv['indiv_nombres']);
                            ?></td>
                    <td><?PHP
                        echo ($indiv['indiv_dni']);
                        ?></td>
                    <td><?PHP
                        echo ($indiv['indiv_celular']);
                        ?></td>
                    <td><?PHP
                        echo ($indiv['indiv_email']);
                        ?></td>

                    <td>
                    
 
                        <button class="btn btn-warning btn-sm" type="button"  onclick=" Persona.Ui.btn_tblinfoper_click_legajo(<?php echo ($indiv['indiv_id']); ?>); "> <em class="fa fa-pencil"></em></button>
                    </td>
                </tr>
            <?php
            }
            ?>


        </tbody>
    </table>

    <!--		Start Pagination -->
    <div class='pagination-container'>
        <nav>
            <ul class="pagination">
                <!--	Here the JS Function Will Add the Rows -->
            </ul>
        </nav>
    </div>
    <!-- <div class="rows_count">Showing 11 to 20 of 91 entries</div> -->

</div>