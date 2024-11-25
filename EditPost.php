<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DB 게시판 과제</title>

    <link rel="stylesheet" href="css/style.css">

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