<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Task extends Model
{
    protected $fillable = ['name', 'user_id', 'deadline_at'];
    protected function casts(): array{
        return [
            'deadline_at' => 'date'
        ];
    }
    // @todo 戻す
    // protected $appends = ['deadline_at_formatted', 'is_overdue'];

    // 期限日（表示用）
    protected function deadlineAtFormatted(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes)=> Carbon::parse($attributes['deadline_at'])->isoFormat('M/D(ddd)'),
        );
    }

    // // 期限切れフラグ
    protected function isOverdue(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes){
                return $attributes['deadline_at'] ? Carbon::parse($attributes['deadline_at'])->isBefore(now()) : false;
            }
        );
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


    public function user(){
        return $this->belongsTo(User::class);
    }
}
