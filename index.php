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
                <strong class="page-title">게시판 리스트</strong>

                <?php
                include './Components/SearchComponent.php';
                ?>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="40" class="index">No</th>
                            <th class="title">제목</th>
                            <th width="150" class="user">글쓴이</th>
                            <th width="100" class="create_date">작성시간</th>
                            <th width="100" class="watch_count">조회수</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        // 게시물 데이터 가져오기
                        $sql = "SELECT id, title, user_id, created_at FROM posts ORDER BY created_at DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            $index = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "
                                <tr>
                                    <td>{$index}</td>
                                    <td class='post-title'><a href='/PostDetail.php?id={$row['id']}'>{$row['title']}</a></td>
                                    <td>{$row['user_id']}</td>
                                    <td>" . date('Y.m.d', strtotime($row['created_at'])) . "</td>
                                    <td>0</td>
                                </tr>
                                ";
                                $index++;
                            }
                        } else {
                            echo "
                            <tr>
                                <td colspan='5'>게시물이 없습니다.</td>
                            </tr>
                            ";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <a href="/CreatePost.php"><button class="create-post-button">글 작성</button></a>
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

        .table-container {
            min-height: 580px;
        }

        .page-header {
            height: 31px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
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

        .create-post-button {
            color: white;
            display: flex;
            margin-left: auto;
            background-color: #2a2a2a;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }
    }
</style>

</html>
