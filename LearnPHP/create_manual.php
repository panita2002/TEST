<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $_POST['description'];

    // เพิ่มคู่มือในตาราง manual
    $stmt = $conn->prepare("INSERT INTO manual (title, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $description);
    if ($stmt->execute()) {
        $manual_id = $stmt->insert_id;  // รับค่า ID ของคู่มือที่เพิ่ม

        // เพิ่มหัวข้อย่อยถ้ามี
        if (!empty($_POST['subtopics'])) {
            foreach ($_POST['subtopics'] as $subtopic) {
                $sub_title = $conn->real_escape_string($subtopic['title']);
                $sub_description = $conn->real_escape_string($subtopic['description']);

                // เพิ่มหัวข้อย่อยในตาราง subtopics
                $sub_stmt = $conn->prepare("INSERT INTO subtopics (manual_id, title, description) VALUES (?, ?, ?)");
                $sub_stmt->bind_param("iss", $manual_id, $sub_title, $sub_description);
                $sub_stmt->execute();
            }
        }

        echo "<p>บันทึกข้อมูลสำเร็จ</p>";
        header("Location: index.php");
        exit();
    } else {
        echo "<p>เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error . "</p>";
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

            <div class="form-group" id="subtopics-container">
                <h3>หัวข้อย่อย</h3>
                <div class="subtopic">
                    <label for="subtopic-title">Subtopic Title:</label>
                    <input type="text" name="subtopics[0][title]" required>
                    <label for="subtopic-description">Subtopic Description:</label>
                    <textarea name="subtopics[0][description]" rows="3" required></textarea>
                </div>
                <button type="button" onclick="addSubtopic()">เพิ่มหัวข้อย่อย</button>
            </div>

            <button type="submit" class="btn">บันทึก</button>
        </form>

        <a href="index.php" class="btn">กลับไปหน้าหลัก</a>
    </div>

    <script>
        let subtopicIndex = 1;

        function addSubtopic() {
            const container = document.getElementById('subtopics-container');
            const newSubtopic = document.createElement('div');
            newSubtopic.classList.add('subtopic');
            newSubtopic.innerHTML = `
                <label for="subtopic-title">Subtopic Title:</label>
                <input type="text" name="subtopics[${subtopicIndex}][title]" required>
                <label for="subtopic-description">Subtopic Description:</label>
                <textarea name="subtopics[${subtopicIndex}][description]" rows="3" required></textarea>
            `;
            container.appendChild(newSubtopic);
            subtopicIndex++;
        }
    </script>
</body>

</html>
