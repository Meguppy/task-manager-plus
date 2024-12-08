<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTaskRequest;

class TasksController extends Controller
{
    // マイタスク画面表示
    public function myTasks(Request $request)
    {
        // 絞り込みプルダウンの値取得
        $selectedFilter = $request->input('selectedFilter', 'all');

        // ログイン中のユーザー
        $user_id = Auth::id();

        // 未完了タスク取得
        $tasks = $this->getNotDoneTasks($selectedFilter, $user_id);

        // 完了済みタスク取得
        $doneTasks = $this->getDoneTasks('all', $user_id);

        return view('tasks.my', compact('tasks', 'doneTasks', 'selectedFilter'));
    }

    // オールタスク画面表示
    public function index(Request $request)
    {
        // 絞り込みプルダウンの値取得
        $selectedFilter = $request->input('selectedFilter', 'all');

        // 未完了タスク取得
        $tasks = $this->getNotDoneTasks($selectedFilter);

        // 完了済みタスク取得
        $doneTasks = $this->getDoneTasks('all');

        return view('tasks.index', compact('tasks', 'doneTasks', 'selectedFilter'));
    }

    // タスク登録画面表示
    public function create()
    {
        $users = User::all();
        return view('tasks.create', compact('users'));
    }

    // タスク登録処理
    public function store(StoreTaskRequest $request)
    {
        // POST送信データを受け取る
        $name = $request->input('name');
        $deadline_at = $request->input('deadline_at');
        $user_id = $request->input('user_id');

        // DB登録
        $task = Task::create([
            'name' => $name,
            'deadline_at' => $deadline_at,
            'user_id' => $user_id,
        ]);

        $flashMessage = '登録成功しました。';
        return redirect()->route('tasks.index')->with(compact('flashMessage'));
    }

    // タスク編集画面表示
    public function edit(Task $task)
    {
        $users = User::all();
        return view('tasks.edit', compact('task','users'));
    }

    // タスク更新処理
    public function update(StoreTaskRequest $request, Task $task)
    {
        $task->fill($request->all())->save();

        $flashMessage = '更新成功しました。';
        return redirect()->route('tasks.index')->with(compact('flashMessage'));
    }

    // タスク削除処理
    public function destroy(Task $task)
    {
        Task::destroy($task->id);

        $flashMessage = 'タスクを削除しました。';
        return redirect()->route('tasks.index')->with(compact('flashMessage'));
    }

    // タスク完了処理
    public function done(Request $request)
    {
        // チェックしたタスクのID
        $taskIds = $request->input('done', []);

        // エラー：タスク0個で完了ボタン
        if (empty($taskIds)) {
            return redirect()->route('tasks.index')->with('error', 'タスクが選択されていません。');
        }

        // 選択されたタスクのdone_atに現在の日付を更新
        Task::whereIn('id', $taskIds)->update(['done_at' => Carbon::now()]);

        $flashMessage = 'タスクを完了しました。';
        return redirect()->route('tasks.index')->with(compact('flashMessage'));
    }

    // タスク未完了処理
    public function unDone(Task $task)
    {
        Task::where('id', $task->id)->update(['done_at' => null]);

        $flashMessage = 'タスクを未完了に戻しました。';
        return redirect()->route('tasks.index')->with(compact('flashMessage'));
    }

    // 未完了タスク取得
    private function getNotDoneTasks($selectedFilter, $user_id = '')
    {
        return $this->getTasks('notDone', $selectedFilter, $user_id);
    }

    // 完了済みタスク取得
    private function getDoneTasks($selectedFilter, $user_id = '')
    {
        return $this->getTasks('done', $selectedFilter, $user_id);
    }

    // タスク取得
    private function getTasks($status, $selectedFilter, $user_id = '')
    {
        // 完了または未完了タスク取得
        $tasks = Task::allTasks()->$status(); // done または notDone

        // プルダウン絞り込み
        $filters = config('const.filter');
        if (isset($filters[$selectedFilter]['method']) && $filters[$selectedFilter]['method']) {
            $method = $filters[$selectedFilter]['method'];
            $tasks->$method();
        }

        // ユーザー絞り込み
        $tasks->when($user_id, function ($q, $user_id) {
            $q->my($user_id);
        });

        return $tasks->paginate(config('const.paginate.display_count'));
    }
}
