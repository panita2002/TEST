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

// ดึงหัวข้อย่อยที่เกี่ยวข้อง
$subtopics_sql = "SELECT * FROM subtopics WHERE manual_id = $id";
$subtopics_result = $conn->query($subtopics_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['title']); ?></title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
    <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>

    <!-- แสดงหัวข้อย่อย -->
    <?php
    if ($subtopics_result->num_rows > 0) {
        echo "<h3>หัวข้อย่อย</h3>";
        while ($subtopic = $subtopics_result->fetch_assoc()) {
            echo "<div class='subtopic'>";
            echo "<h4>" . htmlspecialchars($subtopic['title']) . "</h4>";
            echo "<p>" . nl2br(htmlspecialchars($subtopic['description'])) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>ไม่มีหัวข้อย่อย</p>";
    }
    ?>

    <a href="index.php">กลับไปหน้าหลัก</a>
</body>

</html>
