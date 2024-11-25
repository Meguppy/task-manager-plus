<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name', 'user_id', 'deadline_at'];
    protected function casts(): array{
        return [
            'deadline_at' => 'date:M/D(ddd)'
        ];
    }

    // deadline_atのアクセサを定義
    public function getDeadlineAtFormattedAttribute()
    {
        return $this->deadline_at->isoFormat('M/D(ddd)');
    }


    // 未完了タスクを取得するスコープ
    public function scopeAllTasks($query)
    {
        return $query
            ->leftJoin('users', 'tasks.user_id', '=', 'users.id')
            ->select('tasks.*', 'users.name as user_name')
            ->orderBy('tasks.deadline_at', 'asc');
    }

    // ログインユーザーのタスク絞り込み
    public function scopeMy($query, $user_id)
    {
        return $query
            ->where('user_id', $user_id);
    }

    // 未完了タスク絞り込み
    public function scopeNotDone($query)
    {
        return $query
            ->whereNull('done_at');
    }

    // 完了済みタスク絞り込み
    public function scopeDone($query)
    {
        return $query
            ->whereNotNull('done_at');
    }

    // 期限切れタスク絞り込み
    public function scopeOverDeadline($query)
    {
        return $query
            ->where('deadline_at', '<', Carbon::today());
    }

    // 期限なしタスク絞り込み
    public function scopeNoDeadline($query)
    {
        return $query
            ->whereNull('deadline_at');
    }

    // 担当者未設定タスク絞り込み
    public function scopeNoUser($query)
    {
        return $query
            ->whereNull('user_id');
    }
}
