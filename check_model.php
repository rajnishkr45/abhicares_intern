<?php
// check_models.php
header("Content-Type: text/plain");

// 🔴 Paste your AIza... key here
$apiKey = "AIzaSyAhcovFifvN-2qFpinRv4_-ICx2VgmMfPY"; 

$url = "https://generativelanguage.googleapis.com/v1beta/models?key=$apiKey";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (isset($data['models'])) {
    echo "✅ AVAILABLE MODELS FOR YOUR KEY:\n";
    echo "---------------------------------\n";
    foreach ($data['models'] as $model) {
        // Filter for models that support 'generateContent'
        if (in_array("generateContent", $model['supportedGenerationMethods'])) {
            // Remove "models/" prefix for cleaner reading
            echo str_replace("models/", "", $model['name']) . "\n";
        }
    }
} else {
    echo "❌ ERROR FETCHING MODELS:\n";
    print_r($data);
}
?>