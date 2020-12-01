<?php

$dataPoints = array();
$y = 40;
for ($i = 0; $i < 1000; $i++) {
    $y += rand(0, 10) - 5;
    array_push($dataPoints, array("x" => $i, "y" => $y));
}

?>
<!DOCTYPE HTML>
<html>

<head>
    <script>
        window.onload = function() {

            var chart = new CanvasJS.Chart("chartContainer", {
                theme: "light2", // "light1", "light2", "dark1", "dark2"
                animationEnabled: true,
                zoomEnabled: true,
                title: {
                    text: "Try Zooming and Panning"
                },
                data: [{
                    type: "area",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

        }
    </script>
</head>

<body>
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>

</html>


// $datapoints = array('y' => $vpot, "label" => $x);



// foreach ($idx as $id => $value) {
// $dataPoints[$id] = array("y" => $vpot[$id], "label" => $x[$id]);
// }






// $dataPoints = array(
// array("y" => $vpot[0], "label" => $x[0]),
// array("y" => 15, "label" => "Monday"),
// array("y" => 25, "label" => "Tuesday"),
// array("y" => 5, "label" => "Wednesday"),
// array("y" => 10, "label" => "Thursday"),
// array("y" => 0, "label" => "Friday"),
// array("y" => 20, "label" => "Saturday")
// );


// echo ("<br>");
// echo ("<br>");
// var_dump($idx);

// echo ("<br>");
// echo ("<br>");
// print_r($dataPoints);


?>

<!-- 
<!DOCTYPE HTML>
<html>

<head>
    <script>
        window.onload = function() {

            var chart = new CanvasJS.Chart("chartContainer", {
                title: {
                    text: "Grafik Potensial"
                },
                axisY: {
                    title: "V"
                },
                data: [{
                    title: "X",
                    type: "line",
                    dataPoints: <?php //echo json_encode($dataPoints, JSON_NUMERIC_CHECK); 
                                ?>
                }]
            });
            chart.render();

        }
    </script>
</head>

<body>
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>

</html> -->