<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    // สำหรับ description จะไม่ใช้ real_escape_string
    // เพราะต้องการให้แสดง HTML ที่จัดรูปแบบได้
    $description = $_POST['description'];  // ไม่ต้องใช้ real_escape_string

    // ตรวจสอบว่าได้อัปโหลดไฟล์หรือไม่
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // กำหนดโฟลเดอร์ที่ใช้เก็บไฟล์
        $upload_dir = 'uploads/';  // ใช้ 'uploads/' แทน 'LearnPHP/uploads'
        $image_path = $upload_dir . basename($_FILES['image']['name']);

        // ตรวจสอบประเภทของไฟล์ (เช่น ให้รับเฉพาะไฟล์ภาพ)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image']['type'], $allowed_types)) {
            // ตรวจสอบว่าโฟลเดอร์ uploads/ มีอยู่หรือไม่ ถ้าไม่มีก็ให้สร้าง
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true); // สร้างโฟลเดอร์ใหม่ให้มีสิทธิ์ทั้งหมด
            }

            // ย้ายไฟล์ที่อัปโหลดไปยังโฟลเดอร์ที่กำหนด
            if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                // เพิ่มข้อมูลลงในฐานข้อมูล
                $stmt = $conn->prepare("INSERT INTO manual (title, description, image_path) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $title, $description, $image_path);

                if ($stmt->execute()) {
                    echo "<p>บันทึกข้อมูลสำเร็จ</p>";
                    header("Location: index.php");
                    exit();
                } else {
                    echo "<p>เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error . "</p>";
                }
            } else {
                echo "<p>เกิดข้อผิดพลาดในการอัปโหลดไฟล์</p>";
            }
        } else {
            echo "<p>ประเภทไฟล์ไม่ถูกต้อง กรุณาอัปโหลดไฟล์ภาพ</p>";
        }
    } else {
        // หากไม่ได้เลือกไฟล์
        echo "<p>กรุณาเลือกไฟล์รูปภาพ</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มคู่มือใหม่</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
    <div class="container">
        <h1>เพิ่มคู่มือใหม่</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="10" required></textarea>
            </div>

            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <button type="submit" class="btn">บันทึก</button>
        </form>

        <a href="index.php" class="btn">กลับไปหน้าหลัก</a>
    </div>
</body>

</html>