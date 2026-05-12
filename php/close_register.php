<?php

//gün sonu kapanışı ile tüm orderları ekrandan temizliyouz
session_start();
header('Content-Type: application/json; charset=utf-8');
require 'db.php';

// Güvenlik: Sadece yetkili personel bu işlemi yapabilir
if (!isset($_SESSION['personnel_user_id'])) {
    echo json_encode(["error" => "Yetkisiz işlem. Lütfen giriş yapın."]);
    exit;
}

try {
    // SİLMEK YERİNE GÜNCELLİYORUZ: 
    // Tüm siparişlerin durumunu 'Archived' (Arşivlendi) olarak değiştiriyoruz.
    // Kanban panosu sadece pending, preparing ve completed olanları ekrana bastığı için 
    // Archived olanlar ekrandan temizlenip veritabanında güvenle tutulacak.

    $pdo->exec("UPDATE `order` SET Status = 'Archived'"); //veritabanındaki order tablosuna gider ve tüm siparişlerin durumunu (Status) 'Archived' olarak değiştirir.

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
