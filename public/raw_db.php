<?php
header('Content-Type: application/json');
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=u754986547_toba', 'u754986547_tobau', 'Laketoba_1');
    $stmt = $pdo->query("SELECT * FROM media WHERE filename LIKE '%logo%' ORDER BY id DESC LIMIT 20");
    $logos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'logos' => $logos], JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
