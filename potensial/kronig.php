<?php
$xmin   = $_POST["xmin"];
$xmax   = $_POST["xmax"];
$dx     = $_POST["dx"];
$initE  = $_POST["initE"];

$v0     = $_POST["v0"];
$vmax   = $_POST["vmax"];
$lks    = $_POST["lks"];
$lpt    = $_POST["lpt"];


?>

<?php
$x = [];
$vpot = [];
$idx = [];
$efunction = [];
$vtemp = [];
$iter = 0;
$nloop = 10;

$leb    = $xmax - $xmin;
$ngrid  = $leb / $dx;
function singleEigen($vtemp, $potential_function = "harmonicPotential")
{
    global $xmin;
    global $dx;
    global $ngrid;
    global $v0;
    global $vpot;
    global $x;
    global $idx;
    for ($i = 0; $i < $ngrid; $i++) {
        $x[$i] = $xmin + ($i)  * $dx;

        $vpot[$i] = $potential_function($x[$i], $v0);
        $idx[$i] = $i;
    }
    //var_dump($vpot);

    eigenState();
}

// var_dump($vpot);

function eigenState()
{
    global $x;
    global $ngrid;
    global $efunction;
    global $vpot;
    global $initE;
    global $iter;
    global $dx;
    global $vtemp;

    $initE = $initE + 1e-16;

    $x_tart = $x[0];
    $x_end  = $x[$ngrid - 1];
    if ($x_tart < 0) {
        for ($i = 0; $i < $ngrid; $i++) {
            $efunction[$i] = (sin($x[$i]) + cos($x[$i]));
        }
    } else {
        for ($i = 0; $i < $ngrid; $i++) {
            $efunction[$i] = 1 + $x[$i] / $x_end;
        }
    }

    $vharm = $vpot[$ngrid - 1];
    $estart = $initE;
    $eps = 1e-5;
    if ($estart > 0) $eps = 1e-5;
    if ($vharm > 1) $eps = 1e-8;
    $energy = $initE;
    $isig = 1;
    if ($initE < 0) $isig = -1;
    do {
        $iter += $iter;
        invers();
        $sum = 0;
        for ($i = 0; $i < $ngrid; $i++) {
            $sum = $sum + $efunction[$i] * $efunction[$i];
        }
        $sum = sqrt($sum * $dx);
        for ($i = 1; $i < $ngrid; $i++) {
            $efunction[$i] = $efunction[$i] / $sum;
        }
        if ($x_tart > 0) hamilton();
        else hamilton5p();
        //var_dump(global $vtemp);
        $sum = 0;
        for ($i = 1; $i < $ngrid - 1; $i++) {
            $sum = $sum + $vtemp[$i] * $efunction[$i];
        }
        $energy_new = $sum * $dx;
        $delta = abs(($energy_new - $energy) / $energy);

        $energyN = $energy_new;
        if ($iter < 1) $energyN = $initE;
        $energy = $energyN;
    } while ($delta <= $eps);

    $initE = $energy_new;
    $vtemp = [];
    $rsign = 1;
    if ($x < 1e-8) $rsign = ($efunction[1] <=> 0);
    $ampl_max = 1;
    if ($vharm <= 1) {
        if ($estart > 0 && $initE > 0) {
            $ampl_max = 0;
            $inode = 0;
            $wb = $efunction[$ngrid - 1];
            $i = $ngrid;
            while ($inode < 10) {
                $i = $i - 1;
                $wf = $efunction[$i];
                if ($i < 10) break;
                if ($wf * $wb < 0) $inode = $inode + 1;
                $abswf = abs($wf);
                if ($abswf > $ampl_max) $ampl_max = $abswf;
                $wb = $wf;
            }
        }
    }
    $ampl_max = $ampl_max * $rsign;
    for ($i = 0; $i < $ngrid; $i++) {
        $efunction[$i] = $efunction[$i] / $ampl_max;
    }
    //var_dump($efunction);
}

function hamilton()
{
    global $dx;
    global $vtemp;
    global $vpot;
    global $efunction;
    global $ngrid;

    $dtr = 1 / ($dx * $dx) / 2;
    $a = -$dtr;
    $b = 2 * $dtr;
    $c = -$dtr;
    $vtemp[0] = ($b + $vpot[0]) * $efunction[0] + $c * $efunction[1];
    for ($i = 1; $i < $ngrid; $i++) {
        $vtemp[$i] = ($b + $vpot[$i]) * $efunction[$i] + $c * ($efunction[$i - 1] + $efunction[$i + 1]);
    }
    $vtemp[$ngrid] = ($b + $vpot[$ngrid]) * $efunction[$ngrid] + $a * $efunction[$ngrid - 1];
}
function hamilton5p()
{
    global $dx;
    global $vpot;
    global $efunction;
    global $ngrid;
    global $vtemp;

    $dtr = 1 / ($dx * $dx) / 24;
    $a = $dtr;
    $b = 30 * $dtr;
    $c = -16 * $dtr;
    $vtemp = [];
    $i = 0;
    $vtemp[$i] = ($b + $vpot[$i]) * $efunction[$i]
        + $c * $efunction[$i + 1]
        + $a * $efunction[$i + 2];
    $i = 1;
    $vtemp[$i] = ($b + $vpot[$i]) * $efunction[$i]
        + $c * ($efunction[$i - 1] + $efunction[$i + 1])
        + $a * $efunction[$i + 2];
    for ($i = 2; $i < $ngrid - 3; $i++) {
        $vtemp[$i] = ($b + $vpot[$i]) * $efunction[$i]
            + $c * ($efunction[$i - 1] + $efunction[$i + 1])
            + $a * ($efunction[$i - 2] + $efunction[$i + 2]);
    }
    $i = $ngrid - 3;
    $vtemp[$i] = ($b + $vpot[$i]) * $efunction[$i]
        + $c * ($efunction[$i - 1] + $efunction[$i + 1])
        + $a * $efunction[$i - 2];
    $i = $ngrid - 2;
    $vtemp[$i] = ($b + $vpot[$i]) * $efunction[$i]
        + $c * $efunction[$i + 1]
        + $a * $efunction[$i - 2];
}

