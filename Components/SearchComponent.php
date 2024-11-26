<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=search" />
</head>

<div id="search-component">
    <input type="text">
    <button>
        <span class="material-symbols-outlined">
            search
        </span>
    </button>
</div>

<script>

</script>

<style>
    #search-component {
        display: flex;
        text-align: center;

        * {
            /* border: 1px solid red; */
        }

        button {
            color: black;
            width: 50px;
            height: 31px;
            border: unset;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: unset;
            border-top: 1px solid black;
            border-right: 1px solid black;
            border-bottom: 1px solid black;
        }

        button:hover {
            background-color: #4f4f4f0a;
        }

        button:active {
            background-color: #4646460a;
        }

        button:focus {
            border-top: 1px solid black;
            border-right: 1px solid black;
            border-bottom: 1px solid black;
        }
    }
</style>