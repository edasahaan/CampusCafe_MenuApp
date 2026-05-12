<?php
session_start();

//Kutunun içindeki tüm değişkenleri temizle
$_SESSION = array();

//Kutuyu (Oturumu) tamamen yok et
session_destroy();

//Kullanıcıyı giriş sayfasına geri fırlat
header("Location: ../entrance.php");
exit();
