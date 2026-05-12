<?php
header('Content-Type: application/json; charset=utf-8');
require 'db.php'; //db bağlanan php kodunu çağırır.

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM product WHERE ProductID = ?");
        $stmt->execute([$data['id']]);
        
        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
} else {
    // ID gelmediyse sessiz kalma, hata döndür
    echo json_encode(["success" => false, "error" => "ID bilgisi alınamadı."]);
}
?>