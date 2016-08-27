<?php

if (!defined('IN_SPYOGAME')) die("Hacking attempt"); // Pas daccès direct

include("expedition_css.php");
include('header.php');


require_once(FOLDER_EXP . "/subHeaderDateChooser.php");
require_once('functions.php');

$data = readDBuserDet($typeUser, $datedebut, $datefin);
$data = formatUserData($data);
?>

<span class="exp-title">
	<?php echo $titrePeriode ?>
</span>
<br/>
<br/>
<br/>
<br/>
<span class="exp-titleBis">eXpeditions ratées :</span>
<br/>
<br/>
<br/>

<table class="result-tab">
    <thead>
    <tr>
        <?php
        if ($typeUser == 0) {
            ?>
            <td class="c medium-td">Pseudo</td>
            <td style="width: 20px;"></td>
            <?php
        }
        ?>
        <td class="c" style="width: 200px;">Date</td>
        <td class="c medium-td"></td>
    </tr>
    </thead>
    <tbody>
    <?php
    if (count($data['Missed']) != 0 && $data['Missed'] != '') {
        foreach ($data['Missed'] as $date => $resDate) {
            ?>
            <tr class="total-line">
                <?php if ($typeUser == 0) { ?>
                    <th style="medium-td"></th>
                    <td style="width: 20px;"></td>
                <?php } ?>
                <th align=center style="width: 200px;"><?php echo $date ?></th>
                <th align=center style="width: 75px;"><?php echo (string)$resDate['Total']['count'] ?></th>
            </tr>
            <?php
            foreach ($resDate['Details'] as $res) {
                ?>
                <tr>
                    <?php if ($typeUser == 0) {
                        ?>
                        <td class='b' style="width: 75px; font-weight: bold;"><?php echo $res['user'] ?></td>
                        <td style="width: 20px; font-weight: bold;"></td>
                    <?php } ?>

                    <td class='b' align=center
                        style="width: 200px;"><?php echo date('d-m-Y - H:i:s', $res['date']) ?></td>
                    <td class='b' align=center style="width: 75px;">[<?php echo $res['position'] ?>]</td>
                </tr>
            <?php }
        }
    }
    ?>
    </tbody>
</table>
<br/>
<br/>
<br/>
<span class="exp-titleBis">eXpeditions ayant ramené des ressources :</span>
<br/>
<br/>
<br/>
<table class="result-tab">
    <thead>
    <tr>
        <?php
        if ($typeUser == 0) {
            ?>
            <td class="c" style="width: 75px;">Pseudo</td>
            <td style="width: 20px;"></td>
            <?php
        }
        ?>
        <td class="c" style="width: 200px;">Date</td>
        <td class="c" style="width: 75px;">Position</td>
        <td style="width: 20px;"></td>
        <td class="c" style="width: 100px;">Métal</td>
        <td class="c" style="width: 100px;">Cristal</td>
        <td class="c" style="width: 100px;">Deutérium</td>
        <td class="c" style="width: 100px;">Antimatière</td>
    </tr>
    </thead>
    <tbody>
    <?php
    if (count($data['Ressources']) != 0) {
        foreach ($data['Ressources'] as $key => $resDate) {
            ?>
            <tr class="total-line">
                <?php if ($typeUser == 0) { ?>
                    <th class="medium-td"></th>
                    <td style="width: 20px;"></td>
                <?php } ?>
                <th align=center style="width: 200px;"><?php echo $key ?></th>
                <th align=center style="width: 75px;"></th>
                <td style="width: 20px;"></td>
                <th align=center style="width: 100px;"><?php echo formate_number($resDate['Total']['metal']) ?></th>
                <th align=center style="width: 100px;"><?php echo formate_number($resDate['Total']['cristal']) ?></th>
                <th align=center style="width: 100px;"><?php echo formate_number($resDate['Total']['deut']) ?></th>
                <th align=center style="width: 100px;"><?php echo formate_number($resDate['Total']['AM']) ?></th>
            </tr>
            <?php foreach ($resDate['Details'] as $detail) {
                ?>
                <tr>
                    <?php if ($typeUser == 0) { ?>
                        <td class="b medium-td"><?php echo $detail['user'] ?></td>
                        <td style="width: 20px;"></td>
                    <?php } ?>
                    <td class='b' align=center
                        style="width: 200px;"><?php echo date('d-m-Y - H:i:s', $detail['date']) ?></td>
                    <td class='b' align=center style="width: 75px;">[<?php echo $detail['position'] ?>]</td>
                    <td style="width: 20px;"></td>
                    <td class='b' align=center style="width: 100px;"><?php echo formate_number($detail['metal']) ?></td>
                    <td class='b' align=center style="width: 100px;"><?php echo formate_number($detail['cristal']) ?></td>
                    <td class='b' align=center style="width: 100px;"><?php echo formate_number($detail['deut']) ?></td>
                    <td class='b' align=center style="width: 100px;"><?php echo formate_number($detail['AM']) ?></td>
                </tr>
                <?php
            }
        }
    }
    ?>
    </tbody>
