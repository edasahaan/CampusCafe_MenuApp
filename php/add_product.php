<?php
// add_product.php
header('Content-Type: application/json; charset=utf-8'); //sunucunun, tarayıcıya JSON tipinde veri gireceğini belirtir
require 'db.php'; //veritabanını bağlayan dosyayı çağırdık.

$data = json_decode(file_get_contents('php://input'), true); //javascriptden gelen verileri php diline çevirir

if (isset($data['name']) && isset($data['price']) && isset($data['category'])) { //name, price ve kategori javascript dosyasından eksiksiz gelmişse:
    try {

        $stmt = $pdo->prepare("INSERT INTO product (ProductName, Price, CategoryID, IsActive) VALUES (?, ?, ?, 1)"); //SQL sorgusunu hazırlar.

        // prepare komutu içindeki soru işaretlerinin yerine gelece değerleri execute ile çektik
        $stmt->execute([
            $data['name'],
            $data['price'],
            $data['category']
        ]);

        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Eksik veri gönderildi."]);
}
