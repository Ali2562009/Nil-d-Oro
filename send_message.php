<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $whatsapp = trim($_POST['whatsapp']);
    $message = trim($_POST['message']);

    if ($name && $email && $whatsapp && $message) {
        $stmt = $conn->prepare("INSERT INTO messages (name, email, whatsapp, message, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $name, $email, $whatsapp, $message);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Your message has been sent successfully. We will contact you soon!";
        } else {
            $_SESSION['error'] = "Error sending message. Please try again.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Please fill in all fields.";
    }
}
$conn->close();
header("Location: contact.php");
exit();
