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
    .save_btn {
        background-color: orangered;
        color: white;
        font-size: 14px;
        border-radius: 8px;
        padding: 5px 10px;
        transition: 0.3s;
        margin: 10px 0;
    }
    .save_btn:hover {
        background-color: #912600;
    }
    .edit_form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .image_block {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    @section('content')
        <div class="container my_container">
            <h1 class="title">Редактирование профиля</h1>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="edit_form">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Имя</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}">
                </div>

                <div class="mb-3 image_block">
                    <label for="image" class="form-label">Изображение профиля</label>
                    <input type="file" name="image" class="form-control" id="image" style="display: none;" onchange="previewImage(event)">

                    @if(auth()->user()->image)
                        <label for="image">
                            <img src="{{ asset('storage/' . $user->image) }}" alt="Avatar" id="profileImage" width="150px" style="cursor: pointer;">
                        </label>
                    @else
                        <label for="image">
                            <img src="{{ asset('storage/' . $user->image) }}" alt="Avatar" id="profileImage" width="150px" style="cursor: pointer;">
                        </label>
                    @endif
                </div>

                <button type="submit" class="save_btn">Сохранить</button>
            </form>
        </div>
    @endsection
</x-app-layout>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('profileImage');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
