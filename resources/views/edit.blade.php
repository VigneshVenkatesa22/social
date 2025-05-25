@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Edit Profile</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Name --}}
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input value="{{ old('name', auth()->user()->name) }}" type="text" name="name" class="form-control" required>
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input value="{{ old('email', auth()->user()->email) }}" type="email" name="email" class="form-control" required>
        </div>

        {{-- Avatar --}}
        <div class="mb-3">
            <label for="avatar_image" class="form-label">Avatar Image</label><br>
            @if(auth()->user()->avatar_image)
                <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" width="100" class="mb-2 rounded-circle">
            @endif
            <input type="file" name="avatar_image" class="form-control">
        </div>

        {{-- Bio --}}
        <div class="mb-3">
            <label for="bio" class="form-label">Bio</label>
            <textarea name="bio" class="form-control" rows="4">{{ old('bio', auth()->user()->bio) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
@endsection
