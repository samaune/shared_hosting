<?php
function pluralize($word) {
    // if ($count === 1) {
    //     return $word;
    // }

    // Basic English pluralization rules (simplified)
    if (substr($word, -1) === 'y' && !in_array(substr($word, -2, 1), ['a', 'e', 'i', 'o', 'u'])) {
        return substr($word, 0, -1) . 'ies';
    } elseif (in_array(substr($word, -1), ['s', 'x', 'z']) || substr($word, -2) === 'ch' || substr($word, -2) === 'sh') {
        return $word . 'es';
    } else {
        return $word . 's';
    }
}

// echo pluralize("cat", 2); // cats
// echo "\n";
// echo pluralize("city", 2); // cities
// echo "\n";
// echo pluralize("box", 2); // boxes
// echo "\n";
// echo pluralize("child", 2); // This simple function won't handle "child" -> "children"
?>