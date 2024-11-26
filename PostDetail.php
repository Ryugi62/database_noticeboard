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

            <div class="post-detail-continaer">
                <h2 class="post-title">대충 제목</h2>


            </div>



            <a href="/"><button class="go-post-list-button">글 목록</button></a>
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

        .post-detail-continaer {
            min-height: 580px;
        }

        .post-title {
            margin: 0;
            padding: 16px 0;
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

        table {
            width: 100%;
            text-align: center;
            border-top: 1px solid black;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px 10px;
        }

        tr {
            border-bottom: 1px solid #e5e7eb;
        }

        tbody tr:hover {
            background-color: #4f4f4f0a;
        }

        .post-title {
            text-align: left;
        }

        .go-post-list-button {
            color: white;
            margin: auto;
            display: flex;
            background-color: #2a2a2a;
        }
    }
</style>

</html>