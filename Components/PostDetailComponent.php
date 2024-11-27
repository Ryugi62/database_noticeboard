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
        <h3>첨부파일:</h3>
        <?php if (count($attachments) > 0): ?>
            <ul>
                <?php foreach ($attachments as $file): ?>
                    <li>
                        <a href="<?= htmlspecialchars($file); ?>" class="attachment-link" download>
                            <?= htmlspecialchars(basename($file)); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>첨부파일이 없습니다.</p>
        <?php endif; ?>
    </div>

    <div class="post-detail-footer-buttons">
        <a href="/"><button class="go-post-list-button">글 목록</button></a>
        <a href="/EditPost.php?id=<?= $post_id; ?>"><button class="go-post-edit-button">글 수정</button></a>
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

    <!-- 스타일을 직접 적용 -->
    <style>
        .post-title {
            margin: 0;
        }

        .go-post-edit-button {
            color: white;
            margin: auto;
            display: flex;
            background-color: #2a2a2a;
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
            padding: 0;
            list-style-type: none;
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