<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class TasksController extends Controller
{
    // マイタスク画面表示
    public function myTasks(Request $request)
    {
        // 絞り込みプルダウンの値取得
        $filter = $request->input('filter', 'all');

        // ログイン中のユーザー
        $user_id = 1; // 一旦固定値

        // 未完了タスク取得
        $tasks = $this->getNotDoneTasks($filter, $user_id);

        // 完了済みタスク取得
        $doneTasks = $this->getDoneTasks('all', $user_id);

        return view('tasks.my',  ['tasks' => $tasks, 'doneTasks' => $doneTasks, 'filter' => $filter, 'filters' => Config::get('const.filter')]);
    }

    // オールタスク画面表示
    public function index(Request $request)
    {
        // 絞り込みプルダウンの値取得
        $filter = $request->input('filter', 'all');

        // 未完了タスク取得
        $tasks = $this->getNotDoneTasks($filter);

        // 完了済みタスク取得
        $doneTasks = $this->getDoneTasks('all');

        return view('tasks.index',  ['tasks' => $tasks, 'doneTasks' => $doneTasks, 'filter' => $filter, 'filters' => Config::get('const.filter')]);
    }

    // タスク登録画面表示
    public function create()
    {
        $users = User::select()->get();
        return view('tasks.create',  ['users' => $users]);
    }

    // タスク登録処理
    public function store(StoreTaskRequest $request)
    {

        // POST送信データを受け取る
        $name = $request->input('name');
        $deadline_at = $request->input('deadline_at');
        $user_id = $request->input('user_id');

        // DB登録
        $task = Task::create(
            [
                'name' => $name,
                'deadline_at' => $deadline_at,
                'user_id' => $user_id,
            ],
        );

        return redirect()->route('tasks.index')->with('flash_message', '登録成功しました。');
    }

    // タスク編集画面表示
    public function edit($taskId)
    {
        // $taskId が null または空でないことを確認
        if (!$taskId) {
            return redirect()->route('tasks.index');
        }

        // Tasks テーブルに id が存在するかを確認
        $task = Task::find($taskId);

        // タスクが存在しない場合はリダイレクト
        if (!$task) {
            return redirect()->route('tasks.index');
        }

        $task = Task::where('id', $taskId)->first();
        $users = User::select()->get();
        return view('tasks.edit',  ['task' => $task, 'users' => $users]);
    }

    // タスク更新処理
    public function update(StoreTaskRequest $request, $taskId)
    {

        // PUT送信データを受け取る
        $name = $request->input('name');
        $deadline_at = $request->input('deadline_at');
        $user_id = $request->input('user_id');

        // DB更新
        Task::where('id', $taskId)->update([
            'name' => $name,
            'deadline_at' => $deadline_at,
            'user_id' => $user_id,
        ]);

        return redirect()->route('tasks.index')->with('flash_message', '更新成功しました。');
    }

    // タスク削除処理
    public function destroy($taskId)
    {
        // 該当IDのタスクを取得
        $task = Task::find($taskId);

        // 該当タスクが存在しない
        if (!$task) {
            return redirect()->route('tasks.index')->with('flash_message', 'タスクが見つかりませんでした。');
        }

        // タスク削除処理
        $task->delete();

        return redirect()->route('tasks.index')->with('flash_message', 'タスクを削除しました。');
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

        // チェックしたIDのデータ更新
        if (!empty($taskIds)) {
            // 選択されたタスクのdone_atに現在の日付を更新
            Task::whereIn('id', $taskIds)->update(['done_at' => Carbon::now()]);
        }
        return redirect()->route('tasks.index')->with('flash_message', 'タスクを完了しました。');
    }

    // タスク未完了処理
    public function unDone($taskId)
    {

        // 該当IDのタスクを取得
        $task = Task::find($taskId);

        // 該当タスクが存在しない
        if (!$task) {
            return redirect()->route('tasks.index')->with('flash_message', 'タスクが見つかりませんでした。');
        }

        Task::where('id', $taskId)->update(['done_at' => null]);

        return redirect()->route('tasks.index')->with('flash_message', 'タスクを未完了に戻しました。');
    }


    // 未完了タスク取得
    private function getNotDoneTasks($filter, $user_id = "")
    {
        return $this->getTasks('notDone', $filter,  $user_id);
    }

    // 完了済みタスク取得
    private function getDoneTasks($filter, $user_id = "")
    {
        return $this->getTasks('done', $filter, $user_id);
    }

    // タスク取得
    private function getTasks($status, $filter, $user_id = "")
    {

        // 完了または未完了タスク取得
        $tasks = Task::allTasks()->$status(); // done または notDone

        // プルダウン絞り込み
        $filters = Config::get('const.filter');
        if (isset($filters[$filter]['method']) && $filters[$filter]['method']) {
            $method = $filters[$filter]['method'];
            $tasks->$method();
        }

        // ユーザー絞り込み
        $tasks->when($user_id, function ($q, $user_id) {
            $q->my($user_id);
        });

        return $tasks->paginate(config('const.paginate.display_count'));
    }

}
