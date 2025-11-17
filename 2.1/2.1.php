<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    
function calcMoy(array $tab) {
    if (count($tab) === 0) {
        return 0; // éviter division par 0
    }

    $somme = 0;

    for ($i = 0; $i < count($tab); $i++) {
        $somme += $tab[$i];
    }

    return $somme / count($tab);
}

echo calcMoy([10, 20, 30]) . "<br>"; 

?>

</body>
</html>