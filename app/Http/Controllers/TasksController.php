<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
// 日付
use Carbon\Carbon;

class TasksController extends Controller
{
    // マイタスク画面表示
    public function myTasks(Request $request)
    {
        // 1ページの表示数
        $n = 10;
        // 絞り込みプルダウンの値取得
        $filter = $request->input('filter', 'all');
        // ログイン中のユーザー
        $user_id = 1; // 一旦固定値

        // 未完了タスク取得
        $tasks = $this->getNotDoneTasks($filter,$n,$user_id);

        // 完了済みタスク取得
        $doneTasks = $this->getDoneTasks('all',$n,$user_id);

        // 日付フォーマット変更
        $this->getFormattedTasks($tasks);
        $this->getFormattedTasks($doneTasks);

        return view('tasks.my',  ['tasks' => $tasks, 'doneTasks' => $doneTasks, 'filter' => $filter]);
    }

    // オールタスク画面表示
    public function index(Request $request)
    {
        // 1ページの表示数
        $n = 10;
        // 絞り込みプルダウンの値取得
        $filter = $request->input('filter', 'all');

        // 未完了タスク取得
        $tasks = $this->getNotDoneTasks($filter,$n);

        // 完了済みタスク取得
        $doneTasks = $this->getDoneTasks('all',$n);

        // 日付フォーマット変更
        $this->getFormattedTasks($tasks);
        $this->getFormattedTasks($doneTasks);

        return view('tasks.index',  ['tasks' => $tasks, 'doneTasks' => $doneTasks, 'filter' => $filter]);
    }

    // タスク登録画面表示
    public function create()
    {
        $users = User::select()->get();
        return view('tasks.create',  ['users' => $users]);
    }

    // タスク登録処理
    public function store(Request $request)
    {
        // バリデーションチェック
        $validation = $this->getValidationRules();
        $request->validate($validation['rules'],$validation['params']);

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
    public function edit($id)
    {
        // $id が null または空でないことを確認
        if (!$id) {
            return redirect()->route('tasks.index');
        }

        // Tasks テーブルに id が存在するかを確認
        $task = Task::find($id);

        // タスクが存在しない場合はリダイレクト
        if (!$task) {
            return redirect()->route('tasks.index');
        }

        $task = Task::where('id', $id)->first();
        $users = User::select()->get();
        return view('tasks.edit',  ['task' => $task, 'users' => $users]);
    }

    // タスク更新処理
    public function update(Request $request, $id)
    {
        // バリデーションチェック
        $validation = $this->getValidationRules();
        $request->validate($validation['rules'],$validation['params']);

        // PUT送信データを受け取る
        $name = $request->input('name');
        $deadline_at = $request->input('deadline_at');
        $user_id = $request->input('user_id');

        // DB更新
        Task::where('id', $id)->update([
            'name' => $name,
            'deadline_at' => $deadline_at,
            'user_id' => $user_id,
        ]);

        return redirect()->route('tasks.index')->with('flash_message', '更新成功しました。');
    }

    // タスク削除処理
    public function destroy(Request $request, $id)
    {
        // 該当IDのタスクを取得
        $task = Task::find($id);

        // 該当タスクが存在しない場合のエラーハンドリング
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
    public function unDone(Request $request)
    {
        // チェックしたタスクのID
        $taskIds = $request->input('done', []);

        // エラー：タスク0個で完了ボタン
        if (empty($taskIds)) {
            return redirect()->route('tasks.index')->with('error', 'タスクが選択されていません');
        }

        // チェックしたIDのデータ更新
        if (!empty($taskIds)) {
            // 選択されたタスクのdone_atを空に更新
            Task::whereIn('id', $taskIds)->update(['done_at' => '']);
        }
        return redirect()->route('tasks.index')->with('flash_message', 'タスクを未完了に戻しました。');
    }


    // 未完了タスク取得
    private function getNotDoneTasks($filter, $n, $user_id="")
    {
        return $this->getTasks('notDone', $filter, $n, $user_id);
    }

    // 完了済みタスク取得
    private function getDoneTasks($filter, $n, $user_id="")
    {
        return $this->getTasks('done', $filter, $n, $user_id);
    }

    // タスク取得
    private function getTasks($status, $filter, $n, $user_id="")
    {

        // 完了または未完了タスク取得
        $tasks = Task::allTasks()->$status(); // done または notDone

        // 絞り込みプルダウン
        switch ($filter) {
            // 期限切れ
            case 'over_deadline':
                $tasks->overDeadline();
            break;
            // 期限なし
            case 'no_deadline':
                $tasks->noDeadline();
            break;
            // 担当なし
            case 'no_user':
                $tasks->noUser();
            break;
            // すべて
            case 'all':
            default:
        }

        // ユーザー絞り込み
        if(!empty($user_id)){
            $tasks->my($user_id);
        }

        return $tasks->paginate($n);

    }

    // 日付フォーマット変更
    private function getFormattedTasks($tasks){
        // 日本語のロケールに設定
        Carbon::setLocale('ja');

        // 日付フォーマット変更
        foreach ($tasks as $task) {
            if (!empty($task->deadline_at)) {
                $task->deadline_at = Carbon::parse($task->deadline_at)->translatedFormat('n/j(D)');
            }
        }
    }

    // バリデーションルール
    private function getValidationRules()
    {
        $validation = [
            'rules'=>[
                'name' => 'required|max:15',
                'user_id' => 'nullable|exists:users,id',
            ],
            'params'=>[
                'name.required' => 'タスク名は必須です。',
                'name.max' => 'タスク名は15文字以内です。',
                'user_id.exists' => '選択された担当者が無効です。',
            ],
        ];

        return $validation;
    }


}

