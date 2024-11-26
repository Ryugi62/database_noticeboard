<!-- CommentInputComponent.php -->

<form action="/api/SubmitComment.php" method="POST" class="comment-form">
    <input type="hidden" name="post_id" value="<?= $post_id; ?>">
    <?php if ($parent_comment_id != null) { ?>
        <input type="hidden" name="parent_comment_id" value="<?= $parent_comment_id; ?>">
    <?php } ?>
    <div class="comment-info-input-component">
        <input type="text" name="user_id" placeholder="아이디 입력" required>
        <input type="password" name="password" placeholder="비밀번호 입력" required>
    </div>
    <textarea name="content" placeholder="댓글 내용을 작성하세요" required></textarea>
    <div class="button-items">
        <?php if ($parent_comment_id != null) { ?>
            <button class="comment-button cancel" type="button"
                onclick="hideCommentInput(<?= $parent_comment_id ?>)">취소</button>
        <?php } ?>
        <button class="comment-button submit" type="submit">등록</button>
    </div>
</form>


<!-- 스타일을 직접 적용 -->
<style>
    .comment-form {
        gap: 10px;
        display: flex;
        align-items: stretch;
    }

    .comment-input-component {
        display: flex;
        margin-top: 16px;
        flex-direction: column;
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

    .comment-input-component textarea {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        resize: none;
        height: 80px;
    }

    .comment-input-component textarea:focus {
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
        align-self: stretch;
        padding: 10px 16px;
        border-radius: 4px;
        cursor: pointer;
        box-sizing: border-box;
    }

    .comment-button.submit {
        background-color: #2a2a2a;
        color: white;
    }

    .comment-button.submit:hover {
        background-color: #444;
    }

    .comment-button.cancel:hover {
        background-color: #ddd;
    }

    textarea {
        flex: 1;
    }
</style>