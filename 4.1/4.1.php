<?php

$eleves = [
    ["nom" => "Alice", "notes" => [15, 14, 16]],
    ["nom" => "Bob", "notes" => [12, 10, 11]],
    ["nom" => "Claire", "notes" => [18, 17, 16]]
];

foreach ($eleves as $eleve) {
    $notes = $eleve["notes"];

    
    $somme = 0;
    for ($i = 0; $i < count($notes); $i++) {
        $somme += $notes[$i];
    }
    $moyenne = $somme / count($notes);

    echo $eleve["nom"] . " → moyenne : " . $moyenne . "<br>";
}

?>
