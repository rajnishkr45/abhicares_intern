<?php
header("Access-Control-Allow-Origin: *");

// 1. Define Static Responses (Keyword -> Response)
$static_responses = [
    "hello" => "Hello! Welcome to Abhicares. How can I help you today?",
    "hi" => "Hi there! Need help with a service?",
    "pricing" => "Our visiting charges start at ₹99. Final costs depend on the service required.",
    "contact" => "You can call our support team at +91-12345-67890 between 9 AM and 6 PM.",
    "refund policy" => "Refunds are processed within 5-7 working days for valid cancellations.",
    "location" => "We currently serve Darbhanga, Madhubani, and Samastipur.",
    "thank you" => "You're welcome! Have a great day ahead.",
    "bye" => "Goodbye! We hope to serve you again soon.",
    "founder" => "Abhicares was founded to make home services easy and accessible for everyone."
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userMessage = trim($_POST['message']);
    $userMessageLower = strtolower($userMessage);

    // 2. Check for Static Match FIRST
    foreach ($static_responses as $keyword => $response) {
        if (strpos($userMessageLower, $keyword) !== false) {
            echo $response;
            exit; // Stop script here, do not call API
        }
    }

    // 3. If No Match, Call Gemini API with Token Limit
    $apiKey = "AIzaSyBnQfcx1ohjc-7BzF_wDPnEaRcz23xQGTg"; // REPLACE THIS
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent?key=" . $apiKey;

    // JSON Payload with maxOutputTokens limited to 200
    $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => "You are a support agent for Abhicares. Answer this query strictly in less than 50 words: " . $userMessage]
                ]
            ]
        ],
        "generationConfig" => [
            "maxOutputTokens" => 200, // Strict limit
            "temperature" => 0.7
        ]
    ];

    $options = [
        "http" => [
            "header" => "Content-type: application/json\r\n",
            "method" => "POST",
            "content" => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        echo "I am currently experiencing high traffic. Please try again later.";
    } else {
        $response = json_decode($result, true);
        if (isset($response['candidates'][0]['content']['parts'][0]['text'])) {
            echo $response['candidates'][0]['content']['parts'][0]['text'];
        } else {
            echo "Sorry, I didn't understand that.";
        }
    }
}
?>