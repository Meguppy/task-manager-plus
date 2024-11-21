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

    <h1 class="my-4 fs-4 fw-normal">All Tasks</h1>
    <hr>

    {{-- プルダウン検索 --}}
    <form action="{{ route('tasks.index') }}" method="get" class="form-inline d-inline-flex w-100 mb-4">
        <!-- 条件選択 -->
        {{-- <label for="filter" class="mr-2">条件を選択:</label> --}}
        <select name="filter" id="filter" class="form-select me-3" style="width: 200px;">
            <option value="all" @if($filter == "all")selected @endif>すべて</option>
            <option value="over_deadline" @if($filter == "over_deadline")selected @endif>期限切れ</option>
            <option value="no_deadline" @if($filter == "no_deadline")selected @endif>期限なし</option>
            <option value="no_user" @if($filter == "no_user")selected @endif>担当者未設定</option>
        </select>

        <!-- 検索ボタン -->
        <button type="submit" class="btn btn-primary">絞り込み</button>
    </form>

    <form action="{{ route('tasks.done') }}" method="post" >
        @csrf
        @method('put')
        @foreach ($tasks as $task)
        <!-- タスク -->
        <div class="row mb-3 p-3 bg-light">
            <div class="form-check col-8 d-flex align-items-center">
                    <input class="form-check-input" type="checkbox" name="done[]"
                        value="{{ $task->id }}" id="task-{{ $task->id }}">
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
                                @if ($task->deadline_at)
                                    <div class="">
                                        <p class="fs-7 text-danger ms-1">
                                            {{ $task->deadline_at }}
                                        </p>
                                    </div>
                                @endif
                                {{-- 担当者名 --}}
                                <div class="">
                                    <p class="fs-7 text-body-tertiary">
                                        {{$task->user_name }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </label>
            </div>
            {{-- 編集 --}}
            <div class="col-2">
                <a href="{{ route('tasks.edit', ['id' => $task->id]) }}" class="btn text-body-tertiary">編集</a>
            </div>
            {{-- 削除 --}}
            <div class="col-2">
                <form action="{{ route('tasks.destroy', ['id' => $task->id]) }}" method="POST" class="inline">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn text-body-tertiary"
                    onclick="return confirm('本当に削除しますか？')">削除</button>
                </form>
            </div>
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

<hr class="my-5">

<div class="container col-8">

    <h2 class="my-4 fs-4">完了済タスク</h2>
    <table class="table table-sm">
        <thead class="table-light">
            <tr>
                <th>タスク名</th>
                <th>ニックネーム</th>
                <th>期限</th>
            </tr>
        </thead>
        <tbody class="align-middle">
            @foreach ($doneTasks as $task)
                <tr>
                    {{-- タスク名 --}}
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked name=""
                                id="task-{{ $task->id }}">
                            <label class="form-check-label" for="task-{{ $task->id }}">
                                {{ $task->name }}
                            </label>
                        </div>
                    </td>
                    <td>{{ $task->user_name }}</td>
                    <td>{{ $task->deadline_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- ペジネーションリンク --}}
    <div>
        {{ $doneTasks->links() }}
    </div>
</div>

@endsection('content')
