<?php

// Include the database configuration file
include '../canfig.php';

// Set headers to allow cross-origin resource sharing (CORS)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id']) && isset($data['startdate']) && isset($data['enddate']) && isset($data['Format']) && isset($data['campaigns'])) {
        $startdate = $data['startdate'];
        $id = $data['id'];
        $enddate = $data['enddate'];
        $format = $data['Format'];
        $campaigns = $data['campaigns'];

        $formats = $format;
        $formatsString = implode("','", $formats);
        $campaignsString = implode("','", $campaigns);

        if ($format == [] && $campaigns == []) {
            $sql = "SELECT c_id, c_name, imp, click, conv, createdAt,lead ,dailyBudjet,camp_budget FROM campaigns WHERE adv_id = '$id' AND createdAt >= '$startdate' AND createdAt <= '$enddate'";

        } else if ($campaigns == []) {
            $sql = "SELECT c_id, c_name, imp, click, conv, createdAt, lead,dailyBudjet,camp_budget FROM campaigns WHERE adv_id = '$id' AND createdAt >= '$startdate' AND createdAt <= '$enddate' AND formate IN ('$formatsString');";

        } else {
            $sql = "SELECT c_id, c_name, imp, click, conv, createdAt, lead,dailyBudjet,camp_budget FROM campaigns WHERE adv_id = '$id' AND createdAt >= '$startdate' AND createdAt <= '$enddate' AND formate IN ('$formatsString') AND model IN ('$campaignsString');";
        }
        $result = $conn->query($sql);
        $users = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            $response = array("success" => true, "campaigns" => $users);
            http_response_code(200);
        } else {
            $response = array("success" => true, "message" => "No records found");
            http_response_code(200);
        }
        $result->close();
    } else {
        $response = array("success" => false, "message" => "Missing parameters");
        http_response_code(400);
    }
} else {
    $response = array("success" => false, "message" => "Invalid request method");
    http_response_code(405);
}
$conn->close();
echo json_encode($response);

?>