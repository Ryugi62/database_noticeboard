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

    <!-- 이 부분에 HeaderComponent가 삽입됨 -->
    <h1>Hello Edit Page</h1>

    <?php
    include './Components/FooterComponent.php';
    ?>
</body>

<script>

</script>

</html>