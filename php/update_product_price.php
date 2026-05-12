<?php

/**
 * ÜRÜN FİYAT GÜNCELLEME SERVİSİ
 * ----------------------------
 * 1. Frontend'den gelen ürün ID'sini ve yeni fiyat bilgisini alır.
 * 2. 'product' tablosunda ilgili ürünü bulur ve fiyatını (Price) günceller.
 * 3. İşlem başarılıysa onay, hata oluşursa hata mesajı döndürür.
 */

header('Content-Type: application/json; charset=utf-8');
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'], $data['price'])) {
    try {
        $stmt = $pdo->prepare("UPDATE product SET Price = ? WHERE ProductID = ?");
        $stmt->execute([$data['price'], $data['id']]);
        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}
