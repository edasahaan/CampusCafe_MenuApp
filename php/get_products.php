<?php
//ürünlerin listesini veritabanından çeker.

header('Content-Type: application/json; charset=utf-8');
require 'db.php'; // Bağlantıyı dahil et

try {
    // Sadece aktif ürünleri seç
    $stmt = $pdo->query("SELECT ProductID, CategoryID, ProductName, Price FROM product WHERE IsActive = 1 ORDER BY CategoryID ASC, ProductName ASC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC); //php diline çevirmek

    echo json_encode($products);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
