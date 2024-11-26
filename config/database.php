<?
// 데이터베이스 설정
$db_host = 'localhost'; // 데이터베이스 호스트
$db_user = 'root';      // 데이터베이스 사용자명
$db_pass = 'apmsetup';  // 데이터베이스 비밀번호
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
$conn->select_db($db_name);

// 필요한 테이블 확인 및 생성
$table_check_query = "SHOW TABLES LIKE 'posts'";
$table_result = $conn->query($table_check_query);

if ($table_result->num_rows === 0) {
    // 게시물 테이블 생성
    $table_create_query = "
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
    if ($conn->query($table_create_query) === TRUE) {
        // echo "테이블 'posts'가 생성되었습니다.<br>";
    } else {
        die("테이블 생성 실패: " . $conn->error);
    }
} else {
    // echo "테이블 'posts'는 이미 존재합니다.<br>";
}

// 댓글 테이블 확인 및 생성
$table_check_query = "SHOW TABLES LIKE 'comments'";
$table_result = $conn->query($table_check_query);

if ($table_result->num_rows === 0) {
    // 댓글 테이블 생성
    $table_create_query = "
        CREATE TABLE comments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            post_id INT NOT NULL,
            user_id VARCHAR(50) NOT NULL,
            password VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            parent_comment_id INT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
            FOREIGN KEY (parent_comment_id) REFERENCES comments(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ";
    if ($conn->query($table_create_query) === TRUE) {
        // echo "테이블 'comments'가 생성되었습니다.<br>";
    } else {
        die("댓글 테이블 생성 실패: " . $conn->error);
    }
} else {
    // echo "테이블 'comments'는 이미 존재합니다.<br>";
}

// 깨진 데이터 읽기 및 복구
$result = $conn->query("SELECT id, content FROM posts");
while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $content = $row['content'];

    // 깨진 데이터 복구
    $fixed_content = iconv('latin1', 'utf-8', $content);

    // 복구된 데이터 저장
    $stmt = $conn->prepare("UPDATE posts SET content = ? WHERE id = ?");
    $stmt->bind_param("si", $fixed_content, $id);
    $stmt->execute();
}

// UTF-8 설정
$conn->set_charset("utf8");

// 완료 메시지
// echo "데이터베이스 및 테이블 구성이 완료되었습니다.<br>";
?>
