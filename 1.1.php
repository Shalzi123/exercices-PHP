<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php

function schoolLevel($age) {
    if ($age < 3) {
        return "creche";
    } elseif ($age < 6) {
        return "maternelle";
    } elseif ($age < 11) {
        return "primaire";
    } elseif ($age < 16) {
        return "college";
    } elseif ($age < 18) {
        return "lycee";
    } else {
        return "";
    }
}



echo "<h3>Exercice 1 - School</h3>";
echo schoolLevel(4) . "<br>";  // Ex: maternelle
echo schoolLevel(10) . "<br>"; // Ex: primaire


?>

</body>
</html>