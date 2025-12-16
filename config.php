<?php
$db = new mysqli("localhost", "root", "", "abhicares");


/**
 * Gemini embedding function
 */
function getEmbedding($text, $apiKey) {

    $url = "https://generativelanguage.googleapis.com/v1beta/models/text-embedding-004:embedContent?key=$apiKey";

    $data = [
        "content" => [
            "parts" => [
                ["text" => $text]
            ]
        ]
    ];

    $options = [
        "http" => [
            "method"  => "POST",
            "header"  => "Content-Type: application/json",
            "content" => json_encode($data),
            "ignore_errors" => true
        ]
    ];

    $response = file_get_contents($url, false, stream_context_create($options));

    if ($response === false) {
        return null;
    }

    $json = json_decode($response, true);

    return $json["embedding"]["values"] ?? null;
}

?>
