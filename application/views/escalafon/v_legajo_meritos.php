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
                    <h3>Lista de Meritos</h3>
                    <ul class="breadcrumb side">
                        <li><i class="fa fa-home fa-lg"></i></li>
                        <li>&nbsp;&nbsp;MÉRITOS&nbsp;</li>
                        <li class="active">&nbsp;/&nbsp;&nbsp;MÉRITOS</li>
                    </ul>
                </div>

                <div>
                    <button onclick=" Persona.Ui.btn_meritos_click_legajo(<?php echo $indiv_id; ?>,<?php echo $ipersid; ?>,'agregar',''); " type="button" class="btn btn-primary">
                        Agregar Meritos
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

            foreach ($meritos as $key => $meri) {
            ?>
                <tr>
                    <td>
                    <?php
                        echo ($key + 1)
                        ?>
                    </td>
                    <td>
                        <?PHP
                        echo ($meri['ctipomerito']);
                        ?>
                    </td>

                    <td>
                        <?PHP
                        echo ($meri['cdocumentotipo']);
                        ?>
                    </td>

                    <td>
                        <?PHP
                        echo ($meri['cdocumentonro']);
                        ?>
                    </td>
                    <td>
                        <?PHP
                        echo ($meri['cdocumentofecha']);
                        ?>
                    </td>
                    <td>
                        <?PHP
                        echo ($meri['cmotivo']);
                        ?>
                    </td>

                    <td>

                    <button class="btn btn-warning btn-sm" type="button" onclick=" Persona.Ui.btn_meritos_click_legajo(<?php echo $indiv_id; ?>,<?php echo $ipersid; ?>,'actualizar',<?php echo ($meri['imeritosid']); ?>); "> <em class="fa fa-pencil"></em></button>
                    <button class="btn btn-danger btn-sm" type="button" onclick=" Persona.Ui.btn_meritos_click_legajo(<?php echo $indiv_id; ?>,<?php echo $ipersid; ?>,'eliminar',<?php echo ($meri['imeritosid']); ?>); "> <em class="fa fa-trash"></em></button>

                    </td>
                </tr>
            <?php
            }
            ?>


        </tbody>
    </table>

</div>