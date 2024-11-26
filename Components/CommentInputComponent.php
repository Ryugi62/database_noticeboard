<div class="comment-input-component">
    <div class="comment-info-input-component">
        <input type="text" class="comment-id" placeholder="아이디 입력하세요">
        <input type="password" name="" id="" class="comment-password" placeholder="비밀번호 입력하세요">
    </div>
    <textarea rows="4" cols="50" placeholder="댓글 내용을 작성하세요"></textarea>
    <button class="comment-button">등록</button>
</div>

<script>

</script>

<style>
    .comment-input-component {
        display: flex;
        gap: 6px;

        .comment-info-input-component {
            gap: 6px;
            width: 150px;
            display: flex;
            flex-direction: column;
        }

        textarea {
            flex: 1;
            height: 60px;
        }

        .comment-button {
            color: white;
            height: auto;
            background-color: #2a2a2a;
            width: 100px;
        }

        .comment {
            padding: 8px;
            border-radius: 8px;
            border-bottom: 1px solid #ccc;
        }

        .comment:last-child {
            border-bottom: 0;
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
    }
</style>

</html>