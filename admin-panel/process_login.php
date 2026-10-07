<?php
/**
 * Process Login - Sets PHP Session after Firebase success
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['loggedIn']) && $data['loggedIn'] === true && isset($data['phone'])) {
    $_SESSION['user_logged_in'] = true;
    $_SESSION['user_phone'] = $data['phone'];

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
}
?>
