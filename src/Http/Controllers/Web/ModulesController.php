<?php

namespace Fortix\Shieldify\Http\Controllers\Web;

use Illuminate\Http\Request;
use Fortix\Shieldify\Http\Requests\StoreModuleRequest;
use Fortix\Shieldify\Http\Requests\UpdateModuleRequest;
use Fortix\Shieldify\Models\Module;
use App\Http\Controllers\Controller;

class ModulesController extends Controller
{
    public function index()
    {
        $modules = Module::paginate(10); // Adjust pagination as needed
        return view('shieldify::modules.index', compact('modules'));
    }

    public function create()
    {
        return view('shieldify::modules.create');
    }

    public function store(StoreModuleRequest $request)
    {
        $module = new Module($request->validated());
        $module->save();

        return redirect()->route('modules.index')->with('success', 'Module created successfully.');
    }


    public function edit($id)
    {
        $module = Module::findOrFail($id);
        return view('shieldify::modules.edit', compact('module'));
    }

    public function update(UpdateModuleRequest $request, Module $module)
    {
        $module->update($request->validated());

        return redirect()->route('modules.index')->with('success', 'Module updated successfully.');
    }


    public function destroy($id)
    {
    $module = Module::findOrFail($id);
    $module->delete();

    // Redirect back to the modules index page with a success message
    return redirect()->route('modules.index')->with('success', 'Module deleted successfully.');
    }
}
