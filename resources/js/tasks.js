displayFormat();
fetchAllTasks();

window.toggleStatus = function(taskId,isDoneAt) {
    if(!isDoneAt){
        doneTask(taskId);
        return;
    }
    unDoneTask(taskId);
}

window.remove = function(taskId) {
    if (!confirm('本当に削除しますか？')) {
        return;
    }
    deleteTask(taskId);
}

function displayFormat(){
    const format =`
<div class="container col-md-6">
    <h1 class="my-3 fs-4 fw-normal">All Tasks</h1>
    <hr>
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
</div>
<hr class="my-5">
<div class="container col-md-6" style="min-height: 80vh">
    <h2 class="my-4 fs-4">完了済タスク</h2>
    <hr>
    <div id="doneTasksContainer">
    </div>
    </div>
    `;
    const mainContainer = document.querySelector('main');
    mainContainer.innerHTML = format;
}

function fetchAllTasks(){
    fetchUnDoneTasks();
    fetchDoneTasks();
}

function fetchUnDoneTasks(){
    axios.get('http://localhost/api/tasks/undone')
    .then(response => {
        const tasksContainer = document.getElementById('tasksContainer');
        tasksContainer.innerHTML = renderTasks(response.data);
    })
    .catch(error => {
        console.error('タスクの取得に失敗しました', error);
    });
}

function fetchDoneTasks(){
    axios.get('http://localhost/api/tasks/done')
    .then(response => {
        const doneTasksContainer = document.getElementById('doneTasksContainer');
        doneTasksContainer.innerHTML = renderTasks(response.data);
    })
    .catch(error => {
        console.error('タスクの取得に失敗しました', error);
    });
}

function renderTasks(tasks) {
    return tasks.map(task => `
    <div class="rounded mb-2 py-2 px-3 bg-light d-flex justify-content-center align-items-center">
        <div class="form-check col-8 d-flex align-items-center">
        <input class="form-check-input" type="checkbox" name="done[]" id="task-${task.id}" ${task.done_at ? 'checked' : ''} onclick="toggleStatus(${task.id},${task.done_at ? true:false})">
            <label class="form-check-label ps-3 w-100 d-block" for="task-${task.id}">
                <div>
                    <p class="fs-6">${task.name}</p>
                    <div class="d-flex gap-2">
                        <p class="fs-7 ms-1 ${task.is_overdue ? 'text-danger' : ''}">${task.deadline_at_formatted}</p>
                        <p class="fs-7 text-body-tertiary">${task.user ? task.user.name : ''}</p>
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

function doneTask(taskId){
    axios.put(`http://localhost/api/tasks/${taskId}/done`)
    .then(response => {
        setTimeout(fetchAllTasks(),700);
    })
    .catch(error => {
        console.error('タスクの更新に失敗しました。', error);
    });
}

function unDoneTask(taskId){
    axios.put(`http://localhost/api/tasks/${taskId}/undone`)
    .then(response => {
        fetchAllTasks();
    })
    .catch(error => {
        console.error('タスクの更新に失敗しました。', error);
    });
}

function deleteTask(taskId){
    axios.delete(`http://localhost/api/tasks/${taskId}`)
    .then(response => {
        fetchAllTasks();
        console.log(response.data);
    })
    .catch(error => {
        console.error('タスクの削除に失敗しました。', error);
    });
}
