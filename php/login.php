<?php

/**
 * MERKEZİ GİRİŞ SİSTEMİ (LOGIN ENGINE)
 * -----------------------------------
 * 1. Kullanıcının rolünü (Öğrenci/Personel), numarasını ve şifresini alır.
 * 2. Role göre ilgili tabloyu (student veya personnel) 'users' tablosuyla JOIN yaparak kontrol eder.
 * 3. Şifreyi güvenli bir şekilde (password_verify) doğrular.
 * 4. Giriş başarılıysa, kullanıcının rolüne göre özel Session anahtarları oluşturur.
 * 5. Başarılı girişte frontend'e kullanıcının rolünü döndürür.
 */

session_start(); // Oturumu başlat
header('Content-Type: application/json; charset=utf-8');
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(["success" => false, "error" => "Veri alınamadı."]);
    exit;
}

$role = $data['role']; // 'Student' veya 'Personnel'
$idNo = $data['idNo'];
$password = $data['password'];

try {
    // Role göre sorgulanacak tabloyu ve sütunu dinamik belirliyoruz
    $idColumn = ($role === 'Student') ? 'StudentNo' : 'RegistrationNum';
    $table = ($role === 'Student') ? 'student' : 'personnel';

    // users tablosu ile öğrenci/personel tablosunu birleştirip (JOIN) bilgileri çekiyoruz
    $query = "SELECT u.UserID, u.Password, u.RoleType, t.$idColumn 
              FROM users u 
              JOIN $table t ON u.UserID = t.UserID 
              WHERE t.$idColumn = ? AND u.RoleType = ?";

    $stmt = $pdo->prepare($query);
    $stmt->execute([$idNo, $role]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kullanıcı bulunduysa ve şifre doğrulanırsa
    if ($user && password_verify($password, $user['Password'])) {

        // Rolleri izole ederek Session'a kaydet
        if ($user['RoleType'] === 'Student') {
            $_SESSION['student_user_id'] = $user['UserID'];
            $_SESSION['student_id_no'] = $user[$idColumn];
        } else if ($user['RoleType'] === 'Personnel') {
            $_SESSION['personnel_user_id'] = $user['UserID'];
            $_SESSION['personnel_id_no'] = $user[$idColumn];
        }

        echo json_encode(["success" => true, "role" => $user['RoleType']]);
    } else {
        echo json_encode(["success" => false, "error" => "Hatalı kimlik numarası veya şifre."]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => "Sistem hatası: " . $e->getMessage()]);
}
