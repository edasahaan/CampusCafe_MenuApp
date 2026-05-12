<?php
$host = '127.0.0.1'; //veritabanının adresini (localhost) tanıtır
$db = 'cafeteriaproject_db'; //veritabanının adı
$user = 'root'; // XAMPP varsayılan genelde root'tur
$pass = '';     //XAMPP  varsayılan şifre genelde boştur

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass); //veritabanını pdo değişkenine attık. charset=utf8mb4 türkçe karakterlerin okunabilirliği içindir

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Hata modunu exception olarak ayarla
} catch (\PDOException $e) {

    die(json_encode(["error" => "Veritabanı bağlantı hatası: " . $e->getMessage()])); //Programı anında durdurur (die) ve JavaScript'e hata mesajını JSON formatında gönderir.
}
