<?php
require_once 'config/database.php'; // 데이터베이스 연결

// 현재 게시물 ID 가져오기
$post_id = intval($_GET['id']);

// 조회수 증가 처리
$update_view_count_query = "UPDATE posts SET view_count = view_count + 1 WHERE id = ?";
$stmt_update_view_count = $conn->prepare($update_view_count_query);
$stmt_update_view_count->bind_param("i", $post_id);
$stmt_update_view_count->execute();
$stmt_update_view_count->close();

// 현재 게시물 정보 가져오기
$post_query = "SELECT id, title, user_id, password, content, created_at FROM posts WHERE id = ?";
$stmt_post = $conn->prepare($post_query);
$stmt_post->bind_param("i", $post_id);
$stmt_post->execute();

// 결과 바인딩
$stmt_post->bind_result($id, $title, $user_id, $password, $content, $created_at);
if ($stmt_post->fetch()) {
    $post = array(
        'id' => $id,
        'title' => $title,
        'user_id' => $user_id,
        'password' => $password,
        'content' => $content,
        'created_at' => $created_at
    );
} else {
    echo "존재하지 않는 게시물입니다.";
    exit;
}
$stmt_post->close();

// 첨부파일 가져오기
$attachments_query = "SELECT file_url FROM attachments WHERE post_id = ?";
$stmt_attachments = $conn->prepare($attachments_query);
$stmt_attachments->bind_param("i", $post_id);
$stmt_attachments->execute();
$stmt_attachments->bind_result($file_url);

$attachments = [];
while ($stmt_attachments->fetch()) {
    $attachments[] = $file_url;
}
$stmt_attachments->close();

// 이전글 가져오기
$prev_query = "SELECT id, title, user_id, created_at FROM posts WHERE id < ? ORDER BY id DESC LIMIT 1";
$stmt_prev = $conn->prepare($prev_query);
$stmt_prev->bind_param("i", $post_id);
$stmt_prev->execute();

$stmt_prev->bind_result($prev_id, $prev_title, $prev_user_id, $prev_created_at);
if ($stmt_prev->fetch()) {
    $prev_post = array(
        'id' => $prev_id,
        'title' => $prev_title,
        'user_id' => $prev_user_id,
        'created_at' => $prev_created_at
    );
} else {
    $prev_post = null;
}
$stmt_prev->close();

// 다음글 가져오기
$next_query = "SELECT id, title, user_id, created_at FROM posts WHERE id > ? ORDER BY id ASC LIMIT 1";
$stmt_next = $conn->prepare($next_query);
$stmt_next->bind_param("i", $post_id);
$stmt_next->execute();

$stmt_next->bind_result($next_id, $next_title, $next_user_id, $next_created_at);
if ($stmt_next->fetch()) {
    $next_post = array(
        'id' => $next_id,
        'title' => $next_title,
        'user_id' => $next_user_id,
        'created_at' => $next_created_at
    );
} else {
    $next_post = null;
}
$stmt_next->close();

$title = $post['title'];
$user_id = $post['user_id'];
$created_at = $post['created_at'];
$content = $post['content'];
?>

