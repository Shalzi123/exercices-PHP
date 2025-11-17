<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   <?php


function pgcd_1($a, $b) {
    $a = abs($a);
    $b = abs($b);

    if ($a == 0) return $b;
    if ($b == 0) return $a;

    while ($a != $b) {
        if ($a > $b) {
            $a -= $b;
        } else {
            $b -= $a;
        }
    }
    return $a;
}


function pgcd_2($a, $b) {
    $a = abs($a);
    $b = abs($b);

    while ($b != 0) {
        $reste = $a % $b;
        $a = $b;
        $b = $reste;
    }
    return $a;
}


function pgcd_3($a, $b) {
    $a = abs($a);
    $b = abs($b);

    if ($b == 0) {
        return $a;
    }
    return pgcd_3($b, $a % $b);
}



echo "<h2>Tests PGCD (48 et 18)</h2>";

echo "pgcd_1(48, 18) = " . pgcd_1(48, 18) . "<br>";
echo "pgcd_2(60, 40) = " . pgcd_2(60, 40) . "<br>";
echo "pgcd_3(15, 3) = " . pgcd_3(15, 3) . "<br>";

?>


</body>
</html>