@extends('layouts.main')

@section('content')
<div class="w-3/5 mx-auto">
    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-800">Permissions</h2>
        <a href="{{ route('permissions.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
             Permission Manager
        </a>
    </div>

    <div class="overflow-hidden shadow-md sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Role
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Module
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Permissions
                    </th>
                    {{-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th> --}}
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($permissions as $permission)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $permission->role->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $permission->module->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @foreach (json_decode($permission->permissions, true) as $perm)
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 mr-2">
                                    {{ $perm }}
                                </span>
                            @endforeach
                        </td>
                        {{-- <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('permissions.edit', $permission->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        </td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $permissions->links() }}
    </div>
</div>
@endsection
