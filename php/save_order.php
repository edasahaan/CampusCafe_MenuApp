<?php

/**
 * SİPARİŞ OLUŞTURMA VE SEPET KAYIT MOTORU
 * --------------------------------------
 * 1. Öğrencinin oturumunu doğrular.
 * 2. TRANSACTION başlatır: Siparişin bir parçası bile hata verirse tüm işlemi iptal eder.
 * 3. Hiyerarşik kayıt yapar: 
 * - Önce 'order' tablosuna ana siparişi,
 * - Sonra 'order_detail' tablosuna sepetteki her bir ürünü,
 * - En son 'order_detail_custom' tablosuna o ürüne ait ek seçimleri (süt, şurup vb.) kaydeder.
 * 4. Başarılı işlem sonunda oluşturulan benzersiz Sipariş ID'sini döndürür.
 */

session_start(); //Oturum yönetimini başlatır. Giriş yapan öğrencinin kim olduğunu anlamamızı sağlar.
header('Content-Type: application/json; charset=utf-8'); //Sunucunun tarayıcıya "Ben sana JSON formatında veri gönderiyorum" demesini sağlar.
require 'db.php'; //Veritabanı bağlantı bilgilerini içeren dosyayı çağırır.

$data = json_decode(file_get_contents('php://input'), true); //JavaScript tarafından gönderilen verileri PHP'nin anlayacağı bir diziye çevirir.

try {
    //Eğer öğrenci giriş yapmamışsa işlemi durdur ve hata mesajı döner.
    if (!isset($_SESSION['student_user_id'])) {
        echo json_encode(["success" => false, "error" => "Geçerli bir öğrenci oturumu bulunamadı."]);
        exit;
    }

    //İşlemleri bir paket yapar. Eğer siparişin bir kısmı bile kaydedilemezse her şeyi geri alır (Rollback).
    $pdo->beginTransaction();

    $studentId = $_SESSION['student_user_id'];

    //Ana siparişi oluştur (Status varsayılan 'Pending')-------------------------------

    //order tablosuna genel sipariş bilgilerini (Öğrenci ID, Tarih, Toplam Fiyat) kaydeder
    $stmt = $pdo->prepare("INSERT INTO `order` (StudentID, OrderDate, TotalPrice, Status) VALUES (?, NOW(), ?, 'Pending')");
    $stmt->execute([$studentId, $data['totalPrice']]);

    //oluşturulan ana siparişin ID'sini (Örn: Sipariş #25) bir değişkene kaydet
    $orderId = $pdo->lastInsertId();

    //Sipariş detaylarını (ürünleri) ekle-----------------------------------------------

    //order_detail tablosuna ürünleri (Sandviç, Kahve vb.) kaydetmek için SQL hazırla.
    $detailStmt = $pdo->prepare("INSERT INTO order_detail (OrderID, ProductID, Quantity, UnitPrice) VALUES (?, ?, ?, ?)");


    // 3. Extraları ekle (order_detail_custom tablon için)------------------------------
    //order_detail_custom tablosuna extraları (Süt, Şurup vb.) kaydetmek için SQL hazırlar.
    $customStmt = $pdo->prepare("INSERT INTO order_detail_custom (DetailID, CustomID) VALUES (?, ?)");


    //Sepetteki her bir ürün için tek tek dönecek bir döngü başlat.
    foreach ($data['items'] as $item) {

        //Sepetteki ürünü (ProductID, Miktar, Fiyat) veritabanına yazar.
        $detailStmt->execute([$orderId, $item['productID'], $item['quantity'], $item['unitPrice']]);

        //Kaydedilen ürün satırının benzersiz ID'sini alır.
        $orderDetailId = $pdo->lastInsertId(); // Kaydedilen ürünün ID'sini al

        // Eğer ürüne ait extralar varsa onları da kaydet
        // Eğer ürüne ait extralar varsa onları da kaydet
        if (isset($item['customizations']) && is_array($item['customizations'])) {
            // JS sadece ID listesi gönderdiği için doğrudan o ID'yi kullanıyoruz
            foreach ($item['customizations'] as $customId) {
                $customStmt->execute([$orderDetailId, $customId]);
            }
        }
    }


    //beginTransaction() işleminin eksizsiz olup olmadığını kontrol eder
    $pdo->commit();
    echo json_encode(["success" => true, "orderID" => $orderId]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(["error" => $e->getMessage()]);
}
