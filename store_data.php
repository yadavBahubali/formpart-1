<?php
// Include database connection configuration
include_once './assets/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the selected data from the POST request
    $countryName = $_POST['countryName'];
    $stateName = $_POST['stateName'];
    $cityName = $_POST['cityName'];
    $pincode = $_POST['pincode'];

    // Insert the selected data into the "data_c" table
    $query = "INSERT INTO data_c (country_name, state_name, city_name, pincode) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);

    // Check if the prepare statement was successful
    if ($stmt) {
        $stmt->bind_param('ssss', $countryName, $stateName, $cityName, $pincode);
        $stmt->execute();

        // Check if the insertion was successful
        if ($stmt->affected_rows > 0) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false];
        }

        $stmt->close();
    } else {
        // Handle the case where the prepare statement fails
        $response = ['success' => false];
    }

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Handle invalid requests (e.g., GET requests)
    http_response_code(400); // Bad Request
}
?>