<?php
// Initializing the variable to false to handle the comment input visibility.
$commentShow = false;
?>

<div class="comment">
    <div class="comment-box">
        <div class="comment-header">
            <span class="comment-author">김철수</span>
            <span class="comment-date">
                2024.01.02 08:15:10
                <a href="#">x</a>
            </span>
        </div>
        <div class="comment-body" onclick="toggleCommentInput()">
            <p>이 글 정말 유용합니다! CKEditor 사용법을 더 자세히 설명해주셔서 감사합니다.</p>
        </div>
    </div>

    <div class="re-comment">
        <div class="re-comment-header">
            <span class="re-comment-author">김철수</span>
            <span class="re-comment-date">
                2024.01.02 08:15:10
                <a href="#">x</a>
            </span>
        </div>
        <div class="re-comment-body">
            <p>이 글 정말 유용합니다! CKEditor 사용법을 더 자세히 설명해주셔서 감사합니다.</p>
        </div>
    </div>

    <div id="re-comment-input" style="display: none;">
        <?php
        // The comment input will be included only if $commentShow is true.
        include './Components/CommentInputComponent.php';
        ?>
    </div>
</div>

<script>
    // Function to toggle the visibility of the comment input form
    function toggleCommentInput() {
        var commentInput = document.getElementById('re-comment-input');

        // Toggle the display style between 'none' and 'block'
        if (commentInput.style.display === 'none' || commentInput.style.display === '') {
            commentInput.style.display = 'block';
        } else {
            commentInput.style.display = 'none';
        }
    }
</script>

<style>
    .comment,
    .re-comment {
        padding: 8px;
    }

    .comment {
        border-bottom: 1px solid #ccc;
    }

    .re-comment,
    #re-comment-input {
        margin-left: 30px;
    }

    .comment-header,
    .re-comment-header {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        color: gray;
    }

    .comment-date,
    .re-comment-date {
        gap: 8px;
        display: flex;
        align-items: center;
    }

    .comment-author,
    .re-comment-author {
        font-weight: bold;
    }

    .comment-body,
    .re-comment-body {
        margin-top: 4px;
    }

    .comment-body {
        cursor: pointer;
    }

    .
</style>