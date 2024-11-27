<?php
// SubmitComment.php

include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['comment_id'])) {
        // 댓글 수정 처리
        $comment_id = $_POST['comment_id'];
        $user_id = $_POST['user_id'];
        $password = $_POST['password'];
        $content = $_POST['content'];

        // 비밀번호 및 사용자 아이디 검증을 위해 기존 정보 가져오기
        $query = "SELECT user_id, password FROM comments WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $comment_id);
        $stmt->execute();
        $stmt->bind_result($db_user_id, $hashedPassword);
        $stmt->fetch();
        $stmt->close();

        if ($user_id === $db_user_id && password_verify($password, $hashedPassword)) {
            $sql = "UPDATE comments SET content = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $content, $comment_id);

            if ($stmt->execute()) {
                // 성공 시 리다이렉션
                header("Location: /PostDetail.php?id=" . $_POST['post_id']);
                exit;
            } else {
                // 실패 시 뒤로가기
                echo "<script>alert('댓글 수정에 실패했습니다.'); history.back();</script>";
                exit;
            }
        } else {
            // 실패 시 뒤로가기
            echo "<script>alert('아이디 또는 비밀번호가 일치하지 않습니다.'); history.back();</script>";
            exit;
        }
    } else {
        // 댓글 작성 처리
        $post_id = $_POST['post_id'];
        $user_id = $_POST['user_id'];
        $password = $_POST['password'];
        $content = $_POST['content'];
        $parent_comment_id = $_POST['parent_comment_id'] ?? null;

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // 비밀번호를 해시화

        if ($parent_comment_id) {
            $query = "INSERT INTO comments (post_id, user_id, password, content, parent_comment_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("isssi", $post_id, $user_id, $hashedPassword, $content, $parent_comment_id);
        } else {
            $query = "INSERT INTO comments (post_id, user_id, password, content) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("isss", $post_id, $user_id, $hashedPassword, $content);
        }

        if ($stmt->execute()) {
            // 성공 시 리다이렉션
            header("Location: /PostDetail.php?id=" . $post_id);
            exit;
        } else {
            // 실패 시 뒤로가기
            echo "<script>alert('댓글 등록에 실패했습니다.'); history.back();</script>";
            exit;
        }
    }
}
?>