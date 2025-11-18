<?php

$filename = __DIR__ . "/table.txt";

if (!file_exists($filename)) {
    die("Erreur : table.txt introuvable.");
}


$lines = file($filename, FILE_IGNORE_NEW_LINES);


$errors = [];


for ($i = 1; $i <= 10; $i++) {


    $row = preg_split('/\s+/', trim($lines[$i]));


    for ($j = 1; $j <= 10; $j++) {


        $value = intval($row[$j]);


        $expected = $i * $j;

      
        if ($value !== $expected) {
            $errors[] = $i . "x" . $j;
        }
    }
}


if (count($errors) === 0) {
    echo "Aucune erreur trouvée.";
} else {
    echo "Les erreurs sont : " . implode(", ", $errors);
}


?>
