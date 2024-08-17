<?php
// Database connection parameters
$host = 'localhost';
$db = 'info';
$user = 'root';
$pass = 'root';

// Create a PDO instance for MySQL database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'failed', 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// Function to handle file uploads
function handleFileUploads($uploadDir) {
    $uploadedFiles = [];
    if (isset($_FILES['proof']) && is_array($_FILES['proof']['name'])) {
        foreach ($_FILES['proof']['name'] as $key => $name) {
            if ($_FILES['proof']['error'][$key] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['proof']['tmp_name'][$key];

                // Generate a unique filename with a timestamp
                $uniqueName = uniqid() . '-' . basename($name);
                $destination = $uploadDir . $uniqueName;

                if (move_uploaded_file($tmpName, $destination)) {
                    $uploadedFiles[] = $uniqueName; // Store the unique filename
                }
            }
        }
    }
    return $uploadedFiles;
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {

        // Retrieve form data
        //relation 
        //relation-other 
        //nid-birth 
        //date 

        $form_for = $_POST['form_for'] ?? '';
        $relation = $_POST['relation'] ?? '';
        $nid_birth_certificate = $_POST['nid_birth_certificate'] ?? '';
        $date_occurrence = $_POST['date_occurrence'] ?? '';
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $damage = $_POST['damage'] ?? '';
        $thana = $_POST['thana'] ?? '';
        $address = $_POST['address'] ?? '';
        $zilla = $_POST['zilla'] ?? '';
        $details = $_POST['details'] ?? '';

        // Handle file uploads
        $uploadDir = __DIR__ . '/uploads/'; // Use absolute path
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $uploadedFiles = handleFileUploads($uploadDir);

        // Join filenames into a comma-separated string
        $proof = implode(',', $uploadedFiles);

        // Insert data into database
        $stmt = $pdo->prepare("INSERT INTO storage (name, phone, damage, thana, address, zilla, details, proof, date_occurrence, nid_birth_certificate, relation, form_for) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ,?)");
        $stmt->execute([$name, $phone, $damage, $thana, $address, $zilla, $details, $proof, $date_occurrence, $nid_birth_certificate, $relation, $form_for]);

        // Return JSON response indicating success
        echo json_encode(["status" => "success", "message" => "Data submitted successfully."]);
    } catch (Exception $e) {
        // Return JSON response indicating failure
        echo json_encode(["status" => "failed", "message" => $e->getMessage()]);
    }
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode(["status" => "failed", "message" => "Method not allowed."]);
}
