<?php

/**
 * KANBAN (SİPARİŞ) DURUM GÜNCELLEME SERVİSİ
 * -------------------------------
 * 1. Sadece giriş yapmış personelin işlem yapmasına izin verir.
 * 2. Birden fazla sipariş ID'sini ve yeni durumu ('Preparing', 'Completed') liste olarak alır.
 * 3. SQL "IN" operatörünü kullanarak tek bir sorguda çok sayıda siparişi günceller.
 * 4. Bu veri, mutfak ekranındaki "Seçilenleri Hazırla" veya "Tamamla" butonlarını besler.
 */

session_start();
header('Content-Type: application/json; charset=utf-8');
require 'db.php';

// Sadece personel yetkisi olanlar güncelleyebilir
if (!isset($_SESSION['personnel_user_id'])) {
    echo json_encode(["success" => false, "error" => "Yetkisiz islem"]);
    exit();
}

// Frontend'den gelen JSON verisini yakala
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['orderIds']) || !isset($data['newStatus'])) {
    echo json_encode(["success" => false, "error" => "Eksik parametre"]);
    exit();
}

$orderIds = $data['orderIds']; // Array
$newStatus = $data['newStatus']; // String (preparing veya completed)

try {
    // Array içindeki id sayısına göre SQL soru işaretleri (?) oluştur. Örn: (?, ?, ?)
    $placeholders = implode(',', array_fill(0, count($orderIds), '?'));

    // UPDATE sorgusunu hazırla
    $query = "UPDATE `order` SET Status = ? WHERE OrderID IN ($placeholders)";
    $stmt = $pdo->prepare($query);

    // İlk parametre yeni durum, sonrakiler ID'ler
    $params = array_merge([$newStatus], $orderIds);
    $stmt->execute($params);

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
