<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }
date_default_timezone_set('Asia/Kolkata');
require_once __DIR__ . '/config.php';

$username = trim($_POST['username'] ?? '');
$password = (string)($_POST['password'] ?? '');
$deviceID = trim($_POST['deviceID'] ?? '');

if ($username === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Username and password required']); exit;
}
if ($deviceID === '') {
    echo json_encode(['success' => false, 'message' => 'Device ID required']); exit;
}

try {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();
    if (!$user) { echo json_encode(['success' => false, 'message' => 'User not found']); exit; }

    if (!hash_equals((string)$user['password'], $password)) {
        echo json_encode(['success' => false, 'message' => 'Invalid password']); exit;
    }
    if ($user['status'] !== 'active') {
        echo json_encode(['success' => false, 'message' => 'Your account is not active']); exit;
    }

    $isExpired = false;
    if (!empty($user['validUntil']) && $user['validUntil'] !== '0000-00-00 00:00:00') {
        $isExpired = new DateTime($user['validUntil']) < new DateTime();
        if ($isExpired) {
            $update = $pdo->prepare('UPDATE users SET status = \'inactive\' WHERE username = :username');
            $update->execute([':username' => $username]);
        }
    }
    if ($isExpired) { echo json_encode(['success' => false, 'message' => 'Your account has expired']); exit; }

    $access = $user['access'] ?? '1';
    if ($access === '1') {
        if (!empty($user['deviceID']) && $user['deviceID'] !== $deviceID) {
            echo json_encode(['success' => false, 'message' => 'Account already logged in on another device', 'access' => '1']); exit;
        }
        $update = $pdo->prepare('UPDATE users SET deviceID = :deviceID WHERE username = :username');
        $update->execute([':deviceID' => $deviceID, ':username' => $username]);
    } elseif ($access === 'unlimited') {
        $devices = !empty($user['deviceID']) ? array_filter(explode(',', $user['deviceID'])) : [];
        if (!in_array($deviceID, $devices, true)) {
            $devices[] = $deviceID;
            $devices = array_slice($devices, -20);
            $update = $pdo->prepare('UPDATE users SET deviceID = :deviceID WHERE username = :username');
            $update->execute([':deviceID' => implode(',', $devices), ':username' => $username]);
        }
    }

    echo json_encode([
        'success' => true,
        'user' => [
            'id' => $user['id'], 'username' => $user['username'], 'password' => $user['password'],
            'status' => $user['status'], 'access' => $access, 'validUntil' => $user['validUntil'],
            'registered_date' => $user['registeredDate'], 'deviceID' => $user['deviceID']
        ],
        'message' => 'Login successful', 'access' => $access,
        'access_display' => $access === 'unlimited' ? 'unlimited' : 'single'
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
