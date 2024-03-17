@extends('layouts.main')

@section('content')
<div class="w-4/5 mx-auto">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-800">Edit Module: {{ $module->name }}</h2>
        <a href="{{ route('modules.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 transition ease-in-out duration-150">
            Back to Modules
        </a>
    </div>

    <form action="{{ route('modules.update', $module->id) }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                Module Name
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" value="{{ $module->name }}" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                Status
            </label>
            <select id="status" name="status" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="1" {{ $module->status ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !$module->status ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Update Module
            </button>
        </div>
    </form>
</div>
@endsection
