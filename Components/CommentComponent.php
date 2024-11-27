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
                        <a href="#" class="edit-comment" onclick="showEditCommentInput(<?= $comment['id']; ?>)">수정</a>
                        <a href="#" class="delete-comment" onclick="deleteComment(<?= $comment['id']; ?>)">삭제</a>
                    </span>
                </div>
                <div class="comment-body" <?php if($depth == 0) { ?>onclick="toggleCommentInput(<?= $comment['id']; ?>)"<?php } ?>>
                    <p id="comment-body-<?= $comment['id']; ?>"><?= nl2br(htmlspecialchars($comment['content'])); ?></p>
                </div>

                <!-- 댓글 수정 폼 -->
                <div id="edit-comment-form-<?= $comment['id']; ?>" class="edit-comment-form" style="display:none; margin-left: <?= ($depth + 1) * 40 ?>px;">
                    <?php
                    // 수정 모드임을 나타내는 변수 설정
                    $isEditMode = true;
                    $edit_comment_id = $comment['id'];
                    $currentContent = $comment['content'];
                    // 불필요한 변수 초기화
                    $parent_comment_id = null;
                    $isReplyMode = false;
                    include './Components/CommentInputComponent.php';
                    // 변수 초기화
                    unset($isEditMode, $edit_comment_id, $currentContent);
                    ?>
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
                    // 대댓글 작성 모드임을 나타내는 변수 설정
                    $parent_comment_id = $comment['id'];
                    $isReplyMode = true;
                    // 불필요한 변수 초기화
                    $isEditMode = false;
                    $currentContent = null;
                    include './Components/CommentInputComponent.php';
                    // 변수 초기화
                    unset($parent_comment_id, $isReplyMode);
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
        // 새로운 댓글 작성 모드
        $parent_comment_id = null;
        $isReplyMode = false;
        $isEditMode = false;
        $currentContent = null;
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

function showEditCommentInput(commentId) {
    var form = document.getElementById('edit-comment-form-' + commentId);
    var body = document.getElementById('comment-body-' + commentId);
    form.style.display = 'block';
    body.style.display = 'none';
}

function cancelEdit(commentId) {
    var form = document.getElementById('edit-comment-form-' + commentId);
    var body = document.getElementById('comment-body-' + commentId);
    form.style.display = 'none';
    body.style.display = 'block';
}

function deleteComment(commentId) {
        const password = prompt("댓글 비밀번호를 입력하세요:");

        if (password) {
            // 비밀번호가 입력된 경우
            fetch("/api/DeleteComment.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    comment_id: commentId,
                    password: password,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        alert(data.message);
                        window.location.href = `/PostDetail.php?id=${data.post_id}`;
                    } else {
                        alert(data.message);
                    }
                })
                .catch((error) => {
                    alert("댓글 삭제 중 오류가 발생했습니다.");
                    console.error("Error:", error);
                });
        } else {
            alert("비밀번호를 입력해주세요.");
        }
    }
</script>

<style>
/* 기존 스타일 코드 */

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
