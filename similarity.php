<?php
function cosineSimilarity($a, $b) {
    $dot = $magA = $magB = 0;

    for ($i = 0; $i < count($a); $i++) {
        $dot += $a[$i] * $b[$i];
        $magA += $a[$i] ** 2;
        $magB += $b[$i] ** 2;
    }
    return $dot / (sqrt($magA) * sqrt($magB));
}
