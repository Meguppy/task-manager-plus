@extends('layouts.app')
@section('content')
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


    <div class="container col-md-8 my-3">
        <h1 class="my-4 fs-4 fw-normal">My Tasks</h1>

        {{-- プルダウン検索 --}}
        <form action="{{ route('tasks.my') }}" method="get" class="form-inline d-inline-flex w-100 mb-4">
            <!-- 条件選択 -->
            {{-- <label for="filter" class="mr-2">条件を選択:</label> --}}
            <select name="filter" id="filter" class="form-select me-3" style="width: 200px;">
                <option value="all" @if ($filter == 'all') selected @endif>すべて</option>
                <option value="over_deadline" @if ($filter == 'over_deadline') selected @endif>期限切れ</option>
                <option value="no_deadline" @if ($filter == 'no_deadline') selected @endif>期限なし</option>
                <option value="no_user" @if ($filter == 'no_user') selected @endif>担当者未設定</option>
            </select>

            <!-- 検索ボタン -->
            <button type="submit" class="btn btn-primary">絞り込み</button>

        </form>


        <form action="{{ route('tasks.done') }}" method="post">
            @csrf
            @method('put')
            <table class="table table-sm">
                <thead class="table-light">
                    <tr>
                        <th>タスク名</th>
                        <th>ニックネーム</th>
                        <th>期限</th>
                        <th>編集</th>
                        <th>削除</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @foreach ($tasks as $task)
                        <tr>
                            {{-- タスク名 --}}
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="done[]"
                                        value="{{ $task->id }}" id="task-{{ $task->id }}">
                                    <label class="form-check-label" for="task-{{ $task->id }}">
                                        {{ $task->name }}
                                    </label>
                                </div>
                            </td>

                            {{-- 担当者名 --}}
                            <td>{{ $task->user_name }}</td>

                            {{-- 期限 --}}
                            <td>{{ $task->deadline_at }}</td>

                            {{-- 編集 --}}
                            <td>
                                <a href="{{ route('tasks.edit', ['id' => $task->id]) }}" class="btn btn-light">編集</a>
                                {{-- <svg class="icon icon-pen text-light" width="24" height="24">
                                    <use xlink:href="#icon-pen"></use>
                                </svg> --}}
                            </td>

                            {{-- 削除 --}}
                            <td>
                                <form action="{{ route('tasks.destroy', ['id' => $task->id]) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-light"
                                        onclick="return confirm('本当に削除しますか？')">削除</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
