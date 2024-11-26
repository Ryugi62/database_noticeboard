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

            <div class="comment-component">
                <h3>댓글</h3>

                <hr>

                <div class="comment">
                    <div class="comment-header">
                        <span class="comment-author">김철수</span>
                        <span class="comment-date">2024.01.02 08:15:10</span>
                    </div>
                    <div class="comment-body">
                        <p>이 글 정말 유용합니다! CKEditor 사용법을 더 자세히 설명해주셔서 감사합니다.</p>
                    </div>
                </div>

                <div class="comment">
                    <div class="comment-header">
                        <span class="comment-author">이영희</span>
                        <span class="comment-date">2024.01.02 09:30:45</span>
                    </div>
                    <div class="comment-body">
                        <p>저도 CKEditor를 사용해봤는데, 정말 유용하더군요. 좋은 정보 감사합니다!</p>
                    </div>
                </div>

                <div class="comment">
                    <div class="comment-header">
                        <span class="comment-author">박수진</span>
                        <span class="comment-date">2024.01.02 10:45:20</span>
                    </div>
                    <div class="comment-body">
                        <p>이 글을 보고 CKEditor를 설치하고 사용해봤는데 너무 편리하네요! 추천해주셔서 감사합니다.</p>
                    </div>
                </div>

                <div class="comment">
                    <div class="comment-header">
                        <span class="comment-author">백승한</span>
                        <span class="comment-date">2024.01.02 10:45:20</span>
                    </div>
                    <div class="comment-body">
                        <p>코리안 섹시 보이</p>
                    </div>
                </div>

                <hr>

                <div class="comment-input-component">
                    <div class="comment-info-input-component">
                        <input type="text" class="comment-id" placeholder="아이디 입력하세요">
                        <input type="password" name="" id="" class="comment-password" placeholder="비밀번호 입력하세요">
                    </div>
                    <textarea rows="4" cols="50" placeholder="댓글 내용을 작성하세요"></textarea>
                    <button class="comment-button">등록</button>
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

        .comment-component {
            margin-top: 32px;
        }

        .comment-input-component {
            display: flex;
            gap: 6px;
        }

        .comment-info-input-component {
            gap: 6px;
            width: 120px;
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