function invers()
{
    global $dx;
    global $ngrid;
    global $efunction;
    global $energy;
    global $vpot;
    global $nloop;

    $dtr = 1 / ($dx * $dx) / 2;
    $a = -$dtr;
    $b = [];
    $bb = 2 * $dtr;
    $c = -$dtr;
    for ($k = 0; $k < $nloop; $k++) {
        for ($i = 0; $i < $ngrid; $i++) {
            $b[$i] = ($bb + $vpot[$i] - $energy);
        }
        $temp = $b[0];
        $efunction[0] = $efunction[0] / $temp;
        for ($j = 1; $j < $ngrid; $j++) {
            $temp1 = $b[$j];
            $b[$j] = $c / $temp;
            $temp = $temp1 - $a * $b[$j];
            $efunction[$j] = ($efunction[$j] - $a * $efunction[$j - 1]) / $temp;
        }
        for ($j = $ngrid - 2; $j > 1; $j--) {
            $efunction[$j] = $efunction[$j] - $b[$j + 1] * $efunction[$j + 1];
        }
    }
}
// foreach ($x as $x1) {
//     echo $x1;
// }

// var_dump($x);

// echo ("<br>");
// echo ("<br>");

// var_dump($vpot);
function harmonicPotential($x, $v0 = 0)
{
    //$xp = [];
    global $xmin;
    global $leb;
    global $om;
    global $v0;
    $xp = $x - $xmin - $leb / 2;
    $pot = (0.5 * $om * $xp * $xp) + $v0;
    return $pot;
}

function kronigPotential($x, $v0 = 0)
{
    //$xp = [];
    global $xmin;
    global $leb;
    global $v0;
    global $vmax;
    global $lks;
    global $lpt;
    $pot = [];

    $xp = $x - $xmin;
    // if ($xp >= 0 && fmod($xp, $lks) < ($lks / 1.4))
    //if ($xp < $lks) 
    if (fmod($xp, $lpt) < ($lks)) {
        $pot = $vmax;
        //echo $xp;
        // echo "<br>";
        // echo "<br>";
        // echo "<br>";
        // echo "<br>";
        // echo "<br>";
        // echo fmod($xp, $lpt);
        //echo "<br>";
        //echo $lpt;
        //return $pot;
    } else {
        $pot = $v0;
        //return $pot;
    }
    return $pot;
    var_dump($pot);
}


?>

<?php

// $datapoints = array('y' => $vpot, "label" => $x);
// function getEfunction(): array
// {
//     global $efunction;

//     return $efunction;
// }

//getEfunction();

singleEigen($vtemp, $potential_function = "kronigPotential");

function getEnergy()
{
    global $initE;

    return $initE;
}

// getEnergy();
// echo ($initE);




foreach ($idx as $id  => $value) {
    $dataPoints1[$id] = array("y" => $efunction[$id], "label" => $x[$id]);
}

foreach ($idx as $id  => $value) {
    $dataPoints2[$id] = array("y" => $vpot[$id], "label" => $x[$id]);
}
// var_dump($dataPoints1);

// echo "<br>";
// echo "<br>";
// var_dump($dataPoints2);
?>
<!DOCTYPE HTML>
<html>

<head>
    <div id="chartContainer1" style="width: 45%; height: 300px;display: inline-block;"></div>
    <br>
    <div id="chartContainer2" style="width: 45%; height: 300px;display: inline-block;"></div>
    <script>
        window.onload = function() {
            var chart1 = new CanvasJS.Chart("chartContainer1", {
                title: {
                    text: "Grafik Energi"
                },
                axisY: {
                    title: "E"
                },
                data: [{
                    title: "X",
                    type: "line",
                    dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart1.render();

            var chart2 = new CanvasJS.Chart("chartContainer2", {
                title: {
                    text: "Grafik Potensial"
                },
                axisY: {
                    title: "V"
                },
                data: [{
                    title: "X",
                    type: "line",
                    dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart2.render();

        }
    </script>
</head>

<body>
    <ul>
        <li>
            <?php
            getEnergy();
            echo ("eigen energi = $initE");
            ?>
        </li>
    </ul>
    <div id="chartContainer" style="height: 370px; width: 100%;">

    </div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

</body>

</html>