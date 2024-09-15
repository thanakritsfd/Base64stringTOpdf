<?php
$file = $_FILES["fileToUpload"]["tmp_name"];  // ไฟล์ที่ถูกอัปโหลดจะอยู่ที่ tmp folder
$fileData = file_get_contents($file);         // อ่านข้อมูลไฟล์
$base64String = base64_encode($fileData);     // แปลงเป็น Base64 string
echo $base64String;  // แสดง Base64 string
?>