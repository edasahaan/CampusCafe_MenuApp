<?php

/**
 * AKTİF OTURUM (SESSION) KONTROLÜ
 * ------------------------------
 * Tarayıcıda geçerli bir öğrenci oturumu olup olmadığını denetler.
 * Eğer öğrenci giriş yapmışsa, sistemdeki benzersiz ID numarasını döndürür.
 * Frontend (JavaScript) tarafında "Kullanıcı giriş yapmış mı?" kontrolü için kullanılır.
 */

session_start();
header('Content-Type: application/json');

if (isset($_SESSION['student_user_id'])) {
    echo json_encode(["id_no" => $_SESSION['student_user_id']]);
} else {
    echo json_encode(["error" => "Not logged in"]);
}
