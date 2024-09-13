<?php
// อนุญาตให้ cross-origin requests หมายความว่า ให้ Domain ไหนสามารถ Request ได้บ้าง ในที่นี้ใส่ * คือได้ทั้งหมด
header("Access-Control-Allow-Origin: *");
// กำหนด content type เป็น JSON
header("Content-Type: application/json; charset=UTF-8");

// รับข้อมูล JSON จาก request body
$data = json_decode(file_get_contents("php://input"), true);

// ตรวจสอบว่าข้อมูล JSON มีข้อมูลที่ต้องการครบถ้วนหรือไม่
if (isset($data['base64string'])) {

    class FileUploader {
        private $_assets_path;

        public function __construct() {
            // กำหนด path ที่คุณต้องการบันทึกไฟล์
            $this->_assets_path = __DIR__; // หรือกำหนด path ตามที่คุณต้องการ เช่น '/var/www/assets'
        }

        public function uploadFileFromBlobString($base64string, $file_name, $folder = 'file')
        {
            $result = 0;

            // Convert blob (base64 string) back to PDF
            if (!empty($base64string)) {

                // Detects if there is base64 encoding header in the string.
                if (strpos($base64string, ',') !== false) {
                    @list($encode, $base64string) = explode(',', $base64string);
                }

                $base64data = base64_decode($base64string, true);
                $file_path  = "{$folder}/{$file_name}";

                // ตรวจสอบว่ามี directory หรือไม่ ถ้าไม่มีก็สร้างขึ้นมา
                if (!is_dir("{$this->_assets_path}/{$folder}")) {
                    mkdir("{$this->_assets_path}/{$folder}", 0755, true);
                }

                // Return the number of bytes saved, or false on failure
                $result = file_put_contents("{$this->_assets_path}/{$file_path}", $base64data);
            }

            return $result;
        }
    }

    // สร้างชื่อไฟล์จาก ปี เดือน วัน ชั่วโมง นาที วินาที และเสี้ยววินาที (microseconds)
    $date = new DateTime();
    $timestamp = $date->format('Ymd_His_u'); // รูปแบบ: ปีเดือนวัน_ชั่วโมงนาทีวินาที_เสี้ยววินาที
    $file_name = "{$timestamp}.pdf"; // ตั้งชื่อไฟล์เป็น timestamp พร้อมนามสกุล .pdf

    // เรียกฟังก์ชันโดยใช้ข้อมูลจาก JSON request
    $uploader = new FileUploader();
    $result = $uploader->uploadFileFromBlobString(
        $data['base64string'],
        $file_name
    );
    if ($result) {
        echo json_encode(["message" => "File uploaded successfully", "file_name" => $file_name, "bytes" => $result]);
    } else {
        echo json_encode(["message" => "File upload failed"]);
    }

} else {
    echo json_encode(["message" => "Invalid input"]);
}
