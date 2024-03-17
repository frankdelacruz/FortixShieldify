@extends('layouts.main')

@section('content')
<style>
    .switch {
      position: relative;
      display: inline-block;
      width: 40px; /* Leaner width */
      height: 20px; /* Leaner height */
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
      border-radius: 20px; /* Rounded */
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 16px; /* Smaller circle */
      width: 16px; /* Smaller circle */
      left: 2px;
      bottom: 2px;
      background-color: white;
      transition: .4s;
      border-radius: 50%;
    }

    input:checked + .slider {
      background-color: #4CAF50; /* Green background for 'On' state */
    }

    input:focus + .slider {
      box-shadow: 0 0 1px #4CAF50;
    }

    input:checked + .slider:before {
      transform: translateX(20px); /* Adjust for leaner switch width */
    }
    </style>




<div class="w-3/4 mx-auto">
    <form id="permissionsForm">
        @csrf
        {{-- Role Selection Dropdown --}}
        <select name="role_id" id="roleSelect" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm h-10 mb-4">
            <option value="">Select a Role</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
        </select>

        {{-- Permissions Table --}}
        <div id="permissionsContainer"></div>
    </form>
</div>

<script>
document.getElementById('roleSelect').addEventListener('change', function() {
    const roleId = this.value;
    const container = document.getElementById('permissionsContainer');

    if (!roleId) {
        container.innerHTML = '<div>Please select a role to view and assign permissions.</div>';
        return;
    }

    container.innerHTML = '<div>Loading permissions...</div>'; // Loading message

    fetch(`/permissions/forRole/${roleId}`)
        .then(response => response.json())
        .then(data => {
            container.innerHTML = ''; // Clear previous content

            let tableHtml = `
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Module</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">`;

            data.modules.forEach(module => {
                tableHtml += `<tr>
                    <td class="px-6 py-4 whitespace-nowrap">${module.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap">`;

                // Example permissions - adjust as per your application's permissions
                ['create', 'view', 'update', 'delete'].forEach(permission => {
                    const isChecked = module.permissions && module.permissions.includes(permission);
                    tableHtml += `
                        <div class="inline-flex items-center mr-4">
                            <label class="switch">
                                <input type="checkbox" ${isChecked ? 'checked' : ''} name="permissions[${module.id}][]" value="${permission}" class="form-checkbox">
                                <span class="slider round"></span>
                            </label>
                            <span class="ml-2 text-sm text-gray-600">${permission.charAt(0).toUpperCase() + permission.slice(1)}</span>
                        </div>
                    `;
                });

                tableHtml += `</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 transition ease-in-out duration-150" onclick="saveModulePermissions(${module.id})">Save Changes</button>
                    </td>
                </tr>`;
            });

            tableHtml += `</tbody></table>`;
            container.innerHTML = tableHtml;
        })
        .catch(error => {
            console.error('Error fetching permissions:', error);
            container.innerHTML = '<div>Error loading permissions. Please try again.</div>';
        });
});

function saveModulePermissions(moduleId) {
    const formData = new FormData(document.getElementById('permissionsForm'));
    formData.append('module_id', moduleId); // Ensure module ID is included
    // Append role ID from the dropdown selection
    const roleId = document.getElementById('roleSelect').value;
    formData.append('role_id', roleId);

    fetch('/save-module-permissions', { // Use the route you defined
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        // Handle success, e.g., show a success message
        alert(data.message); // Example: display an alert with the success message
    })
    .catch(error => {
        console.error('Error saving permissions:', error);
        // Handle failure, e.g., show an error message
        alert('Error saving permissions. Please try again.'); // Example: display an alert with the error message
    });
}


    </script>








@endsection
