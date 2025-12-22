<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abhicares";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed"]));
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

// --- 1. FETCH BOOKINGS ---
if ($action == 'fetch') {
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $city = isset($_POST['city']) ? $_POST['city'] : '';

    $sql = "SELECT * FROM bookings WHERE 1=1";

    if (!empty($status)) {
        $sql .= " AND booking_status = '$status'";
    }
    if (!empty($city)) {
        $sql .= " AND city LIKE '%$city%'";
    }

    $sql .= " ORDER BY booking_date DESC";

    $result = $conn->query($sql);
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
}

// --- 2. UPDATE STATUS ---
elseif ($action == 'update') {
    $id = $_POST['id'];
    $newStatus = $_POST['new_status'];

    $sql = "UPDATE bookings SET booking_status='$newStatus' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}

$conn->close();
?>