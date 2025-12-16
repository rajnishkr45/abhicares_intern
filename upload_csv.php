<?php
set_time_limit(0);
ini_set('max_execution_time', 0);

include "config.php";

if (!isset($_FILES["csv"])) {
    die("No CSV uploaded");
}

$handle = fopen($_FILES["csv"]["tmp_name"], "r");
fgetcsv($handle); // skip header

while (($row = fgetcsv($handle, 1000, ",")) !== false) {

    $tag     = trim($row[0] ?? "");
    $keyword = trim($row[1] ?? "");
    $reply   = trim($row[2] ?? "");

    if (empty($keyword) || strlen($keyword) < 2) {
        continue;
    }

    $embedding = getEmbedding($keyword, $GEMINI_API_KEY);

    if ($embedding === null) {
        echo "❌ Failed: $keyword <br>";
        ob_flush(); flush();
        continue;
    }

    $stmt = $db->prepare(
        "INSERT INTO knowledge_base (tag, keyword, reply, embedding)
         VALUES (?, ?, ?, ?)"
    );

    $jsonEmbedding = json_encode($embedding);
    $stmt->bind_param("ssss", $tag, $keyword, $reply, $jsonEmbedding);
    $stmt->execute();

    echo "✅ Embedded: $keyword <br>";
    ob_flush(); flush();

    usleep(300000); // IMPORTANT: rate limit
}

fclose($handle);

echo "<br><b>CSV Upload Completed</b>";
