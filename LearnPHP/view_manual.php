<?php
include 'db_connect.php';

$id = $_GET['id'] ?? 0;

// ดึงข้อมูลคู่มือ
$sql = "SELECT * FROM manual WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (!$row) {
    echo "ไม่พบข้อมูลคู่มือ";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
    
    <!-- แสดงเนื้อหาคู่มือที่สามารถแสดง HTML ที่จัดรูปแบบได้ -->
    <p><?php echo nl2br(html_entity_decode(htmlspecialchars($row['description']))); ?></p>

    <!-- แสดงรูปภาพ -->
    <?php
    if ($row['image_path']) {
        echo "<img src='" . htmlspecialchars($row['image_path']) . "' alt='Image' style='width: 300px; height: auto;'>";
    }
    ?>

    <a href="edit_manual.php?id=<?php echo $row['id']; ?>">แก้ไขเนื้อหา</a>
    <a href="index.php">กลับไปหน้าหลัก</a>
</body>

</html>
