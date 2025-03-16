<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_name'])) {
    echo json_encode(['success' => false, 'message' => 'Nie jesteś zalogowany!']);
    exit;
}

$host = 'localhost';
$dbName = 'db';
$user = 'user';
$password = 'pass';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT qrp_image, qrp_description, qrp_line, qrp_type, qrp_order, qrp_users_name, qrp_created FROM qrp ORDER BY qrp_id DESC LIMIT 1");
    $lastEntry = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($lastEntry) {
        echo json_encode([
            'success' => true,
            'filePath' => $lastEntry['qrp_image'],
            'description' => $lastEntry['qrp_description'],
            'line' => $lastEntry['qrp_line'],
            'type' => $lastEntry['qrp_type'],
            'orderNumber' => $lastEntry['qrp_order'],
            'userName' => $lastEntry['qrp_users_name'],
            'entryDate' => $lastEntry['qrp_created']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Nie znaleziono żadnych zapisów.']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Błąd bazy danych: ' . $e->getMessage()]);
}
?>
