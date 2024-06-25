<?php

// Allow requests from any origin
header("Access-Control-Allow-Origin: *");

// Allow GET requests from any origin with the following headers
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: *");

// Include the database configuration file
include '../canfig.php';

// Initialize response array
$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Check if 'type' parameter exists in the request
  if (isset($_GET['type'])) {
    $type = $_GET['type'];

    // Sanitize the input if necessary

    // Select records based on the type parameter
    if ($type == '1') {
      $query = "SELECT * FROM `online_website`  LIMIT 3";
    } else {
      $query = "SELECT * FROM `online_website`";
    }

    // Execute the query
    $result = $conn->query($query);

    // Check if there are any records
    if ($result->num_rows > 0) {
      // Initialize an array to store the retrieved records
      $records = array();

      // Fetch each row from the result set
      while ($row = $result->fetch_assoc()) {
        // Append each row to the records array
        $records[] = $row;
      }

      // Set success message and records in response
      $response = array("success" => true, "message" => "Records retrieved successfully", "data" => $records);
    } else {
      // Set message for no records found
      $response = array("success" => true, "message" => "No records found", "data" => array());
    }
  } else {
    // Set message if 'type' parameter is missing
    $response = array("success" => false, "message" => "'type' parameter is missing in the request");
    http_response_code(400); // Bad Request
  }
} else {
  // Set message for invalid request method
  $response = array("success" => false, "message" => "Invalid request method");
  http_response_code(405); // Method Not Allowed
}

// Encode the response as JSON and send it back
echo json_encode($response);

?>