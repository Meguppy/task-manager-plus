<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title','ToDo')</title>

    <!-- bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">


    <style>

        p{
            margin: 0;
        }
        .fs-7 {
            font-size: 0.8rem; /* 6より小さいサイズ */
    }

    </style>
</head>

<body class="font-sans antialiased">

        <header>

            <nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ route('tasks.my') }}">ToDo</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('tasks/create') ? 'active' : '' }}"
                                    {{ Request::is('tasks/create') ? 'aria-current=page' : '' }}
                                    href="{{ route('tasks.create') }}">タスク登録</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('my/tasks') ? 'active' : '' }}"
                                    {{ Request::is('my/tasks') ? 'aria-current=page' : '' }}
                                    href="{{ route('tasks.my') }}">MyTasks</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('tasks') ? 'active' : '' }}"
                                    {{ Request::is('tasks') ? 'aria-current=page' : '' }}
                                    href="{{ route('tasks.index') }}">AllTasks</a>
                            </li>

                        </ul>
                    </div>

                        <span class="navbar-text ms-auto">ゲストユーザー</span>

                </div>
            </nav>

        </header>

        <!-- Page Content -->
        <main class="p-2">
