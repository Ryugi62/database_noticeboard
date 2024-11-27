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
    require_once './config/database.php'; // 데이터베이스 연결
    ?>
</head>

<body>
    <?php
    include './Components/HeaderComponent.php'; // 헤더 컴포넌트 포함
    ?>

    <main>
        <div class="main-component view">
            <div class="page-header">
                <strong class="page-title">게시판 리스트</strong>

                <?php
                include './Components/SearchComponent.php'; // 검색 컴포넌트 포함
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
                        // 검색어 처리
                        $search = isset($_GET['search']) ? $_GET['search'] : '';

                        // 페이지 번호 가져오기 (기본값 1)
                        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                        $posts_per_page = 15; // 한 페이지에 표시할 게시물 수
                        $offset = ($page - 1) * $posts_per_page;

                        // 전체 게시물 수 가져오기 (검색어가 있으면 제목 또는 내용에서 검색)
                        if ($search) {
                            $total_query = "SELECT COUNT(*) as total FROM posts WHERE title LIKE ? OR content LIKE ?";
                            $stmt = $conn->prepare($total_query);
                            $search_like = "%$search%";
                            $stmt->bind_param('ss', $search_like, $search_like);
                            $stmt->execute();
                            $total_result = $stmt->get_result();
                        } else {
                            $total_query = "SELECT COUNT(*) as total FROM posts";
                            $total_result = $conn->query($total_query);
                        }
                        $total_row = $total_result->fetch_assoc();
                        $total_posts = $total_row['total'];
                        $total_pages = ceil($total_posts / $posts_per_page);

                        // 현재 페이지 게시물 가져오기 (검색어가 있으면 제목 또는 내용에서 검색)
                        if ($search) {
                            $sql = "SELECT id, title, user_id, created_at, view_count 
                                    FROM posts 
                                    WHERE title LIKE ? OR content LIKE ? 
                                    ORDER BY created_at DESC 
                                    LIMIT $posts_per_page OFFSET $offset";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param('ss', $search_like, $search_like);
                            $stmt->execute();
                            $result = $stmt->get_result();
                        } else {
                            $sql = "SELECT id, title, user_id, created_at, view_count 
                                    FROM posts 
                                    ORDER BY created_at DESC 
                                    LIMIT $posts_per_page OFFSET $offset";
                            $result = $conn->query($sql);
                        }

                        if ($result->num_rows > 0) {
                            $index = $offset + 1; // 현재 페이지에서 시작 번호
                            while ($row = $result->fetch_assoc()) {
                                // 검색어가 있으면 제목 하이라이트
                                $highlighted_title = $row['title'];
                                if ($search) {
                                    $highlighted_title = preg_replace('/(' . preg_quote($search, '/') . ')/i', '<span class="highlight">$1</span>', $row['title']);
                                }
                                echo "
                                <tr>
                                    <td>{$index}</td>
                                    <td class='post-title'><a href='/PostDetail.php?id={$row['id']}'>$highlighted_title</a></td>
                                    <td>{$row['user_id']}</td>
                                    <td>" . date('Y.m.d', strtotime($row['created_at'])) . "</td>
                                    <td>{$row['view_count']}</td>
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

            <div class="table-container-footer">
                <!-- 페이지 네비게이션 -->
                <div class="pagination">
                    <?php
                    // 첫 번째 페이지 링크
                    echo $page > 1 ? "<a href='?page=1' class='first'>«</a>" : "<a href='#' class='first disabled'>«</a>";

                    // 이전 페이지 링크
                    echo $page > 1 ? "<a href='?page=" . ($page - 1) . "' class='prev'>‹</a>" : "<a href='#' class='prev disabled'>‹</a>";

                    // 페이지 번호 표시 (현재 페이지 기준)
                    // 항상 5개의 페이지를 표시하기 위해서 start와 end를 계산
                    $start = max(1, $page - 2); // 시작 페이지 (최소값 1)
                    $end = min($total_pages, $page + 2); // 끝 페이지 (최대값 total_pages)
                    
                    // 5개로 맞추기 위해 총 페이지수가 5개 미만인 경우 처리
                    if ($end - $start < 4) {
                        if ($start == 1) {
                            $end = min($total_pages, $start + 4); // 끝 페이지가 총 페이지 수보다 크지 않게
                        } else {
                            $start = max(1, $end - 4); // 시작 페이지가 1보다 작지 않게
                        }
                    }

                    // 페이지 번호 링크
                    for ($i = $start; $i <= $end; $i++) {
                        if ($i == $page) {
                            echo "<a href='?page=$i' class='active'>$i</a>"; // 현재 페이지는 active
                        } else {
                            echo "<a href='?page=$i'>$i</a>";
                        }
                    }

                    // 다음 페이지 링크
                    echo $page < $total_pages ? "<a href='?page=" . ($page + 1) . "' class='next'>›</a>" : "<a href='#' class='next disabled'>›</a>";

                    // 마지막 페이지 링크
                    echo $page < $total_pages ? "<a href='?page=$total_pages' class='last'>»</a>" : "<a href='#' class='last disabled'>»</a>";
                    ?>
                </div>

                <a href="/CreatePost.php"><button class="create-post-button">글 작성</button></a>
            </div>

        </div>
    </main>

    <?php
    include './Components/FooterComponent.php'; // 푸터 컴포넌트 포함
    ?>
</body>

<style>
    .main-component {
        margin: 50px 0;
        height: 100%;
    }

    .table-container {
        min-height: 705px;
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

    .table-container-footer {
        display: flex;
        padding-top: 20px;
        border-top: 1px solid #ccc;
        align-items: center;
        justify-content: right;
        position: relative;
    }

    /* 페이지 네비게이션 스타일 */
    .pagination {
        position: absolute;
        text-align: center;
        top: 50%;
        left: 50%;
        transform: translate(-50%, 0);
    }

    .pagination a {
        margin: 0 5px;
        text-decoration: none;
        color: #333;
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        transition: background-color 0.3s, color 0.3s;
    }

    .pagination a.active {
        background-color: #2a2a2a;
        color: #fff;
        border-color: #2a2a2a;
    }

    .pagination a:hover {
        background-color: #ddd;
    }

    .pagination a.first,
    .pagination a.last {
        font-weight: bold;
    }

    .pagination a.prev,
    .pagination a.next {
        font-weight: bold;
    }

    /* 비활성화된 페이지 네비게이션 버튼 */
    .pagination a.disabled {
        color: #ccc;
        pointer-events: none;
        /* 클릭할 수 없게 함 */
        background-color: #f0f0f0;
        border-color: #ddd;
    }

    .highlight {
        background-color: yellow;
        font-weight: bold;
    }
</style>

</html>