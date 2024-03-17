@extends('layouts.main')

@section('content')
<div class="w-2/5 mx-auto">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-800">Create Role</h2>
        <a href="{{ route('roles.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Back to Roles
        </a>
    </div>

    <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                Role Name*
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" id="name" name="name" type="text" placeholder="Role Name" value="{{ old('name') }}">
            @error('name')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                Description(Optional)
            </label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror" id="description" name="description" placeholder="Role Description">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Create
            </button>
        </div>
    </form>
</div>
@endsection


