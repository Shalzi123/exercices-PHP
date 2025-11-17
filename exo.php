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



function fooBar() {
    for ($i = 1; $i <= 100; $i++) {
        if ($i % 15 == 0) {
            echo "FooBar<br>";
        } elseif ($i % 3 == 0) {
            echo "Foo<br>";
        } elseif ($i % 5 == 0) {
            echo "Bar<br>";
        } else {
            echo $i . "<br>";
        }
    }
}


function printPattern($n) {
    for ($i = 1; $i <= $n; $i++) {
        for ($j = 1; $j <= $i; $j++) {
            echo $i;
        }
        echo "<br>";
    }
}




echo "<h3>Exercice 1 - School</h3>";
echo schoolLevel(4) . "<br>";  // Ex: maternelle
echo schoolLevel(10) . "<br>"; // Ex: primaire

echo "<h3>Exercice 2 - Foo Bar</h3>";
fooBar();

echo "<h3>Exercice 3 - Double boucle</h3>";
printPattern(5);

?>

</body>
</html>