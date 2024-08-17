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

// Fetch the record to edit
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM storage WHERE id = ?");
    $stmt->execute([$id]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    header('Location: admin.php');
    exit();
}

    // Handle form submission for update
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $damage = $_POST['damage'] ?? '';
        $thana = $_POST['thana'] ?? '';
        $address = $_POST['address'] ?? '';
        $zilla = $_POST['zilla'] ?? '';
        $details = $_POST['details'] ?? '';
        $proof = $_POST['proof'] ?? '';
        $date_occurrence = $_POST['date_occurrence'] ?? '';
        $nid_birth_certificate = $_POST['nid_birth_certificate'] ?? '';
        $relation = $_POST['relation'] ?? '';
        $form_for = $_POST['form_for'] ?? '';

        $stmt = $pdo->prepare("UPDATE storage SET name = ?, phone = ?, damage = ?, thana = ?, address = ?, zilla = ?, details = ?, proof = ?, date_occurrence = ?, nid_birth_certificate = ?, relation = ?, form_for = ? WHERE id = ?");
        $stmt->execute([$name, $phone, $damage, $thana, $address, $zilla, $details, $proof, $date_occurrence, $nid_birth_certificate, $relation, $form_for, $id]);

        header('Location: admin.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <style>
        .form-container { margin: 20px 0; }
        .form-container input, .form-container textarea { width: 100%; margin-bottom: 10px; }
        .form-container input[type="submit"] { width: auto; }
    </style>
</head>
<body>
    <h1>Edit Record</h1>

    <div class="form-container">
        <form action="edit.php?id=<?php echo $id; ?>" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($record['id']); ?>">
            <input type="text" name="name" placeholder="Name" value="<?php echo htmlspecialchars($record['name']); ?>" required>
            <input type="text" name="phone" placeholder="Phone" value="<?php echo htmlspecialchars($record['phone']); ?>" required>
            <input type="text" name="damage" placeholder="Damage" value="<?php echo htmlspecialchars($record['damage']); ?>" required>
            <input type="text" name="thana" placeholder="Thana" value="<?php echo htmlspecialchars($record['thana']); ?>" required>
            <input type="text" name="address" placeholder="Address" value="<?php echo htmlspecialchars($record['address']); ?>" required>
            <input type="text" name="zilla" placeholder="Zilla" value="<?php echo htmlspecialchars($record['zilla']); ?>" required>
            <textarea name="details" placeholder="Details" rows="4" required><?php echo htmlspecialchars($record['details']); ?></textarea>
            <input type="text" name="proof" placeholder="Proof (comma-separated filenames)" value="<?php echo htmlspecialchars($record['proof']); ?>" required>
            <input type="date" name="date_occurrence" placeholder="Date of Occurrence" value="<?php echo htmlspecialchars($record['date_occurrence']); ?>" required>
            <input type="text" name="nid_birth_certificate" placeholder="NID/Birth Certificate" value="<?php echo htmlspecialchars($record['nid_birth_certificate']); ?>" required>
            <input type="text" name="relation" placeholder="Relation" value="<?php echo htmlspecialchars($record['relation']); ?>" required>
            <input type="text" name="form_for" placeholder="Form For" value="<?php echo htmlspecialchars($record['form_for']); ?>" required>
            <input type="submit" value="Update">
        </form>
    </div>

    <a href="admin.php">Back to Admin Page</a>
</body>
</html>
