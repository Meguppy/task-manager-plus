@extends('layouts.app')
@section('title', 'タスク管理 - タスク編集')
@section('content')


<div class="container col-6">
    <h1 class="my-4 fs-4 fw-normal">タスク編集</h1>
    <form action="{{ route('tasks.update', ['id' => $task->id]) }}" method="post">
        @csrf
        @method('PUT')
        <div class="row bg-light p-5">
            <div class="mb-3 col-12">
                <label for="name" class="form-label">タスク名 <span class="badge rounded-pill bg-danger">必須</span></label>
                <input type="text" class="form-control" name="name" id="name" value="{{$task->name}}" placeholder="タスクを記入" maxlength="15">
                @error('name')
                <div style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 col-12">
                <label for="deadline_at" class="form-label">期限</label>
                <input type="date" class="form-control" name="deadline_at" id="deadline_at" value="{{$task->deadline_at}}">
            </div>

            <div class="mb-3 col-12">
                <label for="user_id" class="form-label">担当者</label>
                <select class="form-select" aria-label="Default select example" name="user_id" id="user_id">
                    <option value="">--</option>
                    @foreach ($users as $user)
                    <option value="{{$user->id}}" @if ($user->id == $task->user_id)selected @endif>{{$user->name}}</option>
                    @endforeach
                </select>
                @error('user_id')
                <div style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 col-12">
                <button class="btn btn-primary px-5" type="submit">更新</button>
            </div>

        </div>
    </form>
</div>


@endsection('content')
