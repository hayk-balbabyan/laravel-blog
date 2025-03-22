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

    .title {
        font-size: 20px;
        font-weight: 700;
    }

    .table_title {
        border: 1px solid gray;
        margin: 5px 0;
        display: flex;
        justify-content: space-around;
        background-color: white;
        padding: 5px 0;
        border-radius: 8px;
    }

    .table_posts {
        border: 1px solid gray;
        display: flex;
        justify-content: space-around;
        position: relative;
        background-color: white;
        margin: 5px 0;
        border-radius: 8px;
    }

    .post_like {
        position: absolute;
        bottom: 0;
        right: 5px;
    }

    .table {
        width: 100%;
    }

    .asc_desc {
        width: 100%;
        max-width: 400px;
        display: flex;
        justify-content: space-around;
        margin: 5px 0;
    }

    .asc_desc a {
        background-color: orange;
        color: white;
        padding: 5px 10px;
        font-weight: 600;
        transition: 0.3s;
    }

    .asc_desc a:hover {
        background-color: darkorange;
    }

    .delete_comm {
        color: white;
        background-color: red;
        border-radius: 8px;
        padding: 5px;
        font-size: 14px;
        transition: 0.3s;
    }
    .delete_comm:hover {
        background-color: #8a0101;
    }
    .add_comm {
        color: white;
        background-color: green;
        padding: 5px;
        font-size: 14px;
        border-radius: 8px;
        transition: 0.3s;
    }
    .add_comm:hover {
        background-color: #004a00;
    }
    .comments_toggle {
        cursor: pointer;
    }
    .comments_block {
        position: absolute;
        z-index: 2;
        background-color: #000000a1;
        color: white;
        padding: 5px 7px;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
    .active {
        opacity: 1!important;
    }
    .comment-box {
        display: flex;
        justify-content: space-between;
    }

</style>

@section('content')
    <div class="container my_container">
        <h2 class="title">Posts list</h2>

        @if ($posts->isEmpty())
            <p>No posts found.</p>
        @else
            Order by - {{  $newSort = $sort == 'asc'?  'desc' :'asc' }}
            <table class="table">
                <thead>
                <tr class="table_title">
                    <th><a href="{{ route('posts.index', ['sort' => $newSort , 'order' => 'title']) }}">Title</a></th>
                    <th><a href="{{ route('posts.index', ['sort' => $newSort , 'order'=> 'content']) }}">Action</a></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($posts as $post)
                    <tr class="table_posts">
                        <td>{{ $post->title }}</td>
                        <td>
                            {{ $post->content }}
                            <img src="{{ asset('storage/public/' . $post->image) }}" alt="post image" width="150px">
                        </td>
                        <td class="post_like">
                            <form action="{{ $post->isLikedByUser() ? route('posts.unlike', $post) : route('posts.like', $post) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn {{ $post->isLikedByUser() ? 'btn-danger' : 'btn-primary' }}">
                                    {{ $post->isLikedByUser() ? '❤️' : '🤍' }} {{ $post->likes->count() }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <div>
                                <div>
                                    @if (Auth::check())
                                        <form action="{{ route('comments.store', $post) }}" method="POST">
                                            @csrf
                                            <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="3" required></textarea>
                                            @error('content')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <button type="submit" class="add_comm">Add</button>
                                            <h3 class="comments_toggle">Comments {{ $post->comments->count() }}</h3>
                                        </form>
                                    @else
                                        <p>Please <a href="{{ route('login') }}">login</a>, to leave comment.</p>
                                    @endif
                                </div>
                                <div class="comments_block">
                                    @foreach ($post->comments as $comment)
                                        <div class="comment-box">
                                            <p><strong>{{ $comment->user->name }}</strong> {{ $comment->created_at->diffForHumans() }}</p>
                                            <p>{{ $comment->content }}</p>

                                            @if (Auth::check() && Auth::id() === $comment->user_id)
                                                <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="delete_comm">Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <script>
        let comments = document.querySelectorAll('.comments_toggle');
        comments.forEach(comment => {
            comment.addEventListener('click', function() {
                let commentBlock = comment.closest('td').querySelector('.comments_block');
                commentBlock.classList.toggle('active')
            })
        })

    </script>
@endsection



