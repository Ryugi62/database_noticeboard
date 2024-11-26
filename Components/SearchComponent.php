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