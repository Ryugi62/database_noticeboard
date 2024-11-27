<head>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=search" />
</head>

<div id="search-component">
    <form action="index.php" method="GET">
        <select name="" id="">
            <option value="제목 + 내용">제목 + 내용</option>
        </select>
        <input type="text" name="search" placeholder="검색..."
            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">
            <span class="material-symbols-outlined">search</span>
        </button>
    </form>
</div>

<script>

</script>

<style>
    #search-component {
        display: flex;
        text-align: center;

        form {
            display: flex;
        }

        * {
            /* border: 1px solid red; */
        }

        select {
            margin-right: 4px;
        }

        input {
            height: 18px;
            padding: 8px;
        }

        button {
            width: 36px;
            height: 36px;
            color: black;
            border: unset;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: unset;
            border-top: 1px solid #ccced1;
            border-right: 1px solid #ccced1;
            border-bottom: 1px solid #ccced1;
        }

        button:hover {
            background-color: #4f4f4f0a;
        }

        button:active {
            background-color: #4646460a;
        }
    }
</style>