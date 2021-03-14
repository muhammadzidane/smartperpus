<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        nav {
            background-color: pink;
            display: flex;
            justify-content: space-between;
        }

        ul {
            margin: 0;
            padding: 0;
            overflow: hidden;
            display: flex;
            list-style-type: none;
        }

        ul:first-child {
            flex-grow: 1;
        }

        li {
            text-decoration: none;
            transition-duration: 240ms;
            padding: 20px;
        }

        li a {
            text-decoration: none;
        }

        li:hover {
            background-color: red;
        }

        .con {
            padding: 15px;
            margin: 20px 10px;
            display: flex;
            flex-wrap: wrap;
            background-color: pink;
            width: 600px;
            height: 300px;
        }

        #categories > div {
            position: absolute;
            /* top: 81%; */
            border-top: 2px solid black;
            width: 35%;
            height: 200px;
            background-color: #f8fafc;
            box-shadow: 0px 0px 1.2px black;
            z-index: 1;
        }

        #categories > div > div {
            margin: 15px;
        }

        .category {
            display: inline-block;
            padding: 5px 8px;
            font-size: 18px;
            /* background-color:hotpink; */
        }

        .con div {
            margin: 5px;
            width: 120px;
            height: 90px;
        }

        .red {
            background-color: red;
        }

        .green {
            background-color: green;
        }

        .blue {
            background-color: blue;
        }

    </style>
</head>

<body>
    <nav>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">Komik</a></li>
            <li><a href="#">Blog</a></li>
            <li><a href="#">Blog</a></li>
            <li><a href="#">Blog</a></li>
        </ul>

        <ul>
            <li><a href="#">Masuk</a></li>
            <li><a href="#">Register</a></li>
            <li><a href="#">Login</a></li>
        </ul>
    </nav>

    <main>
        <li id="categories" class="nav-item">
            <a class="nav-link text-body" href="#">Kategori <i class="fas fa-caret-down ml-1"></i></a>
            <div>
                <div>
                    @for($i = 0; $i < 9; $i++)
                        <span class="category">Komik</span>
                    @endfor
                </div>
            </div>
        </li>
    </main>
</body>

</html>
