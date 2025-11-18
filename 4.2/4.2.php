<?php

function my_str_contains(string $haystack, string $needle) {
    $lenHay = strlen($haystack);
    $lenNeedle = strlen($needle);

    if ($lenNeedle === 0) return true;

    for ($i = 0; $i < $lenHay; $i++) {
        $match = true;

        for ($j = 0; $j < $lenNeedle; $j++) {
            if (!isset($haystack[$i + $j]) || $haystack[$i + $j] !== $needle[$j]) {
                $match = false;
                break;
            }
        }

        if ($match) return true;
    }

    return false;
}

?>

<?php
echo my_str_contains("hello", "hello world") ? "TRUE" : "FALSE"; echo "<br>";
echo my_str_contains("hello world", "hello") ? "TRUE" : "FALSE"; echo "<br>";
echo my_str_contains("the hello the world", "the w") ? "TRUE" : "FALSE"; echo "<br>";
echo my_str_contains("hello the world", "world") ? "TRUE" : "FALSE"; echo "<br>";
echo my_str_contains("hello the world", "world is big") ? "TRUE" : "FALSE"; echo "<br>";
?>
