<?php

// Include the database configuration file
include '../canfig.php';

// Set headers to allow cross-origin resource sharing (CORS)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Decode the JSON data received from the request
    $data = json_decode(file_get_contents("php://input"), true);

    // Check if the 'id' parameter is set in the JSON data
    if (isset($data['id'])) {
        $id = $data['id'];
        $type = $data['type'];

        // SQL query to update the 'campaigns' table
        if ($type == '1') {
            $sql = "UPDATE campaigns SET campaign_status = '1' WHERE c_id = ?";
        } else {
            $sql = "UPDATE campaigns SET campaign_status = '0' WHERE c_id = ?";

        }

        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);


        // Bind the parameter
        $stmt->bind_param("i", $id);

        // Execute the statement
        if ($stmt->execute()) {
            $response = array("success" => true, "message" => "Record updated successfully");
            http_response_code(200);
        } else {
            $response = array("success" => false, "message" => "Error updating record: " . $stmt->error);
            http_response_code(500);
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        $response = array("success" => false, "message" => "Missing parameters");
        http_response_code(400);
    }
} else {
    $response = array("success" => false, "message" => "Invalid request method");
    http_response_code(405);
}

// Close the database connection
$conn->close();

// Encode the response as JSON and send it back
echo json_encode($response);

?>