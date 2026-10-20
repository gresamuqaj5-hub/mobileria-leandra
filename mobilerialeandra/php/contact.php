<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// DB Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mobilerialeandra');

// Vetëm POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Metodë e pavlefshme.']);
    exit;
}

// Merr të dhënat
$name    = trim(htmlspecialchars($_POST['name'] ?? ''));
$email   = trim(htmlspecialchars($_POST['email'] ?? ''));
$project = trim(htmlspecialchars($_POST['project'] ?? ''));
$message = trim(htmlspecialchars($_POST['message'] ?? ''));
$number  = trim(htmlspecialchars($_POST['number'] ?? ''));

// Validim
$errors = [];
if (empty($name))    $errors[] = 'Emri është i detyrueshëm.';
if (empty($email))   $errors[] = 'Emaili është i detyrueshëm.';
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Emaili nuk është valid.';
if (empty($project)) $errors[] = 'Projekti është i detyrueshëm.';
if (empty($message)) $errors[] = 'Mesazhi është i detyrueshëm.';

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// Ruaj në databazë
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        throw new Exception('Lidhja dështoi: ' . $conn->connect_error);
    }
    $conn->set_charset('utf8mb4');

    $stmt = $conn->prepare(
        'INSERT INTO messages (name, email, number , project, message, created_at) VALUES (?, ?, ?, ?, ?, NOW())'
    );
    $stmt->bind_param('sssss', $name, $email, $number , $project, $message);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo json_encode([
        'success' => true,
        'message' => 'Mesazhi u dërgua me sukses!'
    ]);

} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Ndodhi një gabim. Provoni përsëri.'
    ]);
}