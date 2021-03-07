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
        padding: 20px;
    }

    li a {
        text-decoration: none;
    }

     li:hover {
        background-color: red;
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
</body>

</html>
