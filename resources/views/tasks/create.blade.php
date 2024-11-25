@extends('layouts.app')
@section('title', 'タスク管理 - タスク登録')
@section('content')

    <div class="container col-6">
        <h1 class="my-4 fs-4 fw-normal">タスク登録</h1>
        <form action="{{ route('tasks.store') }}" method="post">
            @csrf
            <div class="row bg-light p-5">
                <div class="mb-3 col-12">
                    <label for="name" class="form-label">タスク名 <span class="badge rounded-pill bg-danger">必須</span></label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="タスクを記入" maxlength="15">
                    @error('name')
                    <div style="color: red;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 col-12">
                    <label for="deadline_at" class="form-label">期限</label>
                    <input type="date" class="form-control" name="deadline_at" id="deadline_at">
                </div>

                <div class="mb-3 col-12">
                    <label for="user_id" class="form-label">担当者</label>
                    <select class="form-select" aria-label="Default select example" name="user_id" id="user_id">
                        <option value="" selected>--</option>
                        @foreach ($users as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                    <div style="color: red;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 col-12">
                    <button class="btn btn-primary px-5" type="submit">登録</button>
                </div>

            </div>
        </form>
    </div>

@endsection('content')
