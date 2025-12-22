<?php
// Allow access from any origin (good for development)
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/plain");

// 1. Database Configuration
$servername = "localhost";
$username = "root";       
$password = "";           
$dbname = "abhicares"; 

// 2. Create Connection
$conn = new mysqli($servername, $username, $password, $dbname);

// 3. Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 4. Process the POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // The frontend sends data as a JSON string inside the 'data' key
    if (isset($_POST['data'])) {
        $data = json_decode($_POST['data'], true);

        // Sanitize inputs to prevent SQL Injection
        $name = $conn->real_escape_string($data['name']);
        $phone = $conn->real_escape_string($data['phone']);
        $language = $conn->real_escape_string($data['language']);
        $service_type = $conn->real_escape_string($data['intent']); // intent = service_type
        $category = $conn->real_escape_string($data['category']);
        $sub_category = $conn->real_escape_string($data['subCategory']); // Note the camelCase mapping
        $city = $conn->real_escape_string($data['city']);
        $address = $conn->real_escape_string($data['address']);

        // Default status
        $status = 'pending';

        // 5. Prepare SQL Statement
        $sql = "INSERT INTO bookings (customer_name, phone, language, service_type, category, sub_category, city, address, booking_status) VALUES ('$name', '$phone', '$language', '$service_type', '$category', '$sub_category', '$city', '$address', '$status'
                )";

        // 6. Execute Query
        if ($conn->query($sql) === TRUE) {
            echo "Success";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "No data received";
    }
} else {
    echo "Invalid Request Method";
}

$conn->close();
?>