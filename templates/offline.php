<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= __('You are offline :(','mobili') ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            -webkit-font-smoothing: antialiased;
        }
        img {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        h1, div {
            text-align: center;
        }
        .page-inner{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
            max-width: 100%;
            width: 100%;
        }
        .page-inner svg{
            width: 200px;
            height: 200px;
            max-width: 100%;
            opacity: 0.1
        }
        .page-inner .page-title{
            color: #666;
            font-size: 32px;
            font-weight: 400;
            margin-bottom: 15px
        }
        .page-inner .page-desc{
            color: #888;
            font-size: 18px;
            font-weight: 400;
            margin-bottom: 15px
        }
        .page-inner .button{
            border: none;
            background: #FB951F;
            color: #fff;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
        }
        .page-inner .button:hover{
            opacity: .8
        }
    </style>
</head>
<body>
<div class="page-inner">
    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 128 128" xml:space="preserve" class=""><g><g xmlns="http://www.w3.org/2000/svg"><g><path d="m126.1 28.3c-16.6-16.5-38.6-25.7-62.1-25.7s-45.5 9.2-62.1 25.7c-2.5 2.6-2.5 6.8 0 9.3 2.6 2.5 6.8 2.5 9.3 0 14-14.1 32.8-21.8 52.7-21.8s38.7 7.7 52.7 21.8c2.5 2.5 6.8 2.5 9.3 0 2.7-2.6 2.7-6.7.2-9.3z" fill="#000000" data-original="#000000" class=""></path><path d="m20.6 46.9c-2.5 2.6-2.5 6.8 0 9.3 2.6 2.5 6.8 2.5 9.3 0 18.7-18.7 49.3-18.7 68.1 0 2.6 2.6 6.8 2.6 9.5 0 2.5-2.6 2.5-6.8 0-9.3-24-24-62.9-24-86.9 0z" fill="#000000" data-original="#000000" class=""></path><path d="m64 55.4c-9.3 0-18.2 3.7-24.8 10.3-2.5 2.6-2.5 6.8 0 9.3 2.6 2.5 6.8 2.5 9.3 0 4.2-4.2 9.6-6.4 15.4-6.4s11.4 2.3 15.4 6.4c2.5 2.6 6.7 2.6 9.3 0 2.5-2.6 2.5-6.8 0-9.3-6.4-6.7-15.3-10.3-24.6-10.3z" fill="#000000" data-original="#000000" class=""></path><path d="m64 81.8c-11.1 0-20 9.1-20 20.1 0 11.1 8.9 20 20 20s20-8.9 20-20-8.9-20.1-20-20.1zm0 34.8c-1.3 0-2.3-1.1-2.3-2.3 0-1.3 1.1-2.3 2.3-2.3 1.3-.1 2.3 1 2.3 2.3s-1 2.3-2.3 2.3zm2.2-9.8c-.1 1.3-1.2 2.1-2.3 2.1-1.3 0-2.2-1-2.3-2.2l-1.1-15.7c-.1-2 1.5-3.8 3.6-3.8 1.9.1 3.5 1.7 3.5 3.6 0 .3-.4 4.8-1.4 16z" fill="#000000" data-original="#000000" class=""></path></g></g></g></svg>
    <h1 class="page-title"><?= __('You are offline :(','mobili') ?></h1>
    <p class="page-desc"><?= __('Make sure you have an internet connection.','mobili') ?></p>
    <button class="button" onclick="window.location.reload()"><?= __('Reload page','mobili') ?></button>
</div>
</body>
</html>
