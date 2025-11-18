<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<?php


$filename = "contact.txt";


if (!file_exists($filename)) {
    die("Erreur : Le fichier contact.txt est introuvable.");
}


$contactsToAdd = ["Alice Dupont", "John Doe", "Jean Martin"];


$existingContacts = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);


foreach ($contactsToAdd as $contact) {
    if (!in_array($contact, $existingContacts)) {

        file_put_contents($filename, $contact . PHP_EOL, FILE_APPEND);
        echo "Contact ajouté : $contact <br>";
    } else {
        echo "Contact déjà présent : $contact <br>";
    }
}

echo "<br>Traitement terminé.";

?>

</body>
</html>