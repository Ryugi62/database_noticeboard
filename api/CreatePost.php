<?php
require_once '../config/database.php';

// 폼에서 전송된 데이터 받기
$user_id = $_POST['user_id'];
$password = $_POST['password'];
$title = $_POST['title'];
$content = $_POST['content'];

if (empty($user_id) || empty($password) || empty($title) || empty($content)) {
    header("Location: /");
    die("아이디, 비밀번호, 제목, 게시물 내용은 필수 입력 항목입니다.");
}

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

// 데이터베이스에 게시물 저장
$sql = "INSERT INTO posts (user_id, password, title, content) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $user_id, $password, $title, $content);
$stmt->execute();

// 게시물이 저장된 후 post_id 가져오기
$post_id = $stmt->insert_id; // 마지막으로 삽입된 게시물의 ID

if ($stmt->affected_rows > 0) {
    // 첨부파일 처리
    $file_url = null;
    $file_name = null;
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

            // 첨부파일을 attachments 테이블에 저장
            $attachment_sql = "INSERT INTO attachments (post_id, file_url, file_name) VALUES (?, ?, ?)";
            $attachment_stmt = $conn->prepare($attachment_sql);
            $attachment_stmt->bind_param("iss", $post_id, $file_url, $file_name); // file_name 추가
            $attachment_stmt->execute();
        }
    }

    // 게시물과 첨부파일 저장이 완료되면 메인 페이지로 리다이렉트
    header("Location: /");
    exit;
} else {
    echo "게시물 등록 중 오류가 발생했습니다.";
}

$stmt->close();
$conn->close();
?>