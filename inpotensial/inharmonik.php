<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harmonik</title>
</head>

<body>
    <h1>Memasukkan Parameter Potensial Harmonik </h1>

    <form action="../potensial/harmonik.php" method="post">
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
                <label for="omega">omega</label>
                <input type="text" name="omega" id="omega" value="1">
            </li>
        </ul>

        <button type="submit" name="submit">submit</button>
    </form>
</body>

</html>