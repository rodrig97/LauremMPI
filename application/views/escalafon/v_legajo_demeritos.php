<style>
    /* Fix table head */
    .tableFixHead {
        overflow: auto;
        height: 350px;
    }

    .tableFixHead th {
        position: sticky;
        top: 0;
    }

    /* Just common table stuff. */
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        padding: 8px 16px;
    }

    th {
        background: #eee;
    }
</Style>
<div class="class">


    <div class="wrapper">
        <div class="content-wrapper">
            <div class="page-title">

                <div>
                    <h3>Lista de Demeritos</h3>
                    <ul class="breadcrumb side">
                        <li><i class="fa fa-home fa-lg"></i></li>
                        <li>&nbsp;&nbsp;PERSONAS&nbsp;</li>
                        <li class="active">&nbsp;/&nbsp;&nbsp;DEMERITOS</li>
                    </ul>
                </div>

                <div>
                    <button onclick=" Persona.Ui.btn_demeritos_click_legajo(<?php echo $indiv_id; ?>,<?php echo $ipersid; ?>,'agregar',''); " type="button" class="btn btn-primary">
                        Agregar Demeritos
                    </button>
                </div>



            </div>


        </div>
    </div>

</div>
<div class="tableFixHead">


    <table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-bordered results">
        <thead>
            <tr>
                <th>#</th>
                <th>Sancion</th>
                <th>Documento Resolucion/Carta</th>
                <th>Documento Nro</th>
                <th>Fecha inical</th>
                <th>Fecha Termino</th>
                <th>Operaciones</th>

            </tr>
        </thead>
        <tbody>

            <?PHP

            foreach ($demeritos as $key => $demeri) {
            ?>
                <tr>
                <td>
                    <?php
                        echo ($key + 1)
                        ?>
                    </td>
                    <td>
                        <?PHP
                        echo ($demeri['sancion']);
                        ?>
                    </td>

                    <td>
                        <?PHP
                        echo ($demeri['cdocumentoresolucion']);
                        ?>
                    </td>

                    <td>
                        <?PHP
                        echo ($demeri['cdocumentonro']);
                        ?>
                    </td>

                    <td>
                        <?PHP
                        echo ($demeri['cfecha_ini']);
                        ?>
                    </td>
                    <td>
                        <?PHP
                        echo ($demeri['cfecha_fin']);
                        ?></td>
                    <td>


                        <button class="btn btn-warning btn-sm" type="button" onclick=" Persona.Ui.btn_demeritos_click_legajo(<?php echo $indiv_id; ?>,<?php echo $ipersid; ?>,'actualizar',<?php echo ($demeri['idemeritosid']); ?>); "> <em class="fa fa-pencil"></em></button>
                        <button class="btn btn-danger btn-sm" type="button" onclick=" Persona.Ui.btn_demeritos_click_legajo(<?php echo $indiv_id; ?>,<?php echo $ipersid; ?>,'eliminar',<?php echo ($demeri['idemeritosid']); ?>); "> <em class="fa fa-trash"></em></button>

                    </td>
                </tr>
            <?php
            }
            ?>


        </tbody>
    </table>

</div>