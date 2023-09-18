<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $country = $_POST["country"];
    $state = $_POST["state"];
    $city = $_POST["city"];
    $pincode = $_POST["pincode"];

    // Create an associative array to represent the form data
    $formData = array(
        "Country" => $country,
        "State" => $state,
        "City" => $city,
        "Pincode" => $pincode,
    );

    // Load existing data (if any)
    $existingData = json_decode(file_get_contents("data.json"), true);

    // Add the new form data to the existing data array
    $existingData[] = $formData;

    // Encode the data as JSON and save it back to the data.json file
    file_put_contents("data.json", json_encode($existingData));

    // Send a response to the client
    $response = array("message" => "Data successfully stored.");
    echo json_encode($response);
} else {
    // Handle other HTTP methods or direct access to the file
    http_response_code(405); // Method Not Allowed
}
?>