<?php
// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// อ่านไฟล์ที่แปลงแล้ว (เช่น ไฟล์ .txt ที่ได้จาก pdftotext)
$textContent = file_get_contents("readme.txt");  // ข้อความที่แปลงมาจากไฟล์

// เตรียมคำสั่ง SQL สำหรับบันทึกข้อมูล
$stmt = $conn->prepare("INSERT INTO files (filename, content) VALUES (?, ?)");
$stmt->bind_param("ss", $filename, $content);

// กำหนดค่าตัวแปร
$filename = "readme.txt";  // ชื่อไฟล์
$content = $textContent;   // เนื้อหาของไฟล์

// ดำเนินการแทรกข้อมูล
$stmt->execute();

echo "Data inserted successfully";

// ปิดการเชื่อมต่อ
$stmt->close();
$conn->close();
?>
