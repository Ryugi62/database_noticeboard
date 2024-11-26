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

    <?php
    require_once './config/database.php';
    ?>
</head>

<body>

    <?php
    include './Components/HeaderComponent.php';
    ?>

    <main>
        <div class="main-component view">
            <div class="page-header">
                <strong class="page-title">게시판 상세페이지</strong>
            </div>

            <div class="post-detail-container">
                <div class="post-header-container">
                    <h2 class="post-title">대충 제목</h2>

                    <span>
                        <p>홍길동</p>
                        <p>|</p>
                        <p>2024.01.01 12:35:35</p>
                    </span>
                </div>

                <div class="post-detail-main-container">
                    <!-- 예제 내용입니다. 나중에 수정해야됩니다. -->
                    <h1>Welcome to My Blog</h1>
                    <p>This is an article about <strong>CKEditor</strong>.</p>
                    <p><em>CKEditor</em> is a WYSIWYG text editor that helps you easily format and style text. It
                        supports <u>underlined text</u>, <b>bold text</b>, and more.</p>
                    <p>Here is a list of features:</p>
                    <ul>
                        <li>Text Formatting</li>
                        <li>Image Insertion</li>
                        <li>Custom Styling</li>
                    </ul>
                    <p>Hope you enjoy using CKEditor!</p>
                    <!-- 예제 내용입니다. 나중에 수정해야됩니다. -->
                </div>
            </div>

            <div class="post-detail-footer-buttons">
                <a href="/"><button class="go-post-list-butto">글 목록</button></a>
                <a href="/EditPost.php"><button class="go-post-edit-button">글 수정</button></a>
            </div>

            <div class="post-navigation">
                <div class="previous-post">
                    <p>이전글</p>
                    <a href="/PostDetail.php" class="navigation-post-title">
                        <p>제목</p>
                    </a>
                    <p>홍길동</p>
                    <p>2024.01.01</p>
                </div>
                <div class="next-post">
                    <p>다음글</p>
                    <a href="/PostDetail.php" class="navigation-post-title">
                        <p>제목</p>
                    </a>
                    <p>홍길동</p>
                    <p>2024.01.01</p>
                </div>
            </div>
        </div>
    </main>

    <?php
    include './Components/FooterComponent.php';
    ?>
</body>

<script>

</script>

<style>
    .main-component {
        margin: 50px 0;
        height: 100%;

        * {
            /* border: 1px solid red; */
        }

        .post-title {
            margin: 0;
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
            min-height: 580px;
        }

        .post-header-container {
            gap: 8px;
            padding-top: 16px;
            border-bottom: 1px solid #eee;

            span {
                gap: 8px;
                color: gray;
                display: flex;
                font-size: 14px;
            }

            p {
                margin: 8px 0;
            }
        }


        .go-post-edit-button {
            color: white;
            margin: auto;
            display: flex;
            background-color: #2a2a2a;
        }

        .post-detail-footer-buttons {
            gap: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
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
        }
    }
</style>

</html>