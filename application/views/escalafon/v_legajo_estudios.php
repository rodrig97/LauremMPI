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
                    <h3>Lista de Estudios</h3>
                    <ul class="breadcrumb side">
                        <li><i class="fa fa-home fa-lg"></i></li>
                        <li>&nbsp;&nbsp;PERSONAS&nbsp;</li>
                        <li class="active">&nbsp;/&nbsp;&nbsp;ESTUDIOS</li>
                    </ul>
                </div>

                <div align="right">
                    <button onclick=" Persona.Ui.btn_estudios_click_legajo(<?php echo $indiv_id; ?>,<?php echo $ipersid; ?>,'agregar',''); " type="button" class="btn btn-primary">
                        Agregar Estudio
                    </button>
                </div>



            </div>


        </div>
    </div>

</div>
<div class="tableFixHead">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Centro de Estudios</th>
                <th>Inicio</th>
                <th>Término</th>
                <th>Operaciones</th>

            </tr>
        </thead>
        <tbody>

            <?PHP

            foreach ($estudios as $key => $estud) {
            ?>
                <tr>
                    <td>
                        <?php
                        echo ($key + 1)
                        ?>
                    </td>
                    <td>
                        <?PHP
                        echo ($estud['ccentroestudios']);
                        ?>
                    </td>

                    <td>
                        <?PHP
                        echo ($estud['dfechainicio']);
                        ?>
                    </td>
                    <td> <?PHP
                            echo ($estud['dfechatermino']);
                            ?></td>
                    <td>


                        <button class="btn btn-warning btn-sm" type="button" onclick=" Persona.Ui.btn_estudios_click_legajo(<?php echo $indiv_id; ?>,<?php echo $ipersid; ?>,'actualizar',<?php echo ($estud['iperstipoestudid']); ?>); "> <em class="fa fa-pencil"></em></button>
                        <button class="btn btn-danger btn-sm" type="button" onclick=" Persona.Ui.btn_estudios_click_legajo(<?php echo $indiv_id; ?>,<?php echo $ipersid; ?>,'eliminar',<?php echo ($estud['iperstipoestudid']); ?>); "> <em class="fa fa-trash"></em></button>

                    </td>
                </tr>
            <?php
            }
            ?>


        </tbody>
    </table>

</div>