<?php
$message = '';
$success = false;
$name = $email = $phone = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    if (strlen($name) < 3 || $email === '' || $phone === '') {
        $message = 'All fields are required!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Invalid email format!';
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $message = 'Phone must be 10 digits!';
    } else {
        include 'config.php';
        $stmt = $conn->prepare('INSERT INTO entries (name, email, phone) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $name, $email, $phone);
        if ($stmt->execute()) {
            $message = 'Entry submitted successfully!';
            $success = true;
            $name = $email = $phone = '';
        } else {
            $message = 'Unable to save entry.';
        }
        $stmt->close();
        $conn->close();
    }
}
function e($value) { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Form - Library Management System</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"><script src="day5.js" defer></script><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script></head><body><nav class="navbar navbar-expand-lg navbar-dark bg-dark"><!-- Mobile-friendly navigation --><div class="container-fluid"><a class="navbar-brand" href="index.html">Library Management System</a><button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteNav" aria-controls="siteNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button><div class="collapse navbar-collapse" id="siteNav"><ul class="navbar-nav ms-auto"><li class="nav-item"><a class="nav-link" href="index.html">Home</a></li><li class="nav-item"><a class="nav-link" href="form.php">Form</a></li><li class="nav-item"><a class="nav-link" href="about.html">About</a></li><li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li><li class="nav-item"><a class="nav-link" href="view.php">View Entries</a></li></ul></div></div></nav><main class="container mt-5"><h2 class="mb-4">Add Entry</h2><?php if ($message !== ''): ?><p class="alert <?= $success ? 'alert-success' : 'alert-danger' ?>"><?= e($message) ?></p><?php endif; ?>
<form data-entry-form method="POST" action="form.php">
<div class="mb-3"><label class="form-label" for="name">Name</label><input type="text" name="name" id="name" class="form-control" minlength="3" maxlength="100" placeholder="Enter Name" value="<?= e($name) ?>"></div>
<div class="mb-3"><label class="form-label" for="email">Email</label><input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" value="<?= e($email) ?>"></div>
<div class="mb-3"><label class="form-label" for="phone">Phone</label><input type="text" name="phone" id="phone" class="form-control" maxlength="10" inputmode="numeric" placeholder="Enter Phone" value="<?= e($phone) ?>"></div>
<button type="submit" class="btn btn-success">Submit</button> <button type="reset" class="btn btn-secondary">Clear Form</button>
</form></main><footer class="text-center mt-5">Developed by [Your Name] | 2025</footer></body></html>