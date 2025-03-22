@extends('layouts.app')

<style>
    .my_container {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        max-width: 500px!important;
        margin: 20px auto;
        background-color: #dadada;
        border-radius: 10px;
        padding: 20px;
    }
    .title {
        font-size: 20px;
        font-weight: 700;
    }
    .mb-3 {
        display: flex;
        flex-direction: column;
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
    .edit_btn:hover {
        background-color: #004900;
    }
</style>

@section('content')
    <div class="container my_container">
        <h1 class="title">Edit the post</h1>

        <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $post->title) }}" required>
                @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="5" required>{{ old('content', $post->content) }}</textarea>
                @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <input type="file" name="image" class="form-control" id="image" style="display: none;" onchange="previewImage(event)">

                <label for="image">
                    <img src="{{ $post->image ? asset('storage/public/' . $post->image) : asset('default-avatar.png') }}"
                         alt="Phone image" id="profileImage" width="150px" style="cursor: pointer;">
                    @error('image')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </label>
            </div>

            <button type="submit" class="edit_btn">Save</button>
        </form>
    </div>
@endsection

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profileImage').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>
