@extends('layouts.main')

@section('content')

<style>
    .switch {
      position: relative;
      display: inline-block;
      width: 40px; /* Shorter width */
      height: 22px; /* Shorter height */
    }

    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      transition: .4s;
      border-radius: 22px; /* Fully rounded corners for shorter slider */
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 18px; /* Smaller knob for shorter switch */
      width: 18px; /* Smaller knob for shorter switch */
      left: 2px; /* Adjusted for smaller knob */
      bottom: 2px; /* Adjusted for smaller knob */
      background-color: white;
      transition: .4s;
      border-radius: 50%; /* Fully rounded knob */
    }

    input:checked + .slider {
      background-color: #4CAF50; /* Green when checked */
    }

    input:checked + .slider:before {
      transform: translateX(18px); /* Adjusted for shorter switch */
    }
</style>

<div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Assign Role: {{ $role->name }}</h2>
                <a href="{{ route('roles.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Back to Roles</a>
            </div>

    <form action="{{ route('roles.assign_users.process', $role->id) }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($users as $user)
                <div class="flex items-center mb-2">
                    <label for="user_{{ $user->id }}" class="switch">
                        <input type="checkbox" id="user_{{ $user->id }}" name="users[]" value="{{ $user->id }}" {{ $role->users->contains($user) ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                    <label for="user_{{ $user->id }}" class="ml-2">{{ $user->name }}</label>
                </div>
            @empty
                <p>No users found.</p>
            @endforelse
        </div>

        @if($users->isNotEmpty())
            <div class="mt-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 focus:outline-none focus:shadow-outline">
                    Assign Users
                </button>
            </div>
        @endif
    </form>
</div>
</div>
</div>
@endsection

