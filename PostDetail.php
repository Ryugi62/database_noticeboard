<?php
require_once './config/database.php'; // 데이터베이스 연결

// 게시물 ID 가져오기
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 게시물 데이터 가져오기
$post_query = "SELECT id, title, content, user_id, created_at FROM posts WHERE id = ?";
$stmt = $conn->prepare($post_query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$stmt->bind_result($id, $title, $content, $user_id, $created_at);

if (!$stmt->fetch()) {
    die("게시물이 존재하지 않습니다.");
}
$stmt->close();

// 댓글 데이터 가져오기
$comments_query = "
    SELECT id, post_id, user_id, content, parent_comment_id, created_at 
    FROM comments
    WHERE post_id = ?
    ORDER BY created_at ASC
";
$stmt = $conn->prepare($comments_query);
$stmt->bind_param("i", $post_id);
$stmt->execute();

// 결과 바인딩
$stmt->bind_result($comment_id, $comment_post_id, $comment_user_id, $comment_content, $comment_parent_id, $comment_created_at);

// 댓글 데이터를 배열로 저장
$comments = array();
while ($stmt->fetch()) {
    $comments[] = array(
        'id' => $comment_id,
        'post_id' => $comment_post_id,
        'user_id' => $comment_user_id,
        'content' => $comment_content,
        'parent_comment_id' => $comment_parent_id,
        'created_at' => $comment_created_at
    );
}
$stmt->close();

// 비교 함수 정의 (익명 함수 대신 사용)
function compareComments($a, $b)
{
    return strtotime($a['created_at']) - strtotime($b['created_at']);
}

function sortComments(&$comments)
{
    usort($comments, 'compareComments');

    foreach ($comments as &$comment) {
        if (!empty($comment['children'])) {
            sortComments($comment['children']);
        }
    }
}

function buildCommentTree($comments)
{
    $commentById = array();
    $rootComments = array();

    // 댓글을 ID를 키로 하는 배열로 변환
    foreach ($comments as $comment) {
        $comment['children'] = array();
        $commentById[$comment['id']] = $comment;
    }

    // 부모 댓글에 대댓글을 추가
    foreach ($commentById as $commentId => $comment) {
        if ($comment['parent_comment_id']) {
            $parentId = $comment['parent_comment_id'];
            if (isset($commentById[$parentId])) {
                $commentById[$parentId]['children'][] = &$commentById[$commentId];
            }
        } else {
            $rootComments[] = &$commentById[$commentId];
        }
    }

    // 루트 댓글과 그 하위 댓글들을 정렬
    sortComments($rootComments);

    return $rootComments;
}

$commentTree = buildCommentTree($comments);
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>DB 게시판 과제</title>

    <link rel="stylesheet" href="css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>
    <?php include './Components/HeaderComponent.php'; ?>

    <main>
        <div class="main-component view">
            <!-- 게시물 상세 내용 -->
            <?php include './Components/PostDetailComponent.php'; ?>

            <!-- 댓글 컴포넌트 -->
            <?php include './Components/CommentComponent.php'; ?>
        </div>
    </main>

    <?php include './Components/FooterComponent.php'; ?>
</body>

<!-- 스타일을 직접 적용 -->
<style>
    /* 공통 스타일 */
    .main-component {
        margin: 50px 0;
        height: 100%;
    }

    .page-header {
        height: 31px;
        display: flex;
        align-items: center;
        border-bottom: 1px solid black;
        padding-bottom: 16px;
        justify-content: space-between;
    }

    .page-title {
        font-size: 18px;
    }

    .post-detail-container {
        display: flex;
        min-height: 580px;
        flex-direction: column;
    }

    .post-header-container {
        gap: 8px;
        padding-top: 16px;
        border-bottom: 1px solid #eee;
    }

    .post-header-container span {
        gap: 8px;
        color: gray;
        display: flex;
        font-size: 14px;
    }

    .post-header-container p {
        margin: 8px 0;
    }

    .post-detail-footer-buttons {
        gap: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

</html>