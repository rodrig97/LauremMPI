<div class="class">


    <div class="wrapper">
        <div class="content-wrapper">
            <div class="page-title">

                <div>
                    <h3>Lista de Meritos</h3>
                    <ul class="breadcrumb side">
                        <li><i class="fa fa-home fa-lg"></i></li>
                        <li>&nbsp;&nbsp;PERSONAS&nbsp;</li>
                        <li class="active">&nbsp;/&nbsp;&nbsp;MERITOS</li>
                    </ul>
                </div>

                <div>
                    <button onclick=" Persona.Ui.btn_estudios_click_legajo(<?php echo $ipersid; ?>,'agregar',''); " type="button" class="btn btn-primary">
                        Agregar Meritos
                    </button>
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
        <!-- <div class="tb_search">
            <input type="text" id="search_input_all" onkeyup="FilterkeyWord_all_table()" placeholder="Buscar.." class="form-control">
        </div> -->
    </div>

    <table cellpadding="0" cellspacing="0" border="0"  class="table table-hover table-bordered results">
        <thead>
            <tr>
                <th>#</th>
                <th>Tipo de Merito</th>
                <th>Documento Tipo</th>
                <th>Documento Nro</th>
                <th>Documento Fecha</th>
                <th>Motivo</th>
                <th>Operaciones</th>
            </tr>
        </thead>
        <tbody>

            <?PHP

            foreach ($estudios as $key => $estud) {
            ?>
                <tr>

                    <td>
                        <?PHP
                        echo ($estud['ctipomerito']);
                        ?>
                    </td>

                    <td>
                        <?PHP
                        echo ($estud['cdocumentotipo']);
                        ?>
                    </td>

                    <td>
                        <?PHP
                        echo ($estud['cdocumentonro']);
                        ?>
                    </td>
                    <td>
                        <?PHP
                        echo ($estud['cdocumentofecha']);
                        ?>
                    </td>
                    <td>
                        <?PHP
                        echo ($estud['cmotivo']);
                        ?>
                    </td>


                        <button class="btn btn-warning btn-sm" type="button" onclick=" Persona.Ui.btn_estudios_click_legajo(<?php echo $ipersid; ?>,'actualizar',<?php echo ($estud['iperstipoestudid']); ?>); "> <em class="fa fa-pencil"></em></button>
                        <button class="btn btn-danger btn-sm" type="button" onclick=" Persona.Ui.btn_estudios_click_legajo(<?php echo $ipersid; ?>,'eliminar',<?php echo ($estud['iperstipoestudid']); ?>); "> <em class="fa fa-trash"></em></button>
                  
                    </td>
                </tr>
            <?php
            }
            ?>


        </tbody>
    </table>

</div>