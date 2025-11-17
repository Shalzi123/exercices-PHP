<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
function my_str_contains(string $haystack, string $needle) {
    $lenHay = strlen($haystack);
    $lenNe = strlen($needle);

    if ($lenNe === 0) {
        return true; 
    }

    
    for ($i = 0; $i < $lenHay; $i++) {
        
        $match = true;

       
        for ($j = 0; $j < $lenNe; $j++) {
            if (!isset($haystack[$i + $j]) || $haystack[$i + $j] !== $needle[$j]) {
                $match = false;
                break;
            }
        }

        if ($match) return true;
    }

    return false;
}

echo my_str_contains("Hello world", "world") ? "Oui" : "Non"; 
echo "<br>";
echo my_str_contains("Hello world", "xyz") ? "Oui" : "Non";    

?>


</body>
</html>