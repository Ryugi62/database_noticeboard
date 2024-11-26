<!-- CommentComponent.php -->

<?php
// 페이지 상단에서 현재 게시물 ID를 가져옵니다.
$post_id = $_GET['id']; // 또는 다른 방법으로 가져옵니다.
?>

<div class="comment-component">
    <h3>댓글</h3>
    <hr>

    <!-- 댓글 출력 -->
    <?php
    function renderComments($comments, $depth = 0, $post_id) {
        $count = count($comments);
        $i = 0;

        foreach ($comments as $comment) {
            $i++;
            $marginLeft = $depth * 40;
            // depth가 0이면 top-level 클래스를 추가
            $commentClass = 'comment';
            if ($marginLeft == 0) {
                $commentClass .= ' top-level';
            }
            // 마지막 댓글이면 last-comment 클래스 추가
            if ($i == $count && $depth == 0) {
                $commentClass .= ' last-comment';
            }
            ?>
            <div class="<?= $commentClass ?>" style="margin-left: <?= $marginLeft ?>px;">
                <div class="comment-header">
                    <span class="comment-author"><?= htmlspecialchars($comment['user_id']); ?></span>
                    <span class="comment-date">
                        <?= date("Y.m.d H:i:s", strtotime($comment['created_at'])); ?>
                        <a href="#" class="edit-comment">수정</a>
                        <a href="#" class="delete-comment">삭제</a>
                    </span>
                </div>
                <div class="comment-body" <?php if($depth == 0) { ?>onclick="toggleCommentInput(<?= $comment['id']; ?>)"<?php } ?>>
                    <p><?= nl2br(htmlspecialchars($comment['content'])); ?></p>
                </div>
            </div>
            <?php
            // 자식 댓글이 있으면 재귀적으로 출력
            if (!empty($comment['children'])) {
                renderComments($comment['children'], $depth + 1, $post_id);
            }

            // 깊이가 0인 댓글에 대해서만 대댓글 입력 폼을 마지막에 출력
            if ($depth == 0) {
                ?>
                <!-- 대댓글 입력 폼 -->
                <div id="re-comment-input-<?= $comment['id']; ?>" class="re-comment-input" style="display: none; margin-left: <?= ($depth + 1) * 40 ?>px;">
                    <?php
                    // 부모 댓글 ID를 전달하기 위해 $parent_comment_id 설정
                    $parent_comment_id = $comment['id'];
                    include './Components/CommentInputComponent.php';
                    ?>
                </div>
                <?php
            }
        }
    }

    renderComments($commentTree, 0, $post_id);
    ?>

    <hr>

    <!-- 댓글 작성 -->
    <div class="comment-input-component">
        <?php
        // 새로운 댓글 작성 시에는 $parent_comment_id를 null로 설정
        $parent_comment_id = null;
        include './Components/CommentInputComponent.php';
        ?>
    </div>
</div>

<script>
function toggleCommentInput(commentId) {
    var commentInput = document.getElementById('re-comment-input-' + commentId);

    if (commentInput.style.display === 'none' || commentInput.style.display === '') {
        commentInput.style.display = 'block';
    } else {
        commentInput.style.display = 'none';
    }
}

function hideCommentInput(commentId) {
    var commentInput = document.getElementById('re-comment-input-' + commentId);
    if (commentInput) {
        commentInput.style.display = 'none';
    }
}
</script>

<!-- 스타일을 직접 적용 -->
<style>
    .comment-component {
        padding: 16px 0;
    }

    .comment {
        padding: 8px;
        border-bottom: 1px solid #ccc;
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        color: gray;
    }

    .comment-author {
        font-weight: bold;
    }

    .comment-body {
        margin-top: 4px;
    }

    /* top-level 클래스가 있는 경우 cursor를 pointer로 설정 */
    .comment.top-level .comment-body {
        cursor: pointer;
    }

    .re-comment-input {
        margin-top: 8px;
    }

    .comment-date {
        gap: 8px;
        display: flex;
    }
    
    .edit-comment,
    .delete-comment {
        color: black;
    }

    .edit-comment:hover,
    .delete-comment:hover {
        text-decoration: underline;
    }
</style>
