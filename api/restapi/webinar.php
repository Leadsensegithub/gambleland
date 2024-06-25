<?php

// Allow requests from any origin
header("Access-Control-Allow-Origin: *");

// Allow specific HTTP methods
header("Access-Control-Allow-Methods: POST, OPTIONS");

// Allow specific headers
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Include the database configuration file
include '../canfig.php';

// Initialize response array
$response = array();

// Retrieve JSON data from the request body
$json_data = file_get_contents('php://input');

// Check if JSON data is not empty
if (!empty($json_data)) {
    // Decode JSON data into associative array
    $data = json_decode($json_data, true);

    // Extract values from the associative array
    $degree = isset($data['degree']) ? htmlspecialchars($data['degree']) : "";
    $email = isset($data['email']) ? htmlspecialchars($data['email']) : "";
    $name = isset($data['name']) ? htmlspecialchars($data['name']) : "";
    $number = isset($data['number']) ? htmlspecialchars($data['number']) : "";

    // Check if required fields are not empty
    if (!empty($name) && !empty($number) && !empty($email) && !empty($degree)) {
        // Insert information into the database
        $query = "INSERT INTO `webinar` (`name`, `contact`, `email`, `degree`) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        // Bind parameters
        $stmt->bind_param("ssss", $name, $number, $email, $degree);

        // Execute statement
        if ($stmt->execute()) {
            $response = array("success" => true, "message" => "Record inserted successfully");
        } else {
            $response = array("success" => false, "message" => "Error inserting record");
            http_response_code(500); // Internal Server Error
        }
    } else {
        $response = array("success" => false, "message" => "Missing required fields");
        http_response_code(400); // Bad Request
    }
} else {
    $response = array("success" => false, "message" => "Invalid JSON data");
    http_response_code(400); // Bad Request
}

// Encode the response as JSON and send it back
echo json_encode($response);

?>
