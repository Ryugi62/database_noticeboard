<?php
require_once './config/database.php';

// 게시물 ID 가져오기
$post_id = intval($_GET['id']);

// 기존 게시물 정보 가져오기
$post_query = "SELECT id, title, user_id, password, content FROM posts WHERE id = ?";
$stmt_post = $conn->prepare($post_query);
$stmt_post->bind_param("i", $post_id);
$stmt_post->execute();

// 결과 바인딩
$stmt_post->bind_result($id, $title, $user_id, $password, $content);
if ($stmt_post->fetch()) {
    $post = array(
        'id' => $id,
        'title' => $title,
        'user_id' => $user_id,
        'password' => $password,
        'content' => $content
    );
} else {
    echo "존재하지 않는 게시물입니다.";
    exit;
}
$stmt_post->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시판 수정페이지</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <?php require_once './config/database.php'; ?>
</head>

<body>

    <?php include './Components/HeaderComponent.php'; ?>

    <main>
        <div class="edit-post-component view">
            <div class="page-header">
                <strong class="page-title">게시판 수정페이지</strong>
            </div>

            <div class="edit-post-container">
                <!-- 수정된 form 태그 시작 -->
                <form action="/api/EditPost.php?id=<?= $post_id ?>" method="post" enctype="multipart/form-data">
                    <div class="editor-container">
                        <div class="post-title-container">
                            <span class="post-info-container">
                                <strong class="post-id"><?= htmlspecialchars($post['user_id']); ?></strong>
                                <input type="password" name="password" class="post-password-input"
                                    placeholder="기존 비밀번호를 입력하세요">
                            </span>
                            <input type="text" name="title" class="post-title-input" placeholder="기존 제목을 입력해주세요"
                                value="<?= htmlspecialchars($post['title']); ?>">
                        </div>
                        <textarea name="content" id="editor"><?= htmlspecialchars($post['content']); ?></textarea>

                        <!-- 첨부파일 업로드 -->
                        <div class="file-upload-container">
                            <label for="attachment">첨부파일:</label>
                            <input type="file" name="attachment" id="attachment">
                        </div>
                    </div>

                    <div class="post-button-container">
                        <a href="/"><button type="button" class="cancel">취소</button></a>
                        <button type="submit" class="submit">수정</button>
                    </div>
                </form>
                <!-- 수정된 form 태그 종료 -->
            </div>
        </div>
    </main>

    <?php include './Components/FooterComponent.php'; ?>
</body>

<script>
    // CKEditor 초기화
    ClassicEditor.create(document.querySelector('#editor')).catch(error => { console.error(error); });

    document.querySelector("form").addEventListener("submit", function (e) {
        const userId = document.querySelector("[name='user_id']").value.trim();
        const password = document.querySelector("[name='password']").value.trim();
        const title = document.querySelector("[name='title']").value.trim();

        // 유효성 검사
        if (!userId || !password || !title) {
            e.preventDefault(); // 폼 제출 중단
            alert("아이디, 비밀번호, 제목을 모두 입력해주세요!");
        }
    });
</script>

<style>
    .edit-post-component {
        margin: 50px 0;
        height: 100%;
    }

    .edit-post-container {
        min-height: 630px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        height: 31px;
    }

    .page-title {
        font-size: 18px;
    }

    .editor-container {
        padding: 16px 0;
        border-top: 1px solid black;
    }

    .post-title-container {
        margin-bottom: 16px;
    }

    .post-info-container {
        gap: 8px;
        display: flex;
        width: 50%;
        margin-bottom: 8px;
    }

    .post-id {
        display: flex;
        align-items: center;
    }

    .post-password-input,
    .post-title-input {
        flex: 1;
        border: 1px solid #ccced1;
        display: flex;
        padding: 8px 16px;
        font-weight: bold;
    }

    .post-title-input {
        flex: 1;
        width: 80%;
        height: 36px;
        border: 1px solid #ccced1;
        font-size: 18px;
        font-weight: bold;
    }

    .ck-editor {
        margin-top: 24px;
    }

    .ck-editor__editable_inline {
        height: 450px;
        padding: 8px 16px;
    }

    .post-button-container {
        gap: 6px;
        display: flex;
        align-items: center;
        justify-content: right;
    }

    .cancel {
        text-shadow: 0px -1px #474747;
        border-color: #797979;
    }

    .submit {
        color: white;
        background: #2a2a2a;
        text-shadow: 0px -1px #1d2761;
        border-color: #29367c;
    }

    .file-upload-container {
        margin-top: 16px;
    }

    .file-upload-container label {
        margin-right: 8px;
    }
</style>

</html>