<?php
include 'db_connect.php';

$id = $_GET['id'] ?? 0;

// ดึงข้อมูลเก่ามาแสดง
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $sql = "SELECT * FROM manual WHERE id = $id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    if (!$row) {
        echo "ไม่พบข้อมูลคู่มือ";
        exit;
    }
}

// บันทึกการแก้ไข
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);

    $sql = "UPDATE manual SET title='$title', description='$description' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: view_manual.php?id=$id");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<form method="POST">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
    Title: <input type="text" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required><br>
    Description: <textarea name="description" rows="10" required><?php echo htmlspecialchars($row['description']); ?></textarea><br>
    <button type="submit">Save Changes</button>
</form>

<a href="view_manual.php?id=<?php echo $row['id']; ?>">ยกเลิก</a>
