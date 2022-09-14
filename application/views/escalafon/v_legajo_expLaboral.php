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
                    <h3>Lista de Experiencia Laboral</h3>
                    <ul class="breadcrumb side">
                        <li><i class="fa fa-home fa-lg"></i></li>
                        <li>&nbsp;&nbsp;PERSONAS&nbsp;</li>
                        <li class="active">&nbsp;/&nbsp;&nbsp;EXPERIENCIA LABORAL</li>
                    </ul>
                </div>

                <div>
                    <button onclick=" Persona.Ui.btn_laboral_click_legajo(<?php echo $indiv_id; ?>,<?php echo $ipersid; ?>,'agregar',''); " type="button" class="btn btn-primary">
                        Agregar Experiencia
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
                <th>Cargos Desempeñados</th>
                <th>Inicio</th>
                <th>Término</th>
                <th>Operaciones</th>

            </tr>
        </thead>
        <tbody>

            <?PHP

            foreach ($laboral as $key => $lab) {
            ?>
                <tr>
                    <td>
                        <?php
                        echo ($key + 1)
                        ?>
                    </td>
                    <td>
                        <?PHP
                        echo ($lab['ccargos_desempenados']);
                        ?>
                    </td>

                    <td>
                        <?PHP
                        echo ($lab['dfechainicio']);
                        ?>
                    </td>
                    <td> <?PHP
                            echo ($lab['dfechatermino']);
                            ?></td>
                    <td>


                        <button class="btn btn-warning btn-sm" type="button" onclick="Persona.Ui.btn_laboral_click_legajo(<?php echo $indiv_id; ?>,<?php echo $ipersid; ?>,'actualizar',<?php echo ($lab['iexp_laboralid']); ?>); "> <em class="fa fa-pencil"></em></button>
                        <button class="btn btn-danger btn-sm" type="button" onclick="Persona.Ui.btn_laboral_click_legajo(<?php echo $indiv_id; ?>,<?php echo $ipersid; ?>,'eliminar',<?php echo ($lab['iexp_laboralid']); ?>); "> <em class="fa fa-trash"></em></button>

                    </td>
                </tr>
            <?php
            }
            ?>


        </tbody>
    </table>

</div>