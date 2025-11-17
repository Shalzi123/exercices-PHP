<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
function my_strrev(string $str) {
    $len = strlen($str);
    $result = "";

    for ($i = $len - 1; $i >= 0; $i--) {
        if (isset($str[$i])) {
            $result .= $str[$i];
        }
    }

    return $result;
}

echo my_strrev("Bonjour") . "<br>";

?>

</body>
</html>