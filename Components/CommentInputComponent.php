<?php
// CommentInputComponent.php

// $isEditMode: 댓글 수정 모드 여부 (true/false)
// $isReplyMode: 대댓글 작성 모드 여부 (true/false)
// $parent_comment_id: 대댓글인 경우 부모 댓글의 ID
// $edit_comment_id: 수정할 댓글의 ID
// $currentContent: 수정 모드일 때 기존 댓글 내용

// 기본값 설정
$isEditMode = isset($isEditMode) ? $isEditMode : false;
$isReplyMode = isset($isReplyMode) ? $isReplyMode : false;
$parent_comment_id = isset($parent_comment_id) ? $parent_comment_id : null;
$edit_comment_id = isset($edit_comment_id) ? $edit_comment_id : null;
$currentContent = isset($currentContent) ? $currentContent : '';

// 만약 수정 모드나 대댓글 모드가 아니면, 변수 초기화
if (!$isEditMode && !$isReplyMode) {
    $parent_comment_id = null;
    $edit_comment_id = null;
    $currentContent = '';
}
?>

<form action="/api/SubmitComment.php" method="POST" class="comment-form">
    <input type="hidden" name="post_id" value="<?= htmlspecialchars($post_id); ?>">

    <?php if ($isEditMode): ?>
        <!-- 댓글 수정인 경우 comment_id를 숨겨서 전달 -->
        <input type="hidden" name="comment_id" value="<?= htmlspecialchars($edit_comment_id); ?>">
    <?php elseif ($isReplyMode): ?>
        <!-- 대댓글인 경우 parent_comment_id를 전달 -->
        <input type="hidden" name="parent_comment_id" value="<?= htmlspecialchars($parent_comment_id); ?>">
    <?php endif; ?>

    <div class="comment-info-input-component">
        <!-- 아이디와 비밀번호 입력 필드 -->
        <input type="text" name="user_id" placeholder="아이디 입력" required>
        <input type="password" name="password" placeholder="비밀번호 입력" required>
    </div>
    <textarea name="content" placeholder="댓글 내용을 작성하세요" required><?= htmlspecialchars($currentContent) ?></textarea>
    <div class="button-items">
        <?php if ($isEditMode): ?>
            <button class="comment-button submit" type="submit">수정</button>
            <button class="comment-button cancel" type="button"
                onclick="cancelEdit(<?= htmlspecialchars($edit_comment_id); ?>)">취소</button>
        <?php elseif ($isReplyMode): ?>
            <button class="comment-button submit" type="submit">등록</button>
            <button class="comment-button cancel" type="button"
                onclick="hideCommentInput(<?= htmlspecialchars($parent_comment_id); ?>)">취소</button>
        <?php else: ?>
            <button class="comment-button submit" type="submit">등록</button>
        <?php endif; ?>
    </div>
</form>

<style>
    .comment-form {
        gap: 10px;
        display: flex;
        flex-direction: row;
    }

    .comment-info-input-component {
        gap: 10px;
        display: flex;
        flex-direction: column;
    }

    .comment-info-input-component input {
        flex: 1;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .comment-info-input-component input:focus {
        border-color: #555;
        outline: none;
    }

    .comment-form textarea {
        flex: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        resize: none;
        height: 80px;
    }

    .comment-form textarea:focus {
        border-color: #555;
        outline: none;
    }

    .button-items {
        gap: 10px;
        display: flex;
        flex-direction: column;
    }

    .comment-button {
        flex: 1;
        padding: 10px 16px;
        border-radius: 4px;
        cursor: pointer;
        box-sizing: border-box;
        border: none;
    }

    .comment-button.submit {
        background-color: #2a2a2a;
        color: white;
    }

    .comment-button.submit:hover {
        background-color: #444;
    }

    .comment-button.cancel {
        background-color: #ccc;
    }

    .comment-button.cancel:hover {
        background-color: #ddd;
    }
</style>