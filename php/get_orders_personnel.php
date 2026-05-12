<?php
//öğrencinin oluşturduğu siparişleri personel ekranına taşınacak formda veritabanından çeker

session_start(); //Oturum yönetimini başlatır. Giriş yapan personelin kim olduğunu anlamamızı sağlar.
header('Content-Type: application/json; charset=utf-8'); //Çıktının bir web sayfası değil, temiz bir JSON verisi olduğunu tarayıcıya bildirir.
require 'db.php'; //Veritabanına bağlanmamızı sağlayan dosyayı çağırır.

// Güvenlik Duvarı: Sadece yetkili personel bu verileri çekebilir
if (!isset($_SESSION['personnel_user_id'])) {
    echo json_encode(["error" => "Yetkisiz islem. Lütfen giriş yapın."]);
    exit;
}

// Oturum dosyasını serbest bırakır. Bu, sayfanın daha hızlı yüklenmesini sağlar (PHP'nin dosyayı kilitlemesini önler).
session_write_close();

//personel ekranına gidecek sipariş bilgilerinini nasıl bir formda olacağını düzenler (ekstraları parantez içinde göstermek gibi)
try {
    $query = "SELECT 
            o.OrderID, 
            o.Status, 
            GROUP_CONCAT(
                CONCAT(
                    od.Quantity, 'x ', p.ProductName, 
                    COALESCE((
                        SELECT CONCAT(' (', GROUP_CONCAT(c.Value SEPARATOR ', '), ')')
                        FROM order_detail_custom odc
                        JOIN customization c ON odc.CustomID = c.CustomID
                        WHERE odc.DetailID = od.DetailID
                    ), '')
                ) SEPARATOR ', '
            ) as Details
          FROM `order` o
          JOIN order_detail od ON o.OrderID = od.OrderID
          JOIN product p ON od.ProductID = p.ProductID
          GROUP BY o.OrderID
          ORDER BY o.OrderID DESC";


    //Hazırladığımız sorguyu veritabanına gönderir ve çalıştırır.
    $stmt = $pdo->query($query);

    //Veritabanından gelen karmaşık tabloyu, PHP'nin anlayacağı temiz bir listeye çevirir.
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);


    //Elde edilen sipariş listesini JSON formatında ekrana basar (JavaScript bunu okuyup karta çevirecek).
    echo json_encode($orders);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
