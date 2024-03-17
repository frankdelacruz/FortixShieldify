@extends('layouts.main')

@section('content')
<div class="w-4/5 mx-auto"> <!-- Adjusted width here -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-800">Roles</h2>
        <a href="{{ route('roles.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 transition ease-in-out duration-150">
            Create Role
        </a>
    </div>


    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        {{ session('success') }}
    </div>
@endif



    <div class="overflow-x-auto mt-5 shadow-md sm:rounded-lg"> <!-- Adjusted for horizontal scrolling -->
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Description
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Users
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($roles as $role)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $role->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $role->description ?? 'Not Available' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-wrap gap-2">
                                @foreach ($role->users as $user)
                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">{{ $user->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('roles.edit', $role->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                            <a href="{{ route('roles.assign_users', $role->id) }}" class="text-green-600 hover:text-green-900">Assign Users</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">No roles found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="mt-4">
            {{ $roles->links() }}
        </div>
    </div>
</div>
@endsection
