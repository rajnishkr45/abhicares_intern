<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// -------------------------------------------------
// INPUT
// -------------------------------------------------
$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['message']) || trim($input['message']) === "") {
  echo json_encode(['reply' => '']);
  exit;
}

$userMsg = trim($input['message']);
$userName = $input['user_name'] ?? 'Guest';
$userLang = $input['user_lang'] ?? 'English';

$msg = strtolower($userMsg);

// -------------------------------------------------
// MANUAL DATA (NO API)
// -------------------------------------------------

$serviceCities = [
  "Darbhanga",
  "Madhubani",
  "Samastipur"
];

$services = [
  "electrician" => [
    "en" => "⚡ Electrician services include wiring, switch repair, MCB issues and power faults.",
    "hi" => "⚡ इलेक्ट्रीशियन सेवा में वायरिंग, स्विच रिपेयर और पावर फॉल्ट शामिल हैं।"
  ],
  "plumber" => [
    "en" => "🚰 Plumber services include leakage fixing, tap repair and pipe blockage.",
    "hi" => "🚰 प्लंबर सेवा में लीकेज, नल रिपेयर और पाइप जाम शामिल हैं।"
  ],
  "ac" => [
    "en" => "❄️ AC services include installation, gas refill, servicing and repair.",
    "hi" => "❄️ AC सेवा में इंस्टॉलेशन, गैस रिफिल और सर्विसिंग शामिल है।"
  ],
  "beauty" => [
    "en" => "💄 Beauty services include facial, waxing, cleanup and bridal makeup.",
    "hi" => "💄 ब्यूटी सेवाओं में फेशियल, वैक्सिंग और मेकअप शामिल है।"
  ],
  "carpenter" => [
    "en" => "🪚 Carpenter services include furniture repair, door fitting and modular work.",
    "hi" => "🪚 कारपेंटर सेवा में फर्नीचर रिपेयर और दरवाज़ा फिटिंग शामिल है।"
  ]
];

// -------------------------------------------------
// LANGUAGE HELPER
// -------------------------------------------------
function replyText($en, $hi, $lang)
{
  return ($lang === "Hindi") ? $hi : $en;
}

// -------------------------------------------------
// MANUAL RULES (FAST RETURN)
// -------------------------------------------------

// 1️⃣ City Check
foreach ($serviceCities as $city) {
  if (strpos($msg, $city) !== false) {
    echo json_encode([
      'reply' => replyText(
        "✅ Yes, AbhiCares services are available in " . ucfirst($city) . ". How can I help you?",
        "✅ हाँ, AbhiCares की सेवाएँ " . ucfirst($city) . " में उपलब्ध हैं। मैं कैसे मदद कर सकता हूँ?",
        $userLang
      )
    ]);
    exit;
  }
}

// 2️⃣ Service Check
foreach ($services as $key => $text) {
  if (strpos($msg, $key) !== false) {
    echo json_encode([
      'reply' => replyText(
        $text['en'] . "\n\n📅 Would you like to book this service?",
        $text['hi'] . "\n\n📅 क्या आप यह सेवा बुक करना चाहते हैं?",
        $userLang
      )
    ]);
    exit;
  }
}

// 3️⃣ Pricing
if (preg_match("/price|cost|charges|fee/", $msg)) {
  echo json_encode([
    'reply' => replyText(
      "💰 Inspection charge starts from ₹149. Final price depends on the work.",
      "💰 जांच शुल्क ₹149 से शुरू होता है। अंतिम कीमत काम पर निर्भर करेगी।",
      $userLang
    )
  ]);
  exit;
}

// 4️⃣ Timings
if (preg_match("/time|timing|available|hours/", $msg)) {
  echo json_encode([
    'reply' => replyText(
      "⏰ Our services are available from 9 AM to 8 PM, all days.",
      "⏰ हमारी सेवाएँ सुबह 9 बजे से रात 8 बजे तक उपलब्ध हैं।",
      $userLang
    )
  ]);
  exit;
}

// ----------------------- FALLBACK → GEMINI API --------------------------

$apiKey = ""; // KEEP SECRET
$model = "gemini-2.5-flash";
$apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/$model:generateContent?key=$apiKey";

$systemPrompt = "
You are AbhiCares Urban Company virtual assistant.

Rules:
- Reply ONLY in $userLang
- Be polite and professional
- Give short, practical service advice
- Focus on home services

User name: $userName
";

$data = [
  "systemInstruction" => [
    "parts" => [["text" => $systemPrompt]]
  ],
  "contents" => [
    [
      "role" => "user",
      "parts" => [["text" => $userMsg]]
    ]
  ],
  "generationConfig" => [
    "temperature" => 0.7,
    "maxOutputTokens" => 400
  ]
];

$ch = curl_init($apiUrl);
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
  CURLOPT_POSTFIELDS => json_encode($data),
  CURLOPT_TIMEOUT => 40
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
  curl_close($ch);
  echo json_encode([
    'reply' => replyText(
      "Network error. Please try again.",
      "नेटवर्क समस्या है, कृपया बाद में प्रयास करें।",
      $userLang
    )
  ]);
  exit;
}

curl_close($ch);

$res = json_decode($response, true);

$reply = $res['candidates'][0]['content']['parts'][0]['text']
  ?? replyText(
    "Sorry, I couldn’t respond right now.",
    "माफ़ कीजिए, मैं अभी जवाब नहीं दे पा रहा हूँ।",
    $userLang
  );

$reply = str_replace(["**", "\n\n"], ["", "\n"], $reply);

echo json_encode(['reply' => $reply]);
