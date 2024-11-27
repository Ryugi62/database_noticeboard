<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 입력된 데이터 가져오기
    $input_password = $_POST['password'];
    $input_title = $_POST['title'];
    $input_content = $_POST['content'];

    // 게시물 ID 가져오기
    $post_id = intval($_GET['id']);

    // 데이터베이스 연결 확인
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 게시물 정보 가져오기
    $post_query = "SELECT id, password, attachment FROM posts WHERE id = ?";
    $stmt_post = $conn->prepare($post_query);
    if ($stmt_post === false) {
        die('MySQL prepare error: ' . $conn->error);  // 쿼리 준비 오류 확인
    }

    $stmt_post->bind_param("i", $post_id);
    $stmt_post->execute();
    $stmt_post->bind_result($id, $stored_password, $existing_attachment);

    // 결과 가져오기
    if ($stmt_post->fetch()) {
        // 비밀번호가 일치하는지 확인
        if (password_verify($input_password, $stored_password)) {

            // 파일 업로드 처리
            $attachment_file_url = null;
            $attachment_file_name = null;

            // 첨부파일이 존재하는 경우
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../uploads/'; // 업로드 디렉토리 지정
                $attachment_file_name = basename($_FILES['attachment']['name']);
                $attachment_file_url = $upload_dir . $attachment_file_name;

                // 파일 이동
                if (!move_uploaded_file($_FILES['attachment']['tmp_name'], $attachment_file_url)) {
                    echo "<script>alert('파일 업로드에 실패했습니다.'); </script>";
                    exit;
                }

                // 기존 첨부파일이 있다면 삭제 (attachments 테이블에서 삭제)
                if ($existing_attachment) {
                    // attachments 테이블에서 기존 첨부파일 삭제
                    $delete_attachment_query = "DELETE FROM attachments WHERE post_id = ?";
                    $stmt_delete_attachment = $conn->prepare($delete_attachment_query);
                    if ($stmt_delete_attachment === false) {
                        die('MySQL prepare error for DELETE: ' . $conn->error);
                    }
                    $stmt_delete_attachment->bind_param("i", $post_id);
                    $stmt_delete_attachment->execute();
                    $stmt_delete_attachment->close();  // DELETE 쿼리 종료
                }

                // 새로운 첨부파일을 attachments 테이블에 추가
                $insert_attachment_query = "INSERT INTO attachments (post_id, file_url, file_name) VALUES (?, ?, ?)";
                $stmt_insert_attachment = $conn->prepare($insert_attachment_query);
                if ($stmt_insert_attachment === false) {
                    die('MySQL prepare error for INSERT: ' . $conn->error);
                }
                $stmt_insert_attachment->bind_param("iss", $post_id, $attachment_file_url, $attachment_file_name);
                $stmt_insert_attachment->execute();
                $stmt_insert_attachment->close();  // INSERT 쿼리 종료
            }

            // 게시물 수정 쿼리 준비
            $stmt_post->close();  // SELECT 쿼리 종료

            // 게시물 수정 쿼리
            $update_query = "UPDATE posts SET title = ?, content = ? WHERE id = ?";
            $stmt_update = $conn->prepare($update_query);
            if ($stmt_update === false) {
                die('MySQL prepare error for UPDATE: ' . $conn->error);  // 쿼리 준비 오류 확인
            }

            $stmt_update->bind_param("ssi", $input_title, $input_content, $post_id);
            $stmt_update->execute();

            // 디버깅: 수정된 내용 확인
            if ($stmt_update->error) {
                die('MySQL execute error for UPDATE: ' . $stmt_update->error);  // 쿼리 실행 오류 확인
            }
            echo "Post updated successfully.<br>";

            // UPDATE 쿼리 종료
            $stmt_update->close();

            // 수정 성공 후 홈페이지로 리다이렉트
            header("Location: /");
            exit;
        } else {
            // 비밀번호가 일치하지 않으면 이전 페이지로 리다이렉트 및 alert
            echo "<script>
                    alert('비밀번호가 일치하지 않습니다.');
                    history.back();
                  </script>";
            exit;
        }
    } else {
        // 게시물이 존재하지 않으면 이전 페이지로 리다이렉트 및 alert
        echo "<script>
                alert('게시물이 존재하지 않습니다.');
                history.back();
              </script>";
        exit;
    }

    // 최종적으로 연결 종료
    $conn->close();
}
?>