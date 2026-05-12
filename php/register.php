<?php

/**
 * MERKEZİ KAYIT SİSTEMİ (REGISTRATION ENGINE)
 * -----------------------------------------
 * 1. Kullanıcı verilerini (Email, Şifre, Rol, ID) JSON olarak alır.
 * 2. Şifreyi 'password_hash' ile geri dönüştürülemez şekilde şifreler (Güvenlik).
 * 3. Transaction (İşlem) başlatır: Eğer iki tabloya birden kayıt yapılamazsa işlemi geri alır.
 * 4. Önce 'users' tablosuna ana veriyi, ardından rolüne göre 'student' veya 'personnel' tablosuna detay veriyi ekler.
 * 5. İşlem başarılıysa veritabanına kalıcı olarak işler (Commit).
 */

header('Content-Type: application/json; charset=utf-8');
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(["error" => "Veri alınamadı."]);
    exit;
}

$email = $data['email'];
$password = password_hash($data['password'], PASSWORD_DEFAULT); // Şifreyi güvenli sakla
$role = $data['role']; // 'Student' veya 'Personnel'
$idNo = $data['idNo']; // Öğrenci No veya Sicil No

try {
    $pdo->beginTransaction();

    // 1. Users tablosuna ekle
    $stmt = $pdo->prepare("INSERT INTO users (RoleType, Email, Password) VALUES (?, ?, ?)");
    $stmt->execute([$role, $email, $password]);
    $lastId = $pdo->lastInsertId();

    // 2. Role göre ilgili tabloya ekle
    if ($role === 'Student') {
        $stmt2 = $pdo->prepare("INSERT INTO student (UserID, StudentNo) VALUES (?, ?)");
        $stmt2->execute([$lastId, $idNo]);
    } else {
        $stmt2 = $pdo->prepare("INSERT INTO personnel (UserID, RegistrationNum) VALUES (?, ?)");
        $stmt2->execute([$lastId, $idNo]);
    }

    $pdo->commit();
    echo json_encode(["success" => true, "message" => "Kayıt başarılı."]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(["error" => "Kayıt hatası: " . $e->getMessage()]);
}
