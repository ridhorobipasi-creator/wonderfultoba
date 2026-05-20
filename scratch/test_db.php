<?php
try {
    $pdo = new PDO(
        'mysql:host=srv2045.hstgr.io;port=3306;dbname=u754986547_toba',
        'u754986547_tobau',
        'Laketoba_1',
        [PDO::ATTR_TIMEOUT => 5]
    );
    echo "CONNECTION OK\n";
    $stmt = $pdo->query('SELECT 1');
    echo "QUERY OK\n";
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
