@extends('layouts.app')
<style>
    .my_container {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        max-width: 800px!important;
        margin: 20px auto;
        background-color: #dadada;
        border-radius: 10px;
        padding: 20px;
    }
    .table_posts {
        border: 1px solid gray;
        margin: 5px 0;
    }
    .my_btn_group {
        padding: 0 25px;
    }
    .post {
        width: 100%;
        border: 1px solid gray;
        display: flex;
        justify-content: space-between;
        padding: 0 10px;
        background-color: white;border-radius: 8px;
        margin: 5px 0;
    }
    .edit_btn {
        background-color: green;
        color: white;
        font-size: 14px;
        border-radius: 8px;
        padding: 5px 10px;
        transition: 0.3s;
        margin: 10px 0;
    }
    .title {
        font-size: 20px;
        font-weight: 700;
    }
    .edit_btn:hover {
        background-color: #004900;
    }
    .creat_btn {
        background-color: orangered;
        color: white;
        font-size: 14px;
        border-radius: 8px;
        padding: 5px 10px;
        transition: 0.3s;
        margin: 10px 0;
    }
    .creat_btn:hover {
        background-color: #912600;
    }
    .delete_btn {
        background-color: red;
        color: white;
        font-size: 14px;
        border-radius: 8px;
        padding: 5px 10px;
        transition: 0.3s;
        margin: 10px 0;
    }
    .delete_btn:hover {
        background-color: #a80000;
    }
</style>
@section('content')
    <div class="container my_container">
        <h1 class="title">Добро пожаловать в панель управления</h1>

        <a href="{{ route('posts.create') }}" class="creat_btn">Создать новый пост</a>
        @foreach ($posts as $post)
            <div class="post">
                <tr class="table_posts">
                    <td>{{ $post->title }}</td>
                    <td>
                        {{ $post->content }}
                        <img src="{{ asset('storage/public/' . $post->image) }}" alt="post image" width="150px">
                    </td>
                    <td class="my_btn_group">
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <a href="{{ route('posts.edit', $post) }}" class="edit_btn">Редактировать</a>
                            <button type="submit" class="delete_btn">Удалить</button>
                        </form>
                    </td>
                </tr>
            </div>
        @endforeach
    </div>
@endsection
