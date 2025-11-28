<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectResource;
use Illuminate\Http\Request;

class ProjectResourceController extends Controller
{
    public function assign(Request $request, $projectId)
    {
        $data = $request->validate([
            'user_id'=>'required|exists:users,id',
            'project_step_id'=>'nullable|exists:project_steps,id',
            'role'=>'nullable|string'
        ]);

        ProjectResource::updateOrCreate([
            'project_id'=>$projectId,
            'user_id'=>$data['user_id'],
            'project_step_id'=>$data['project_step_id'] ?? null
        ], [
            'role'=>$data['role'] ?? null
        ]);

        return back()->with('success','Resource assigned');
    }

    public function remove($id)
    {
        ProjectResource::findOrFail($id)->delete();
        return back()->with('success','Resource removed');
    }
}
