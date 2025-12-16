<?php
include "config.php";

$knowledge = [
  "AICTE feedback system helps evaluate faculty performance",
  "Machine learning uses data to make predictions",
  "Chatbots answer user queries automatically"
];

foreach ($knowledge as $text) {

  $response = file_get_contents("https://api.openai.com/v1/embeddings", false,
    stream_context_create([
      "http" => [
        "method" => "POST",
        "header" => "Content-Type: application/json\r\nAuthorization: Bearer $OPENAI_API_KEY",
        "content" => json_encode([
          "model" => "text-embedding-3-small",
          "input" => $text
        ])
      ]
    ])
  );

  $vector = json_decode($response, true)["data"][0]["embedding"];

  $stmt = $db->prepare("INSERT INTO knowledge_base (content, embedding) VALUES (?, ?)");
  $stmt->bind_param("ss", $text, json_encode($vector));
  $stmt->execute();
}

echo "Knowledge Embedded!";
