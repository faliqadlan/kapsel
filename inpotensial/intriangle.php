<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harmonik</title>
</head>

<body>
    <h1>Memasukkan Parameter Potensial Segitiga </h1>

    <form action="../potensial/triangle.php" method="post">
        <ul>
            <li>
                <label for="xmin">xmin</label>
                <input type="text" name="xmin" id="xmin" value="-1">
            </li>
            <br>
            <li>
                <label for="xmax">xmax</label>
                <input type="text" name="xmax" id="xmax" value="1">
            </li>
            <br>
            <li>
                <label for="dx">dx</label>
                <input type="text" name="dx" id="dx" value="0.1">
            </li>
            <br>
            <li>
                <label for="initE">initE</label>
                <input type="text" name="initE" id="initE" value="0">
            </li>

        </ul>
        <ul>
            <li>
                <label for="v0">v0</label>
                <input type="text" name="v0" id="v0" value="0">
            </li>
            <br>
            <li>
                <label for="tinggi">tinggi</label>
                <input type="text" name="tinggi" id="tinggi" value="1">
            </li>
            <br>
            <li>
                <label for="titik_tengah">titik tengah</label>
                <input type="text" name="titik_tengah" id="titik_tengah" value="0">
            </li>
        </ul>

        <button type="submit" name="submit">submit</button>
    </form>
</body>

</html>