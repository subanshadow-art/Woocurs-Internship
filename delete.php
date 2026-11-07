<?php
// Delete only after confirmation on the entries page.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed.');
}
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    http_response_code(400);
    exit('Invalid entry ID.');
}
require 'config.php';
$stmt = $conn->prepare('DELETE FROM entries WHERE id = ?');
$stmt->bind_param('i', $id);
if (!$stmt->execute()) {
    http_response_code(500);
    exit('Unable to delete entry.');
}
$stmt->close();
$conn->close();
header('Location: view.php?status=deleted');
exit;
