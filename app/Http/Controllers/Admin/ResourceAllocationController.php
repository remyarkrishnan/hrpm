<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResourceAllocation;
use App\Models\ProjectSubplan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ResourceAllocationController extends Controller
{
    public function index($subplanId)
    {
        //$subplan = ProjectSubplan::with('allocations.employee')->findOrFail($subplanId);
        $employees = User::with('roles')->whereHas('roles', function($q) {
            $q->whereIn('name', ['employee']);
        })->get();


        //return view('admin.projects.resource_allocations.index', compact('subplan', 'employees'));


        $subplan = ProjectSubplan::with(['step.project'])->findOrFail($subplanId);

        // eager load allocations and employee
        $allocations = ResourceAllocation::with('employee')
            ->where('subplan_id', $subplanId)
            ->get();

        // summary counts for cards
        $totalEmployees = $allocations->count();
        // For context: project count = 1 (this page is per subplan); you can customize
        $activeProjects = $subplan->step->project ? 1 : 0;
        $totalSubplans = $subplan->step->project->steps->flatMap->subplans->count() ?? 0;

        return view('admin.projects.resource_allocations.index', compact(
            'subplan', 'allocations', 'totalEmployees', 'activeProjects', 'totalSubplans','employees'
        ));
    }

    public function store(Request $request, $subplanId)
    {
        $data = $request->validate([
            'employee_id' => ['required',
            'exists:users,id',
            Rule::unique('resource_allocations')->where(function ($query) use ($subplanId) {
                return $query->where('subplan_id', $subplanId);
            })
        ],
            'role' => 'nullable|string|max:255',
            'allocation_percentage' => 'nullable|numeric|min:0|max:100',
            'remarks' => 'nullable|string'
        ], [
        'employee_id.unique' => 'This employee is already allocated to the selected subplan.',
        'employee_id.required' => 'Employee selection is required.',
        'employee_id.exists' => 'The selected employee does not exist.',
    ]);

        $data['subplan_id'] = $subplanId;
        ResourceAllocation::create($data);

        return back()->with('success', 'Resource allocated successfully!');
    }

    public function update(Request $request, $id)
    {
        $allocation = ResourceAllocation::findOrFail($id);
        $data = $request->validate([
            'allocation_percentage' => 'nullable|numeric|min:0|max:100',
            'remarks' => 'nullable|string'
        ]);
        $allocation->update($data);

        return back()->with('success', 'Resource allocation updated.');
    }

    public function destroy($subplanId, $id)
    {
        ResourceAllocation::where('subplan_id', $subplanId)
            ->where('id', $id)
            ->delete();

        return back()->with('success', 'Employee removed successfully!');
    }
}
