<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

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
            {{-- @include('layouts.navigation') --}}

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
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"">
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                </div>
            </nav>

        </header>
        <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
        </form>
        <script>
            function logout() {
                const form = document.getElementById('logoutForm');
                form.submit();
            }
        </script>
        <!-- Page Content -->
        <main class="p-2">
