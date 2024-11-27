<?php
// 데이터베이스 설정
$db_host = 'localhost'; // 데이터베이스 호스트
$db_user = 'root';      // 데이터베이스 사용자명
$db_pass = 'dydehsqjfdl123!@#';  // 데이터베이스 비밀번호
$db_name = 'database_report'; // 데이터베이스 이름

// MySQLi 연결 (데이터베이스 없이 연결 시도)
$conn = new mysqli($db_host, $db_user, $db_pass);

// 연결 확인
if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}

// 데이터베이스가 존재하지 않으면 생성
$db_create_query = "CREATE DATABASE IF NOT EXISTS $db_name CHARACTER SET utf8 COLLATE utf8_general_ci";
if ($conn->query($db_create_query) === TRUE) {
    // echo "데이터베이스가 준비되었습니다.<br>";
} else {
    die("데이터베이스 생성 실패: " . $conn->error);
}

// 생성된 데이터베이스로 연결 전환
if ($conn->select_db($db_name) === false) {
    die("데이터베이스 선택 실패: " . $conn->error);  // select_db가 실패한 경우
}

// 테이블 존재 여부 및 데이터 확인 후 생성 또는 수정하는 함수
function createOrUpdateTable($conn, $table_name, $create_query, $check_empty_query = null)
{
    $table_check_query = "SHOW TABLES LIKE '$table_name'";
    $table_result = $conn->query($table_check_query);

    if ($table_result->num_rows === 0) {
        // 테이블이 존재하지 않으면 생성
        if ($conn->query($create_query) === TRUE) {
            // echo "테이블 '$table_name'가 생성되었습니다.<br>";
        } else {
            die("테이블 '$table_name' 생성 실패: " . $conn->error);
        }
    } else {
        // 테이블이 이미 존재할 경우, 데이터가 없으면 추가하고, 데이터 타입 변경이 필요할 수 있음
        // echo "테이블 '$table_name'은 이미 존재합니다.<br>";

        // 데이터가 비어있는 경우 기본 데이터 추가
        if ($check_empty_query) {
            $result = $conn->query($check_empty_query);
            if ($result && $result->num_rows === 0) {
                // echo "테이블 '$table_name'에 데이터가 없으므로 기본 데이터를 추가합니다.<br>";
                $conn->query($check_empty_query);  // 기본 데이터 추가
            }
        }

        // 테이블의 데이터 타입이 다를 경우 수정
        // 예시: 테이블에 변경된 컬럼이 있다면 ALTER TABLE로 수정할 수 있도록 추가할 수 있음
        // 데이터 타입 변경 필요시 ALTER TABLE 쿼리를 실행
        // 예: ALTER TABLE posts MODIFY COLUMN title VARCHAR(500) NOT NULL;
    }
}

// 게시물 테이블 생성 쿼리
$posts_table_create_query = "
    CREATE TABLE posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id VARCHAR(50) NOT NULL,
        password VARCHAR(255) NOT NULL,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        attachment VARCHAR(255),
        view_count INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";

// 게시물 테이블에 데이터가 없으면 기본 데이터 삽입
$posts_check_empty_query = "
    SELECT * FROM posts LIMIT 1;
";

// 댓글 테이블 생성 쿼리
$comments_table_create_query = "
    CREATE TABLE comments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        post_id INT NOT NULL,
        user_id VARCHAR(50) NOT NULL,
        password VARCHAR(255) NOT NULL,
        content LONGTEXT NOT NULL,
        parent_comment_id INT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
        FOREIGN KEY (parent_comment_id) REFERENCES comments(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";

// 댓글 테이블에 데이터가 없으면 기본 데이터 삽입
$comments_check_empty_query = "
    SELECT * FROM comments LIMIT 1;
";

// 첨부파일 테이블 생성 쿼리
$attachments_table_create_query = "
    CREATE TABLE attachments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        post_id INT NOT NULL,
        file_name VARCHAR(255) NOT NULL,
        file_url VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";

// 첨부파일 테이블에 데이터가 없으면 기본 데이터 삽입
$attachments_check_empty_query = "
    SELECT * FROM attachments LIMIT 1;
";

// 테이블 생성 및 데이터 확인
createOrUpdateTable($conn, 'posts', $posts_table_create_query, $posts_check_empty_query);
createOrUpdateTable($conn, 'comments', $comments_table_create_query, $comments_check_empty_query);
createOrUpdateTable($conn, 'attachments', $attachments_table_create_query, $attachments_check_empty_query);

// UTF-8 설정
$conn->set_charset("utf8mb4");

// 완료 메시지
// echo "데이터베이스 및 테이블 구성이 완료되었습니다.<br>";
?>