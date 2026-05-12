<?php
//Aktif siparişlerin takibini sağlayan php

session_start();
header('Content-Type: application/json; charset=utf-8');
require 'db.php';

// Güvenlik Duvarı: Sadece giriş yapmış öğrenciler buraya istek atabilir
if (!isset($_SESSION['student_user_id'])) {
    echo json_encode(["success" => false, "error" => "Yetkisiz islem."]);
    exit;
}

session_write_close();

$studentId = $_SESSION['student_user_id'];

try {
    // Öğrencinin en son verdiği 5 siparişi (veya sadece aktif olanları) çekiyoruz
    // ORDER BY OrderID DESC ile en yeniyi en üste alıyoruz
    $query = "SELECT * FROM `order` WHERE StudentID = ? AND Status IN ('pending', 'preparing', 'completed') ORDER BY OrderID DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute([$studentId]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "orders" => $orders]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
