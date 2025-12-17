<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// ------------------ API CONFIG (GOOGLE GEMINI) ------------------

$apiKey = ""; 
$model = "gemini-2.5-flash"; 
$apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/$model:generateContent?key=$apiKey";

// --------------------------- INPUT -------------------------

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['message']) || trim($input['message']) === "") {
  echo json_encode(['reply' => '']);
  exit;
}

$userMsg = trim($input['message']);
$userName = $input['user_name'] ?? 'Guest';
$userLang = $input['user_lang'] ?? 'English';

// ------------------------------- STRONG SYSTEM PROMPT -------------------------------

$systemPrompt = "
You are AbhiCares Urban Company virtual assistant.

STRICT RULES:
- Reply ONLY in this language: $userLang
- Hindi → simple spoken Hindi
- Hinglish → Hindi + English mix
- NEVER reply in English if Hindi selected

Behavior:
- Be polite and professional
- Give short, practical service advice
- Focus on different types of home services such as electrician, plumber , beauty tipcs etc.

User name: $userName
";

// ------------------------------- Gemini separates System Instructions from User Content

$data = [
  "systemInstruction" => [
    "parts" => [
      ["text" => $systemPrompt]
    ]
  ],
  "contents" => [
    [
      "role" => "user",
      "parts" => [
        ["text" => $userMsg]
      ]
    ]
  ],
  "generationConfig" => [
    "temperature" => 0.7,
    "maxOutputTokens" => 500
  ]
];

// ------------------------------- CURL -------------------------------

$ch = curl_init($apiUrl);
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_HTTPHEADER => [
    "Content-Type: application/json"
  ],
  CURLOPT_POSTFIELDS => json_encode($data),
  CURLOPT_TIMEOUT => 40,
  CURLOPT_CONNECTTIMEOUT => 15
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
  curl_close($ch);
  echo json_encode([
    'reply' => ($userLang === 'Hindi')
      ? "नेटवर्क समस्या है, कृपया बाद में प्रयास करें।"
      : "Network error. Please try again."
  ]);
  exit;
}

curl_close($ch);

// ------------------------------- RESPONSE HANDLING -------------------------------

$res = json_decode($response, true);


// Gemini Response Structure: candidates[0] -> content -> parts[0] -> text

if (isset($res['candidates'][0]['content']['parts'][0]['text'])) {
  $reply = $res['candidates'][0]['content']['parts'][0]['text'];
} elseif (isset($res['error']['message'])) {
  // Log actual error for admin, show generic for user
  error_log("Gemini API Error: " . $res['error']['message']);

  $reply = ($userLang === 'Hindi')
    ? "सेवा अस्थायी रूप से उपलब्ध नहीं है।"
    : "Service temporarily unavailable.";
} else {
  // Sometimes safety filters block the response (finishReason: SAFETY)
  if (isset($res['promptFeedback']['blockReason'])) {
    $reply = ($userLang === 'Hindi')
      ? "मैं इस प्रश्न का उत्तर नहीं दे सकता।"
      : "I cannot answer this query.";
  } else {
    $reply = ($userLang === 'Hindi')
      ? "माफ़ कीजिए, मैं अभी जवाब नहीं दे पा रहा हूँ।"
      : "Sorry, I couldn’t respond right now.";
  }
}

$reply = str_replace(["**", "\n\n"], ["", "\n"], $reply);

// ------------------------------- OUTPUT -------------------------------
echo json_encode(['reply' => $reply]);
?>