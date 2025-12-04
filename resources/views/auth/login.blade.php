@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded shadow">
        <h2 class="text-2xl font-bold text-center">Login</h2>
        @if (session('success'))
            <div class="mb-4 text-green-600">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-3 py-2 border rounded">
            </div>
            <div class="mb-6">
                <label for="password" class="block mb-1">Password</label>
                <input id="password" type="password" name="password" required class="w-full px-3 py-2 border rounded">
            </div>
            <button type="submit" class="w-full py-2 font-bold text-white bg-green-600 rounded hover:bg-green-700">Login</button>
        </form>
    </div>
</div>
@endsection
