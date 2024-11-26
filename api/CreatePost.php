<?php
require_once '../config/database.php';

// 폼에서 전송된 데이터 받기
$user_id = $_POST['user_id'];
$password = $_POST['password'];
$title = $_POST['title'];
$content = $_POST['content'];

// 입력 데이터가 UTF-8로 인코딩되었는지 확인
if (!mb_check_encoding($user_id, 'UTF-8')) {
    $user_id = mb_convert_encoding($user_id, 'UTF-8', 'auto');
}
if (!mb_check_encoding($password, 'UTF-8')) {
    $password = mb_convert_encoding($password, 'UTF-8', 'auto');
}
if (!mb_check_encoding($title, 'UTF-8')) {
    $title = mb_convert_encoding($title, 'UTF-8', 'auto');
}
if (!mb_check_encoding($content, 'UTF-8')) {
    $content = mb_convert_encoding($content, 'UTF-8', 'auto');
}

// 파일 업로드 처리
$file_url = null;
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['attachment'];
    $upload_dir = '../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $file_name = uniqid() . '_' . basename($file['name']);
    $upload_file = $upload_dir . $file_name;
    if (move_uploaded_file($file['tmp_name'], $upload_file)) {
        $file_url = '/uploads/' . $file_name;
    }
}

// 데이터베이스에 게시물 저장
$sql = "INSERT INTO posts (user_id, password, title, content, attachment) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $user_id, $password, $title, $content, $file_url);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    header("Location: /");
    exit;
} else {
    echo "게시물 등록 중 오류가 발생했습니다.";
}

$stmt->close();
$conn->close();
?>