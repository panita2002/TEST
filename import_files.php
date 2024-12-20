<?php

require 'vendor/autoload.php';

use PhpOffice\PhpWord\IOFactory;
use Smalot\PdfParser\Parser; // เรียกใช้คลาส Parser
use thiagoalessio\TesseractOCR\TesseractOCR;


$conn = new mysqli("localhost", "root", "", "testdb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function readImageWithOCR($imagePath) {
    $ocr = new TesseractOCR($imagePath);
    return $ocr->run();
}

// อ่านไฟล์ DOCX
function readDocx($filePath) {
    try {
        $phpWord = IOFactory::load($filePath);
        $text = '';

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                // ตรวจสอบว่าเป็น Paragraph หรือไม่
                if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    foreach ($element->getParagraphStyle() as $subElement) {
                        if (method_exists($subElement, 'getText')) {
                            $text .= $subElement->getParagraphText() . "\n";
                        }
                    }
                }
            }
        }

        return $text;
    } catch (Exception $e) {
        echo 'Error loading the DOCX file: ' . $e->getMessage();
        return '';
    }
}

// อ่านไฟล์ PDF
function readPdf($filePath) {
    try {
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
        $text = '';

        // วนลูปแต่ละหน้า
        foreach ($pdf->getPages() as $page) {
            $text .= $page->getText() . "\n"; // ดึงข้อความจากแต่ละหน้า
        }

        return $text;
    } catch (Exception $e) {
        echo 'Error loading the PDF file: ' . $e->getMessage();
        return '';
    }
}

// เลือกไฟล์ที่ต้องการ
$filePath = "documents/amc.docx"; // เปลี่ยนเป็นเส้นทางไฟล์ของคุณ
$fileType = pathinfo($filePath, PATHINFO_EXTENSION);

if ($fileType == 'docx') {
    $content = readDocx($filePath);
} elseif ($fileType == 'pdf') {
    $content = readPdf($filePath);
} else {
    die("Unsupported file type");
}

// บันทึกลงฐานข้อมูล
$title = basename($filePath);
$stmt = $conn->prepare("INSERT INTO documents (title, content, file_type) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $title, $content, $fileType);
$stmt->execute();

echo "File imported successfully!";

if (isset($_FILES['image'])) {
    $imageName = $_FILES['image']['name'];
    $imageData = file_get_contents($_FILES['image']['tmp_name']);

    $conn = new mysqli("localhost", "root", "", "testdb");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO images (image_name, image_data) VALUES (?, ?)");
    $stmt->bind_param("sb", $imageName, $imageData);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

?>
