<?php
session_start();
include "config.php";
include "similarity.php";

if (!isset($_SESSION["chat_history"])) {
    $_SESSION["chat_history"] = [];
}

$user = trim($_POST["message"]);

// store user message
$_SESSION["chat_history"][] = ["role"=>"user","text"=>$user];

// build past context (last 5 messages)
$contextText = "";
$recent = array_slice($_SESSION["chat_history"], -5);
foreach ($recent as $msg) {
    $contextText .= strtoupper($msg["role"]).": ".$msg["text"]."\n";
}

// vector embedding
$queryEmbedding = getEmbedding($user, $GEMINI_API_KEY);

// find best reply
$res = $db->query("SELECT * FROM knowledge_base");
$bestScore = 0;
$bestReply = "Sorry, I don't have that information yet.";

while ($row = $res->fetch_assoc()) {
    $score = cosineSimilarity($queryEmbedding, json_decode($row["embedding"], true));
    if ($score > $bestScore) {
        $bestScore = $score;
        $bestReply = $row["reply"];
    }
}

// final prompt
$prompt = "
Previous conversation:
$contextText

Knowledge base:
$bestReply

User question:
$user
";

// call Gemini
$response = file_get_contents(
  "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=$GEMINI_API_KEY",
  false,
  stream_context_create([
    "http" => [
      "method"=>"POST",
      "header"=>"Content-Type: application/json",
      "content"=>json_encode([
        "contents"=>[[ "parts"=>[[ "text"=>$prompt ]] ]]
      ])
    ]
  ])
);

$finalReply = json_decode($response,true)
["candidates"][0]["content"]["parts"][0]["text"];

// store bot reply
$_SESSION["chat_history"][] = ["role"=>"assistant","text"=>$finalReply];

echo $finalReply;
