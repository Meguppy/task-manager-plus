@extends('layouts.app')
@section('content')
    <div class="container col-md-6">

        @if (session('flash_message'))
            <div class="container col-8 text-danger">
                {{ session('flash_message') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" style="color:red">
                {{ session('error') }}
            </div>
        @endif

        <h1 class="my-3 fs-4 fw-normal">All Tasks</h1>
        <hr>

        {{-- プルダウン検索 --}}
        <form action="{{ route('tasks.index') }}" method="get" class="form-inline d-inline-flex w-100 mb-4">
            <!-- 条件選択 -->
            <select name="filter" id="filter" class="form-select me-3" style="width: 200px;">
                @foreach ($filters as $key => $value)
                    <option value="{{$key}}" @if ($filter == $key) selected @endif>{{$value['label']}}</option>
                @endforeach
            </select>

            <!-- 検索ボタン -->
            <button type="submit" class="btn btn-primary">絞り込み</button>
        </form>

        <form action="{{ route('tasks.done') }}" method="post">
            @csrf
            @method('put')
            @foreach ($tasks as $task)
                <!-- タスク -->
                <div class="rounded mb-2 py-2 px-3 bg-light d-flex justify-content-center align-items-center">
                    <div class="form-check col-8 d-flex align-items-center">
                        <input class="form-check-input" type="checkbox" name="done[]" value="{{ $task->id }}"
                            id="task-{{ $task->id }}">
                        <label class="form-check-label ps-3 w-100 d-block" for="task-{{ $task->id }}">
                            <div>
                                {{-- タスク名 --}}
                                <div class="">
                                    <p class="fs-6">
                                        {{ $task->name }}
                                    </p>
                                </div>
                                <div class="d-flex gap-2">
                                    {{-- 期限 --}}
                                    <div class="">
                                        <p class="fs-7 text-danger ms-1">
                                            @if ($task->deadline_at)
                                            {{ $task->deadline_at_formatted }}
                                            @endif
                                        </p>
                                    </div>
                                    {{-- 担当者名 --}}
                                    <div class="">
                                        <p class="fs-7 text-body-tertiary">
                                            {{ $task->user_name }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                    {{-- 編集 --}}
                    {{-- <div class="col-1 col-sm-2"> --}}
                        <a href="{{ route('tasks.edit', ['id' => $task->id]) }}" class="col-2 d-flex justify-content-center align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" height="14" width="14" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"/></svg>
                        </a>
                    {{-- </div> --}}
                    {{-- 削除 --}}
                    {{-- <div class="col-1 col-sm-2"> --}}

                            <button type="button" class="col-2 btn text-body-tertiary"
                                onclick="remove({{$task->id}})"><svg xmlns="http://www.w3.org/2000/svg" height="14" width="12.25" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg></button>
                    {{-- </div> --}}
                </div>
            @endforeach
            <div class="m-3 col-12">
                <button class="btn btn-primary px-5" type="submit">完了</button>
            </div>
            {{-- ペジネーションリンク --}}
            <div>
                {{ $tasks->links() }}
            </div>
        </form>
    </div>
    <form id="deleteForm" action="" method="POST" class="inline">
        @csrf
        @method('delete')
    </form>
    <hr class="my-5">

    <div class="container col-md-6" style="min-height: 80vh">
        <h2 class="my-4 fs-4">完了済タスク</h2>
        <hr>

        @foreach ($doneTasks as $task)
        <!-- タスク -->
        <div class="rounded mb-2 py-2 px-3 bg-light d-flex justify-content-center align-items-center" >
            <div class="form-check col-8 d-flex align-items-center">
                <svg  onclick="undone({{$task->id}})" xmlns="http://www.w3.org/2000/svg" height="14" width="12.25" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>
                <label class="form-check-label ps-3" for="task-{{ $task->id }}">
                    <div>
                        {{-- タスク名 --}}
                        <div class="">
                            <p class="fs-6">
                                {{ $task->name }}
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            {{-- 期限 --}}
                            <div class="">
                                <p class="fs-7 text-danger ms-1">
                                    @if ($task->deadline_at)
                                    {{ $task->deadline_at_formatted }}
                                    @endif
                                </p>
                            </div>
                            {{-- 担当者名 --}}
                            <div class="">
                                <p class="fs-7 text-body-tertiary">
                                    {{ $task->user_name }}
                                </p>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
            {{-- 編集 --}}
            <div class="col-2">
                <a href="{{ route('tasks.edit', ['id' => $task->id]) }}" class="btn text-body-tertiary"><svg xmlns="http://www.w3.org/2000/svg" height="14" width="14" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"/></svg></a>
            </div>
            {{-- 削除 --}}
            <div class="col-2">

                    <button type="button" class="btn text-body-tertiary"
                        onclick="remove({{$task->id}})"><svg xmlns="http://www.w3.org/2000/svg" height="14" width="12.25" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg></button>
            </div>
        </div>
        @endforeach

        {{-- ペジネーションリンク --}}
        <div>
            {{ $doneTasks->links() }}
        </div>
    </div>
    <form id="updateForm" action="" method="POST" class="inline">
        @csrf
        @method('put')
    </form>

    <script>
        function remove(taskId) {
            if(!confirm('本当に削除しますか？')){

                return;
            }

            const form = document.getElementById('deleteForm');
            const url = "{{ route('tasks.destroy', ['id' => '###id###']) }}";
            form.action = url.replace('###id###',taskId);
            form.submit();
        }

        function undone(taskId) {
            if(!confirm('未完了に戻しますか？')){
                return;
            }

            const form = document.getElementById('updateForm');
            const url = "{{ route('tasks.undone', ['id' => '###id###']) }}";
            form.action = url.replace('###id###',taskId);
            form.submit();
        }
    </script>
@endsection('content')