</table>
<br/>
<br/>
<br/>
<span class="exp-titleBis">eXpeditions ayant ramené des vaisseaux :
</span>
<br/>
<br/>
<br/>
<table class="result-tab">
    <thead>
    <tr>
        <?php
        if ($typeUser == 0) {
            ?>
            <td class="c" style="width: 75px;">Pseudo</td>
            <td style="width: 20px;"></td>
            <?php
        }
        ?>
        <td class="c" style="width: 200px;">Date</td>
        <td class="c" style="width: 75px;">Position</td>
        <td style="width: 20px;"></td>
        <td class="c" style="width: 30px;">PT</td>
        <td class="c" style="width: 30px;">GT</td>
        <td class="c" style="width: 30px;">CLE</td>
        <td class="c" style="width: 30px;">CLO</td>
        <td class="c" style="width: 30px;">CR</td>
        <td class="c" style="width: 30px;">VB</td>
        <td class="c" style="width: 30px;">VC</td>
        <td class="c" style="width: 30px;">REC</td>
        <td class="c" style="width: 30px;">SE</td>
        <td class="c" style="width: 30px;">BMB</td>
        <td class="c" style="width: 30px;">DST</td>
        <td class="c" style="width: 30px;">TRA</td>
        <td class="c" style="width: 30px;">Unités</td>
    </tr>
    </thead>
    <tbody>
    <?php
    if (count($data['Fleet']) != 0) {
        $keys = array('pt', 'gt', 'cle', 'clo', 'cr', 'vb', 'vc', 'rec', 'se', 'bmb', 'dst', 'tra', 'units');
        foreach ($data['Fleet'] as $date => $resDate) {
            ?>
            <tr class="total-line">
                <?php if ($typeUser == 0) { ?>
                    <th class="medium-td"></th>
                    <td style="width: 20px;"></td>
                <?php } ?>
                <th align=center style="width: 200px;"><?php echo $date ?></th>
                <th align=center style="width: 75px;"></th>
                <td style="width: 20px;"></td>
                <?php
                foreach ($keys as $key) {
                    ?>
                    <th align=center style="width: 100px;"><?php echo $resDate['Total'][$key] ?></th>
                    <?php
                }
                ?>
            </tr>
            <?php foreach ($resDate['Details'] as $detail) {
                ?>
                <tr>
                    <?php
                    if ($typeUser == 0) {
                        ?>
                        <td class='b' style="width: 75px; font-weight: bold;"><?php echo $detail['user'] ?></td>
                        <td style="width: 20px; font-weight: bold;"></td>
                        <?php
                    }
                    ?>
                    <td class='b' align=center
                        style="width: 200px;"><?php echo date('d-m-Y - H:i:s', $detail['date']) ?></td>
                    <td class='b' align=center style="width: 75px;">[<?php echo $detail['position'] ?>]</td>
                    <td style="width: 20px;"></td>
                    <?php
                    foreach ($keys as $key) {
                        ?>
                        <td class='b' align=center style="width: 30px;"><?php echo $detail[$key] ?></td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
        }
    }
    ?>
    </tbody>
</table>
<br/>
<br/>
<br/>
<span class="exp-titleBis">eXpeditions ayant ramené des marchands :</span>
<br/>
<br/>
<br/>
<table class="result-tab">
    <thead>
    <tr>
        <?php
        if ($typeUser == 0) {
            ?>
            <td class="c" style="width: 75px; font-weight: bold;">Pseudo</td>
            <td style="width: 20px; font-weight: bold;"></td>
            <?php
        }
        ?>
        <td class="c" style="width: 200px; font-weight: bold;">Date</td>
        <td class="c" style="width: 75px; font-weight: bold;">Position</td>
    </tr>
    </thead>
    <tbody>
    <?php
    if (count($data['Merchant']) != 0) {
        foreach ($data['Merchant'] as $date => $resDate) {
            ?>
            <tr class="total-line">
                <?php if ($typeUser == 0) { ?>
                    <th style="medium-td"></th>
                    <th style="width: 20px;"></th>
                <?php } ?>
                <th align=center style="width: 200px;"><?php echo $date ?></th>
                <th align=center style="width: 75px;"><?php echo (string)$resDate['Total']['count'] ?></th>
            </tr>
            <?php
            foreach ($resDate['Details'] as $res) {
                ?>
                <tr>
                    <?php if ($typeUser == 0) {
                        ?>
                        <td class='b' style="width: 75px; font-weight: bold;"><?php echo $res['user'] ?></td>
                        <td style="width: 20px; font-weight: bold;"></td>
                    <?php } ?>

                    <td class='b' align=center
                        style="width: 200px;"><?php echo date('d-m-Y - H:i:s', $res['date']) ?></td>
                    <td class='b' align=center style="width: 75px;">[<?php echo $res['position'] ?>]</td>
                </tr>
            <?php }
        }
    }
    ?>
</table>

<br/>
<br/>
<br/>
<span class="exp-titleBis">eXpeditions ayant ramené des objets :</span>
<br/>
<br/>
<br/>
<table class="result-tab">
    <thead>
    <tr>
        <?php
        if ($typeUser == 0) {
            ?>
            <td class="c" style="width: 75px;">Pseudo</td>
            <td style="width: 20px;"></td>
            <?php
        }
        ?>
        <td class="c" style="width: 200px;">Date</td>
        <td class="c" style="width: 75px;">Position</td>
        <td class="c" style="width: 200px">Type</td>
        <td class="c" style="width: 75px">Qualité</td>
    </tr>
    </thead>
    <tbody>
    <?php
    if (count($data['Items']) != 0) {
        foreach ($data['Items'] as $date => $resDate) {
            ?>
            <tr class="total-line">
                <?php if ($typeUser == 0) { ?>
                    <th style="medium-td"></th>
                    <td style="width: 20px;"></td>
                <?php } ?>
                <th align=center style="width: 200px;"><?php echo $date ?></th>
                <th align=center style="width: 75px;"><?php echo (string)$resDate['Total']['count'] ?></th>
                <th  align=center style="width: 200px;"></th>
                <th  align=center style="width: 75px;"></th>
            </tr>
            <?php
            foreach ($resDate['Details'] as $res) {
                ?>
                <tr>
                    <?php if ($typeUser == 0) {
                        ?>
                        <td class='b' style="width: 75px; font-weight: bold;"><?php echo $res['user'] ?></td>
                        <td style="width: 20px; font-weight: bold;"></td>
                    <?php } ?>

                    <td class='b' align=center
                        style="width: 200px;"><?php echo date('d-m-Y - H:i:s', $res['date']) ?></td>
                    <td class='b' align=center style="width: 75px;">[<?php echo $res['position'] ?>]</td>
                    <td class='b' align=center style="width: 75px;"><?php echo $res['type'] ?></td>
                    <td class='b' align=center style="width: 75px;"><?php echo $res['niveau'] ?></td>
                </tr>
            <?php }
        }
    }
    ?>
</table>

<br/>
<br/>
<br/>
<span class="exp-titleBis">eXpeditions ayant subit des attaques :
</span>
<br/>
<br/>
<br/>
<table class="result-tab">
    <thead>
    <tr>
        <?php
        if ($typeUser == 0) {
            ?>
            <td class="c" style="width: 75px;">Pseudo</td>
            <td style="width: 20px;"></td>
            <?php
        }
        ?>
        <td class="c" style="width: 200px;">Date</td>
        <td class="c" style="width: 75px;">Position</td>
        <td style="width: 20px;"></td>
        <td class="c" style="width: 30px;">PT</td>
        <td class="c" style="width: 30px;">GT</td>
        <td class="c" style="width: 30px;">CLE</td>
        <td class="c" style="width: 30px;">CLO</td>
        <td class="c" style="width: 30px;">CR</td>
        <td class="c" style="width: 30px;">VB</td>
        <td class="c" style="width: 30px;">VC</td>
        <td class="c" style="width: 30px;">REC</td>
        <td class="c" style="width: 30px;">SE</td>
        <td class="c" style="width: 30px;">BMB</td>
        <td class="c" style="width: 30px;">DST</td>
        <td class="c" style="width: 30px;">EDLM</td>
        <td class="c" style="width: 30px;">TRA</td>
    </tr>
    </thead>
    <tbody>
    <?php
    if (count($data['Attacks']) != 0) {
        $keys = array('pt', 'gt', 'cle', 'clo', 'cr', 'vb', 'vc', 'rec', 'se', 'bmb', 'dst', 'edlm', 'tra');
        foreach ($data['Attacks'] as $date => $resDate) {
            ?>
            <tr class="total-line">
                <?php if ($typeUser == 0) { ?>
                    <th class="medium-td"></th>
                    <td style="width: 20px;"></td>
                <?php } ?>
                <th align=center style="width: 200px;"><?php echo $date ?></th>
                <th align=center style="width: 75px;"></th>
                <td style="width: 20px;"></td>
                <?php
                foreach ($keys as $key) {
                    ?>
                    <th align=center style="width: 100px;"><?php echo $resDate['Total'][$key] ?></th>
                    <?php
                }
                ?>
            </tr>
            <?php foreach ($resDate['Details'] as $detail) {
                ?>
                <tr>
                    <?php
                    if ($typeUser == 0) {
                        ?>
                        <td class='b' style="width: 75px; font-weight: bold;"><?php echo $detail['user'] ?></td>
                        <td style="width: 20px; font-weight: bold;"></td>
                        <?php
                    }
                    ?>
                    <td class='b' align=center
                        style="width: 200px;"><?php echo date('d-m-Y - H:i:s', $detail['date']) ?></td>
                    <td class='b' align=center style="width: 75px;">[<?php echo $detail['position'] ?>]</td>
                    <td style="width: 20px;"></td>
                    <?php
                    foreach ($keys as $key) {
                        ?>
                        <td class='b' align=center style="width: 30px;"><?php echo $detail[$key] ?></td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
        }
    }
    ?>
    </tbody>
</table>


<script type="text/javascript">
    $(window).load(function () {
        $(".result-tab > tbody > tr").not(".total-line").hide();

        $(function () {
            var $research = $('.result-tab');
            $research.find(".total-line").click(function () {
                $(this).nextUntil(".total-line").fadeToggle(500);
            });
        });
    });
</script>
