@extends('layouts.main')

@section('content')
<div class="w-4/5 mx-auto">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-800">Modules</h2>
        <a href="{{ route('modules.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 transition ease-in-out duration-150">
            Create Module
        </a>
    </div>

    <div class="overflow-x-auto mt-5 shadow-md sm:rounded-lg">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($modules as $module)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $module->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $module->status ? 'Active' : 'Inactive' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('modules.edit', $module->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                            <form action="{{ route('modules.destroy', $module->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this module?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" style="background: none; border: none; padding: 0; cursor: pointer;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4">No modules found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $modules->links() }}
        </div>
    </div>
</div>
@endsection
