<?php
// require 'vendor/autoload.php'; // รวม autoload ของ Composer

// use PhpOffice\PhpWord\IOFactory;

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $target_dir = "uploads/";
//     $file_name = basename($_FILES["file"]["name"]);
//     $target_file = $target_dir . $file_name;
//     $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

//     // ตรวจสอบประเภทไฟล์
//     $allowed_types = array("pdf", "docx");
//     if (!in_array($file_type, $allowed_types)) {
//         die("เฉพาะไฟล์ PDF และ DOCX เท่านั้น");
//     }

//     // ย้ายไฟล์ไปยังโฟลเดอร์
//     if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
//         // บันทึกข้อมูลไฟล์ลงในฐานข้อมูล
//         $conn = new mysqli("localhost", "root", "", "manuals_db");
//         $stmt = $conn->prepare("INSERT INTO manuals (title, file_path, file_type) VALUES (?, ?, ?)");
//         $stmt->bind_param("sss", $file_name, $target_file, $file_type);
//         $stmt->execute();
//         $stmt->close();
//         $conn->close();
//         echo "อัปโหลดสำเร็จ!";
//     } else {
//         echo "อัปโหลดล้มเหลว";
//     }
// }
?>
