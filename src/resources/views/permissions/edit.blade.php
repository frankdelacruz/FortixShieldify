@extends('layouts.main')
@section('content')
    <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="{{ $permission->name }}" required>
        <button type="submit">Update</button>
    </form>
    @endsection
