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
        <div class="main-component view">
            <h1>Hello World</h1>
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
    .main-component {}
</style>

</html>