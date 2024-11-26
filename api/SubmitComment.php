<?php
require_once '../config/database.php'; // 데이터베이스 연결

// 폼 데이터 가져오기
$post_id = intval($_POST['post_id']);
$user_id = $_POST['user_id'];
$password = $_POST['password'];
$content = $_POST['content'];
$parent_comment_id = isset($_POST['parent_comment_id']) ? intval($_POST['parent_comment_id']) : null;

// 댓글 저장 쿼리 및 준비
if ($parent_comment_id === null) {
    $insert_query = "
        INSERT INTO comments (post_id, user_id, password, content, created_at)
        VALUES (?, ?, ?, ?, NOW())
    ";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("isss", $post_id, $user_id, $password, $content);
} else {
    $insert_query = "
        INSERT INTO comments (post_id, user_id, password, content, parent_comment_id, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())
    ";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("isssi", $post_id, $user_id, $password, $content, $parent_comment_id);
}

// 쿼리 실행
$stmt->execute();

if ($stmt->affected_rows > 0) {
    header("Location: /PostDetail.php?id=" . $post_id);
} else {
    echo "댓글 등록에 실패하였습니다.";
}

$stmt->close();
$conn->close();
?>