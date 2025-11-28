<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectStep;
use App\Models\ProjectSubplan;
use App\Models\ResourceAllocation;

class ProjectReportController extends Controller
{
    public function index()
    {
         $projects = Project::with([
        'steps.subplans.allocations.employee'
    ])->get();

    // Summary stats
    $totalEmployees = ResourceAllocation::count();
    $totalSteps = ProjectStep::count();
    $delayedSubplans = ProjectSubplan::where('end_date', '<', now())
                                     ->where('progress_percentage', '<', 100)
                                     ->count();
    $pendingApprovals = '';//ResourceAllocation::where('approval_status', 'pending')->count();

    // Employee allocations report
    $employeeAllocations = ResourceAllocation::with(['employee', 'subplan'])->get();

    // Step-wise progress report
    $projectSteps = ProjectStep::with(['project', 'subplans'])->get();

    // Approval status summary
    $approvalStatus = ResourceAllocation::with(['employee', 'subplan'])->get();

    return view('admin.projects.reports.index', compact(
        'projects',
        'totalEmployees',
        'totalSteps',
        'delayedSubplans',
        'pendingApprovals',
        'employeeAllocations',
        'projectSteps',
        'approvalStatus'
    ));
}
}