<div class="post-detail-container">
    <div class="page-header">
        <strong class="page-title">게시판 상세페이지</strong>
    </div>

    <div class="post-header-container">
        <h2 class="post-title"><?= htmlspecialchars($title); ?></h2>
        <span>
            <p><?= htmlspecialchars($user_id); ?></p>
            <p>|</p>
            <p><?= date("Y.m.d H:i:s", strtotime($created_at)); ?></p>
        </span>
    </div>

    <div class="post-detail-main-container">
        <p><?= nl2br($content); ?></p>
    </div>

    <!-- 첨부파일 리스트 -->
    <div class="attachments-container">
        <?php if (count($attachments) > 0): ?>
            <ul>
                <strong>첨부파일: </strong>
                <?php foreach ($attachments as $file): ?>
                    <li>
                        <a href="<?= htmlspecialchars($file); ?>" class="attachment-link" download>
                            <?= htmlspecialchars(basename($file)); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <strong>첨부파일: </strong>
            <p>첨부파일이 없습니다.</p>
        <?php endif; ?>
    </div>

    <div class="post-detail-footer-buttons">
        <a href="/" class="go-post-list-button">
            <button>글 </button>
        </a>
        <a href="/EditPost.php?id=<?= $post_id; ?>" class="go-post-edit-button">
            <button>글 수정</button>
        </a>

        <!-- 게시물 삭제 버튼 -->
        <button class="go-post-delete-button" onclick="confirmDelete(<?= $post_id; ?>)">
            게시물 삭제
        </button>
    </div>

    <div class="post-navigation">
        <div class="next-post">
            <p>다음글</p>
            <?php if ($next_post) { ?>
                <a href="/PostDetail.php?id=<?= $next_post['id']; ?>" class="navigation-post-title">
                    <p><?= htmlspecialchars($next_post['title']); ?></p>
                </a>
                <p><?= htmlspecialchars($next_post['user_id']); ?></p>
                <p><?= date("Y.m.d", strtotime($next_post['created_at'])); ?></p>
            <?php } else { ?>
                <p class="no-post">이전글이 없습니다</p>
            <?php } ?>
        </div>
        <div class="previous-post">
            <p>이전글</p>
            <?php if ($prev_post) { ?>
                <a href="/PostDetail.php?id=<?= $prev_post['id']; ?>" class="navigation-post-title">
                    <p><?= htmlspecialchars($prev_post['title']); ?></p>
                </a>
                <p><?= htmlspecialchars($prev_post['user_id']); ?></p>
                <p><?= date("Y.m.d", strtotime($prev_post['created_at'])); ?></p>
            <?php } else { ?>
                <p class="no-post">다음글이 없습니다</p>
            <?php } ?>
        </div>
    </div>

    <script>
        function confirmDelete(postId) {
            // 비밀번호 입력 받기
            const password = prompt("게시물 삭제를 위해 비밀번호를 입력하세요.");

            if (password) {
                // 비밀번호가 입력된 경우 AJAX를 통해 서버로 삭제 요청
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "/api/DeletePost.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const response = xhr.responseText;

                        // 서버에서 반환된 메시지에 따라 알림
                        if (response === "success") {
                            alert("게시물이 삭제되었습니다.");
                            window.location.href = "/"; // 삭제 후 목록 페이지로 리디렉션
                        } else {
                            alert(response); // 비밀번호 불일치 시 경고
                        }
                    }
                };
                xhr.send("id=" + postId + "&password=" + encodeURIComponent(password));
            } else {
                alert("비밀번호를 입력해야 합니다.");
            }
        }
    </script>

    <style>
        .post-title {
            margin: 0;
        }

        .go-post-edit-button button {
            color: white;
            display: flex;
            background-color: #2a2a2a;
        }

        .go-post-delete-button {
            color: white;
            background-color: red;
        }

        .post-navigation {
            margin-top: 16px;
            border-top: 1px solid black;
        }

        .previous-post,
        .next-post {
            gap: 16px;
            height: 40px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ccc;
            padding: 5px 0;
        }

        .navigation-post-title {
            flex: 1;
            text-decoration: none;
            color: inherit;
        }

        .navigation-post-title:hover {
            text-decoration: underline;
            cursor: pointer;
        }

        .no-post {
            flex: 1;
            cursor: default;
            color: #888;
        }

        .post-detail-main-container {
            flex: 1;
        }

        .attachments-container ul {
            border-top: 1px solid #ccc;
            gap: 6px;
            padding-top: 20px;
            padding: 0;
            display: flex;
            list-style-type: none;
            flex-direction: row;
            align-items: center;
        }

        .attachments-container li {
            margin: 5px 0;
        }

        .attachment-link {
            color: #333
        }

        .attachment-link:hover {
            text-decoration: underline;
        }
    </style>
</div>