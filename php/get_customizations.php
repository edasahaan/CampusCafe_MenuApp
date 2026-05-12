<?php
//ekstraların kontrolü
header('Content-Type: application/json; charset=utf-8');
require 'db.php';

try {
    // Tüm özelleştirme seçeneklerini çek
    $stmt = $pdo->query("SELECT CustomID, CustomType, Value, ExtraPrice FROM customization");
    $customizations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Veriyi JavaScript tarafında kolay kullanmak için CustomType'a göre grupluyoruz
    $grouped = [];
    foreach ($customizations as $row) {
        $type = $row['CustomType']; // 'Milk', 'Syrup', 'Size' vb.
        if (!isset($grouped[$type])) {
            $grouped[$type] = [];
        }
        $grouped[$type][] = $row;
    }

    echo json_encode($grouped);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
