<?php

// Include the database configuration file
include '../canfig.php';

// Initialize response array
$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Check if the file was uploaded without errors
  if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    // Get the uploaded file information
    $file_name = $_FILES['file']['name'];
    $file_size = $_FILES['file']['size'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_type = $_FILES['file']['type'];

    // Check if the uploaded file is an SVG file
    $allowed_extensions = array('svg');
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
    if (!in_array(strtolower($file_extension), $allowed_extensions)) {
      $response = array("success" => false, "message" => "Only SVG files are allowed");
      http_response_code(400); // Bad Request
      echo json_encode($response);
      exit; // Terminate the script execution
    }

    // Specify the upload directory
    $upload_dir = "uploads/";

    // Generate a unique filename
    $unique_filename = uniqid() . '_' . $file_name;

    // Move the uploaded file to the specified directory with the unique filename
    $upload_path = $upload_dir . $unique_filename;
    if (move_uploaded_file($file_tmp, $upload_path)) {

      // Validate and sanitize the incoming data
      $name = isset($_POST['name']) ? htmlspecialchars(strip_tags($_POST['name'])) : "";
      $country = isset($_POST['country']) ? htmlspecialchars(strip_tags($_POST['country'])) : "";
      $link = isset($_POST['link']) ? htmlspecialchars(strip_tags($_POST['link'])) : "";
      $rating = isset($_POST['rating']) ? htmlspecialchars(strip_tags($_POST['rating'])) : "";
      $description = isset($_POST['description']) ? htmlspecialchars(strip_tags($_POST['description'])) : "";
      $shot_description = isset($_POST['shot_description']) ? htmlspecialchars(strip_tags($_POST['shot_description'])) : "";

      // No duplicate email or phone number found, proceed with insertion
      if (!empty($name) && !empty($country) && !empty($link) && !empty($description) && !empty($shot_description)) {
        // Insert advertiser information into the database
        $query = "INSERT INTO `online_website` (`name`, `country`, `link`, `rating`, `file_path`, `file_size`, `file_type`,`description`,`shot_description`) VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($query);

        // Bind parameters
        $stmt->bind_param("sssssssss", $name, $country, $link, $rating, $upload_path, $file_size, $file_type, $description, $shot_description);

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
      $response = array("success" => false, "message" => "File upload failed");
      http_response_code(500); // Internal Server Error
    }
  } else {
    $response = array("success" => false, "message" => "File upload failed: " . $_FILES['file']['error']); // Include detailed error message
    http_response_code(500); // Internal Server Error
  }
} else {
  $response = array("success" => false, "message" => "Invalid request method");
  http_response_code(405); // Method Not Allowed
}

// Encode the response as JSON and send it back
echo json_encode($response);

?>
