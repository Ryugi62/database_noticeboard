<?php
// VerifyCommentPassword.php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment_id = $_POST['comment_id'];
    $input_password = $_POST['password'];

    // 댓글의 비밀번호를 가져옵니다.
    $query = "SELECT password FROM comments WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    $stmt->bind_result($stored_password);
    $stmt->fetch();
    $stmt->close();

    // 비밀번호가 일치하는지 확인
    if (password_verify($input_password, $stored_password)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $conn->close();
}
?>