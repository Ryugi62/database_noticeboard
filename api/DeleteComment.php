<?php
// DeleteComment.php
include '../config/database.php';
header('Content-Type: application/json'); // JSON 응답 설정

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // JSON 요청 본문 읽기
    $data = json_decode(file_get_contents('php://input'), true);

    $comment_id = $data['comment_id'];
    $password = $data['password'];

    // 댓글 정보 가져오기 (비밀번호 확인을 위해)
    $query = "SELECT password, post_id FROM comments WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    $stmt->bind_result($hashedPassword, $post_id);
    $stmt->fetch();
    $stmt->close();

    // 비밀번호 확인
    if (password_verify($password, $hashedPassword)) {
        // 비밀번호가 일치하면 댓글 삭제
        $deleteQuery = "DELETE FROM comments WHERE id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $comment_id);

        if ($stmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "댓글이 삭제되었습니다.",
                "post_id" => $post_id, // 리다이렉션용
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "댓글 삭제에 실패했습니다.",
            ]);
        }
        $stmt->close();
    } else {
        // 비밀번호 불일치
        echo json_encode([
            "success" => false,
            "message" => "비밀번호가 일치하지 않습니다.",
        ]);
    }

    $conn->close();
}
?>