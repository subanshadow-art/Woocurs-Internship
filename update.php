<?php
require 'config.php';
function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(400); exit('Invalid entry ID.'); }
$stmt = $conn->prepare('SELECT id, name, email, phone FROM entries WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$row) { http_response_code(404); exit('Entry not found.'); }
$name = $row['name']; $email = $row['email']; $phone = $row['phone']; $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    if (strlen($name) < 3 || $email === '' || $phone === '') {
        $error = 'All fields are required!';
    } elseif (strlen($name) > 100 || strlen($email) > 100) {
        $error = 'Name and email must be at most 100 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format!';
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error = 'Phone must be 10 digits!';
    } else {
        $update = $conn->prepare('UPDATE entries SET name = ?, email = ?, phone = ? WHERE id = ?');
        $update->bind_param('sssi', $name, $email, $phone, $id);
        if ($update->execute()) {
            $update->close(); $conn->close();
            header('Location: view.php?status=updated'); exit;
        }
        $error = 'Unable to update entry.';
        $update->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Edit Entry - Library Management System</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"><script src="day5.js" defer></script><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script></head>
<body><nav class="navbar navbar-expand-lg navbar-dark bg-dark"><!-- Mobile-friendly navigation --><div class="container-fluid"><a class="navbar-brand" href="index.html">Library Management System</a><button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteNav" aria-controls="siteNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button><div class="collapse navbar-collapse" id="siteNav"><div class="navbar-nav ms-auto"><a class="nav-link" href="index.html">Home</a><a class="nav-link" href="form.php">Form</a><a class="nav-link" href="about.html">About</a><a class="nav-link" href="contact.html">Contact</a><a class="nav-link" href="view.php">View Entries</a></div></div></nav>
<main class="container mt-5"><h2 class="mb-4">Edit Entry #<?= (int)$id ?></h2>
<?php if ($error !== ''): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form data-entry-form method="POST" action="update.php?id=<?= (int)$id ?>">
<div class="mb-3"><label for="name" class="form-label">Name</label><input id="name" name="name" type="text" minlength="3" class="form-control" maxlength="100" value="<?= e($name) ?>" required></div>
<div class="mb-3"><label for="email" class="form-label">Email</label><input id="email" name="email" type="email" class="form-control" maxlength="100" value="<?= e($email) ?>" required></div>
<div class="mb-3"><label for="phone" class="form-label">Phone</label><input id="phone" name="phone" type="text" inputmode="numeric" class="form-control" pattern="[0-9]{10}" maxlength="10" value="<?= e($phone) ?>" required></div>
<button type="submit" name="update" class="btn btn-success">Update</button> <a href="view.php" class="btn btn-secondary">Back</a>
</form></main></body></html>
