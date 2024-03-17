<?php

namespace Fortix\Shieldify\Http\Controllers\Api;

use Illuminate\Http\Request;
use Fortix\Shieldify\Http\Requests\StoreModuleRequest;
use Fortix\Shieldify\Http\Requests\UpdateModuleRequest;
use Fortix\Shieldify\Models\Module;
use App\Http\Controllers\Controller;
use Fortix\Shieldify\Http\Resources\ModuleResource;
use Fortix\Shieldify\Http\Resources\ModuleCollection;

class ModulesApiController extends Controller
{
    public function index()
    {
        $modules = Module::paginate(10); // Adjust pagination as needed
        return new ModuleCollection($modules);
    }

    public function store(StoreModuleRequest $request)
    {
        $module = Module::create($request->validated());
        return new ModuleResource($module);
    }

    public function show($id)
    {
        $module = Module::findOrFail($id);
        return new ModuleResource($module);
    }

    public function update(UpdateModuleRequest $request, $id)
    {
        $module = Module::findOrFail($id);
        $module->update($request->validated());
        return new ModuleResource($module);
    }

    public function destroy($id)
    {
        $module = Module::findOrFail($id);
        $module->delete();
        return response()->json(['message' => 'Module deleted successfully'], 200);
    }
}
