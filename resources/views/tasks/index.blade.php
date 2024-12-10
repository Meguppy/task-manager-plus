@extends('layouts.app')
@section('content')
    <div class="container col-md-6">

        @if (session('flashMessage'))
            <div class="container col-8 text-danger">
                {{ session('flashMessage') }}
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
            <select name="selectedFilter" id="selectedFilter" class="form-select me-3" style="width: 200px;">
                @foreach (config('const.filter') as $key => $value)
                    <option value="{{ $key }}" @if ($selectedFilter == $key) selected @endif>
                        {{ $value['label'] }}</option>
                @endforeach
            </select>

            <!-- 検索ボタン -->
            <button type="submit" class="btn btn-primary">絞り込み</button>
        </form>

        <div id="tasksContainer">
        </div>

        <form action="{{ route('tasks.done') }}" method="post">
            @csrf
            @method('put')

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
        <div id="doneTasksContainer">
        </div>

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
        // APIで未完了タスク取得＆表示
        document.addEventListener('DOMContentLoaded', function() {
            fetchTasks();
        });

        function fetchTasks() {
            axios.get('http://localhost/api/tasks')
                .then(response => {
                    // console.log(response.data.tasks.data); @todo 後で消す
                    // 未完了タスク
                    const tasksContainer = document.getElementById('tasksContainer');
                    tasksContainer.innerHTML = renderTasks(response.data.tasks.data);

                    // 完了済みタスク
                    const doneTasksContainer = document.getElementById('doneTasksContainer');
                    doneTasksContainer.innerHTML = renderDoneTasks(response.data.doneTasks.data);

                })
                .catch(error => {
                    console.error('タスクの取得に失敗しました', error);
                });
        }

        // @todo 編集アイコンのurl "http://localhost/"
        function renderTasks(tasks) {
            return tasks.map(task => `
    <div class="rounded mb-2 py-2 px-3 bg-light d-flex justify-content-center align-items-center">
        <div class="form-check col-8 d-flex align-items-center">
            <input class="form-check-input" type="checkbox" name="done[]" value="${task.id}" id="task-${task.id}" ${task.is_done ? 'checked' : ''} onclick="toggleTaskStatus(${task.id})">
            <label class="form-check-label ps-3 w-100 d-block" for="task-${task.id}">
                <div>
                    <p class="fs-6">${task.name}</p>
                    <div class="d-flex gap-2">
                        <p class="fs-7 ms-1 ${task.is_overdue ? 'text-danger' : ''}">${task.deadline_at_formatted}</p>
                        <p class="fs-7 text-body-tertiary">${task.user_name ? task.user_name : ''}</p>
                    </div>
                </div>
            </label>
        </div>
        <a href="http://localhost/tasks/${task.id}/edit" class="col-2 d-flex justify-content-center align-items-center">
            <!-- 編集アイコン -->
            <svg xmlns="http://www.w3.org/2000/svg" height="14" width="14" viewBox="0 0 512 512">
                <path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z" />
            </svg>
        </a>
        <button type="button" class="col-2 btn text-body-tertiary" onclick="remove(${task.id})">
            <!-- 削除アイコン -->
            <svg xmlns="http://www.w3.org/2000/svg" height="14" width="12.25" viewBox="0 0 448 512">
                <path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z" />
            </svg>
        </button>
    </div>
`).join('');
        }

        function renderDoneTasks(tasks) {
            return tasks.map(task => `
    <div class="rounded mb-2 py-2 px-3 bg-light d-flex justify-content-center align-items-center">
        <div class="form-check col-8 d-flex align-items-center">
                <svg onclick="undone(${task.id})" xmlns="http://www.w3.org/2000/svg" height="14" width="12.25" viewBox="0 0 448 512">
                    <path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" />
                </svg>
            <label class="form-check-label ps-3 w-100 d-block" for="task-${task.id}">
                <div>
                    <p class="fs-6">${task.name}</p>
                    <div class="d-flex gap-2">
                        <p class="fs-7 ms-1 ${task.is_overdue ? 'text-danger' : ''}">${task.deadline_at_formatted}</p>
                        <p class="fs-7 text-body-tertiary">${task.user_name ? task.user_name : ''}</p>
                    </div>
                </div>
            </label>
        </div>
        <a href="http://localhost/tasks/${task.id}/edit" class="col-2 d-flex justify-content-center align-items-center">
            <!-- 編集アイコン -->
            <svg xmlns="http://www.w3.org/2000/svg" height="14" width="14" viewBox="0 0 512 512">
                <path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z" />
            </svg>
        </a>
        <button type="button" class="col-2 btn text-body-tertiary" onclick="remove(${task.id})">
            <!-- 削除アイコン -->
            <svg xmlns="http://www.w3.org/2000/svg" height="14" width="12.25" viewBox="0 0 448 512">
                <path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z" />
            </svg>
        </button>
    </div>
`).join('');
        }
    </script>

    <script>
        function remove(taskId) {
            if (!confirm('本当に削除しますか？')) {

                return;
            }

            const form = document.getElementById('deleteForm');
            const url = "{{ route('tasks.destroy', ['task' => '###id###']) }}";
            form.action = url.replace('###id###', taskId);
            form.submit();
        }

        function undone(taskId) {
            if (!confirm('未完了に戻しますか？')) {
                return;
            }

            const form = document.getElementById('updateForm');
            const url = "{{ route('tasks.undone', ['task' => '###id###']) }}";
            form.action = url.replace('###id###', taskId);
            form.submit();
        }
    </script>
@endsection('content')
