<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DB 게시판 과제</title>

    <link rel="stylesheet" href="css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100..900&display=swap" rel="stylesheet">

    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

    <?php
    require_once './config/database.php';
    ?>
</head>

<body>

    <?php
    include './Components/HeaderComponent.php';
    ?>

    <main>
        <div class="create-post-component view">
            <div class="page-header">
                <strong class="page-title">게시판 작성페이지</strong>
            </div>
            
            <div class="create-post-container">

                <div class="editor-continer">
                    <div class="post-title-continer">
                        <span class="post-info-continer">
                            <input type="id" class="post-id-input" placeholder="아이디를 입력하세요">
                            <input type="password" class="post-password-input" placeholder="비밀번호를 입력하세요">
                        </span>
                        <input type="text" class="post-title-input" placeholder="제목을 입력해주세요">
                        <p>※ 쉬운 비밀번호를 입력하면 타인의 수정, 삭제가 쉽습니다.</p>
                        <p>※ 음란물, 차별, 비하, 혐오 및 초상권, 저작권 침해 게시물은 민, 형사상의 책임을 질 수 있습니다.</p>
                    </div>
                    <textarea name="content" id="editor"></textarea>
                </div>

                <div class="post-button-continer">
                    <a href="/"><button class="cancle">취소</button></a>
                    <a href="/api/CreatePost.php"><button class="submit">작성</button></a>
                </div>
            </div>
        </div>
    </main>

    <?php
    include './Components/FooterComponent.php';
    ?>
</body>

<script>
    // CKEditor 초기화
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });
</script>


<style>
    .create-post-component {
        margin: 50px 0;
        height: 100%;

        * {
            /* border: 1px solid red; */
        }

        .create-post-container {
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

        .editor-continer {
            padding: 16px 0;
            border-top: 1px solid black;
        }

        .post-title-continer {
            margin-bottom: 16px;

            p {
                color: gray;
                font-size: 14px;
                margin: 4px 0;
            }
        }

        .post-info-continer {
            gap: 8px;
            display: flex;
            width: 50%;
            margin-bottom: 8px;
        }


        .post-id-input,
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

        .post-button-continer {
            gap: 6px;
            display: flex;
            align-items: center;
            justify-content: right;

            button {
                width: 80px;
                color: #fff;
                height: 40px;
                cursor: pointer;
                border: 1px solid;
            }
        }

        .cancle {
            background: #999;
            text-shadow: 0px -1px #474747;
            border-color: #797979;
        }

        .submit {
            background: #2a2a2a;
            text-shadow: 0px -1px #1d2761;
            border-color: #29367c;
        }
    }
</style>

</html>