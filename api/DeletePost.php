<?php
require_once '../config/database.php'; // 데이터베이스 연결

// 게시물 ID와 입력된 비밀번호 가져오기
$post_id = intval($_POST['id']);
$posted_password = $_POST['password'] ?? null;

// 게시물 정보 가져오기
$post_query = "SELECT id, password FROM posts WHERE id = ?";
$stmt_post = $conn->prepare($post_query);
$stmt_post->bind_param("i", $post_id);
$stmt_post->execute();
$stmt_post->bind_result($id, $password);
$stmt_post->fetch();
$stmt_post->close();

if ($posted_password) {
    if (password_verify($posted_password, $password)) {
        // 비밀번호 일치, 게시물 삭제
        $delete_post_query = "DELETE FROM posts WHERE id = ?";
        $stmt_delete_post = $conn->prepare($delete_post_query);
        $stmt_delete_post->bind_param("i", $post_id);
        $stmt_delete_post->execute();
        $stmt_delete_post->close();

        // 첨부파일 삭제 (선택 사항)
        $delete_attachments_query = "DELETE FROM attachments WHERE post_id = ?";
        $stmt_delete_attachments = $conn->prepare($delete_attachments_query);
        $stmt_delete_attachments->bind_param("i", $post_id);
        $stmt_delete_attachments->execute();
        $stmt_delete_attachments->close();

        echo "success"; // 성공 메시지
    } else {
        echo "비밀번호가 일치하지 않습니다."; // 비밀번호 불일치 메시지
    }
} else {
    echo "비밀번호를 입력해주세요.";
}
?>