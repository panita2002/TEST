<?php
$conn = new mysqli("localhost", "root", "", "testdb");
$result = $conn->query("SELECT * FROM documents");
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>คู่มือการติดตั้ง</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>คู่มือการติดตั้งและอัปเดตโปรแกรม</h1>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="document">
            <h2><?= htmlspecialchars($row['title']) ?></h2>
            <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
        </div>
    <?php endwhile; ?>
</body>
</html>
