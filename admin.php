<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}


// Include the database connection
require 'db.php';

// Handle delete request
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM storage WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: admin.php');
    exit();
}

// Handle form submission for create or update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
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

    if ($id) {
        // Update existing record
        $stmt = $pdo->prepare("UPDATE storage SET name = ?, phone = ?, damage = ?, thana = ?, address = ?, zilla = ?, details = ?, proof = ?, date_occurrence = ?, nid_birth_certificate = ?, relation = ?, form_for = ? WHERE id = ?");
        $stmt->execute([$name, $phone, $damage, $thana, $address, $zilla, $details, $proof, $date_occurrence, $nid_birth_certificate, $relation, $form_for, $id]);
    } else {
        // Create new record
        $stmt = $pdo->prepare("INSERT INTO storage (name, phone, damage, thana, address, zilla, details, proof, date_occurrence, nid_birth_certificate, relation, form_for) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $phone, $damage, $thana, $address, $zilla, $details, $proof, $date_occurrence, $nid_birth_certificate, $relation, $form_for]);
    }

    header('Location: admin.php');
    exit();
}

// Fetch records from database
$stmt = $pdo->query("SELECT * FROM storage");
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container { margin: 20px 0; }
        .table th, .table td { vertical-align: middle; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Admin Page</h1>

        <h2 class="my-4">Records</h2>
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Profession</th>
                    <th>Phone</th>
                    <th>Damage</th>
                    <th>Thana</th>
                    <th>Address</th>
                    <th>Zilla</th>
                    <th>Details</th>
                    <th>Proof</th>
                    <th>Date Occurrence</th>
                    <th>NID/Birth Certificate</th>
                    <th>Relation</th>
                    <th>Form For</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['id']); ?></td>
                        <td><?php echo htmlspecialchars($record['name']); ?></td>
                        <td><?php echo htmlspecialchars($record['profession']); ?></td>
                        <td><?php echo htmlspecialchars($record['phone']); ?></td>
                        <td><?php echo htmlspecialchars($record['damage']); ?></td>
                        <td><?php echo htmlspecialchars($record['thana']); ?></td>
                        <td><?php echo htmlspecialchars($record['address']); ?></td>
                        <td><?php echo htmlspecialchars($record['zilla']); ?></td>
                        <td><?php echo htmlspecialchars($record['details']); ?></td>
                        <td>
                            <?php
                            $files = explode(',', $record['proof']);
                            foreach ($files as $file) {
                                $fileUrl = 'uploads/' . $file;
                                $fileExt = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                echo '<div class="mb-2">';
                                if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])) {
                                    echo '<img src="' . $fileUrl . '" alt="Proof" class="img-fluid" style="max-width: 100px; max-height: 100px;">';
                                } elseif (in_array($fileExt, ['mp4', 'webm', 'ogg'])) {
                                    echo '<video width="100" height="100" controls><source src="' . $fileUrl . '" type="video/' . $fileExt . '">Your browser does not support the video tag.</video>';
                                } elseif ($fileExt === 'pdf') {
                                    echo '<a href="' . $fileUrl . '" target="_blank">View PDF</a>';
                                } else {
                                    echo '<a href="' . $fileUrl . '" download>Download ' . htmlspecialchars($file) . '</a>';
                                }
                                echo '</div>';
                            }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($record['date_occurrence']); ?></td>
                        <td><?php echo htmlspecialchars($record['nid_birth_certificate']); ?></td>
                        <td><?php echo htmlspecialchars($record['relation']); ?></td>
                        <td><?php echo htmlspecialchars($record['form_for']); ?></td>
                        <td>
                            <a href="admin.php?action=delete&id=<?php echo $record['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                            <a href="edit.php?id=<?php echo $record['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <hr>
        <h4 class="my-4">Add record</h1>
        <div class="form-container">
            <form action="admin.php" method="POST" class="row g-3">
                <input type="hidden" name="id" value="">
                <div class="col-md-6">
                    <input type="text" name="name" class="form-control" placeholder="Name" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="phone" class="form-control" placeholder="Phone" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="damage" class="form-control" placeholder="Damage" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="thana" class="form-control" placeholder="Thana" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="address" class="form-control" placeholder="Address" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="zilla" class="form-control" placeholder="Zilla" required>
                </div>
                <div class="col-md-12">
                    <textarea name="details" class="form-control" placeholder="Details" rows="4" required></textarea>
                </div>
                <div class="col-md-6">
                    <input type="text" name="proof" class="form-control" placeholder="Proof (comma-separated filenames)" required>
                </div>
                <div class="col-md-6">
                    <input type="date" name="date_occurrence" class="form-control" placeholder="Date of Occurrence" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="nid_birth_certificate" class="form-control" placeholder="NID/Birth Certificate" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="relation" class="form-control" placeholder="Relation" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="form_for" class="form-control" placeholder="Form For" required>
                </div>
                <div class="col-md-12">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
            </form>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
