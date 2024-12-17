<?php
include 'db_connect.php';

$search = $_GET['search'] ?? ''; // รับค่าค้นหาจาก URL หรือเป็นค่าว่างถ้าไม่มีการค้นหา

// ค้นหาคู่มือหลัก
$sql = "SELECT id, title, image_path FROM manual WHERE title LIKE ?"; // ค้นหาเฉพาะที่มีคำค้นหา
$stmt = $conn->prepare($sql);
$search_term = "%" . $search . "%"; // สร้างคำค้นหาที่ใช้ในการเปรียบเทียบ
$stmt->bind_param("s", $search_term);
$stmt->execute();
$result = $stmt->get_result();
?>

<h1>รายการคู่มือ</h1>
<form method="GET">
    <input type="text" name="search" placeholder="ค้นหาคู่มือ" value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
</form>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
    <ul>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // แสดงชื่อหลัก
                echo "<li>";
                echo "<a href='view_manual.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['title']) . "</a>";
                
                // ตรวจสอบและแสดงรูปภาพถ้ามี
                if (!empty($row['image_path'])) {
                    echo "<br><img src='" . htmlspecialchars($row['image_path']) . "' alt='Image' style='width:100px; height:auto;'>";
                }

                // ดึงข้อมูลหัวข้อย่อย (subtopics) ที่เกี่ยวข้องกับคู่มือหลักนี้
                $subtopics_sql = "SELECT title FROM subtopics WHERE manual_id = ? AND title LIKE ?";
                $subtopics_stmt = $conn->prepare($subtopics_sql);
                $subtopics_stmt->bind_param("is", $row['id'], $search_term);
                $subtopics_stmt->execute();
                $subtopics_result = $subtopics_stmt->get_result();

                if ($subtopics_result->num_rows > 0) {
                    echo "<ul>";
                    while ($subtopic = $subtopics_result->fetch_assoc()) {
                        echo "<li>" . htmlspecialchars($subtopic['title']) . "</li>";
                    }
                    echo "</ul>";
                }
                
                echo "</li>";
            }
        } else {
            echo "<li>ไม่พบข้อมูลคู่มือ</li>";
        }
        ?>
    </ul>

    <a href="create_manual.php">เพิ่มคู่มือใหม่</a>

</body>

</html>
