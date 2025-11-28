<?php
namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PayslipController extends Controller
{
    public function exportPreviousMonth()
    {
        $month = now()->subMonth()->month;
        $year = now()->subMonth()->year;
        $user = auth()->user();
        // Fetch all allowances
        $allowances = $user->allowances()
        ->where('allowance_name', '!=', 'all_allowance')
        ->get(['allowance_name', 'amount']);
        $allowancesTotal = $allowances->sum('amount');

        $basic = $user->basic_salary;

        // Assurance calculation
        $assuranceEmp = round($basic * 0.09, 2);      // Employee 9%
        $assuranceCompany = round($basic * 0.12, 2);  // Company 12%

        $gross = $basic + $allowancesTotal;
        $net = $gross - $assuranceEmp;

        // Prepare data for PDF
        $data = [
            'employee' => $user,
            'basic_salary' => $basic,
            'allowances' => $allowances,
            'allowances_total' => $allowancesTotal,
            'assurance_emp' => $assuranceEmp,
            'assurance_company' => $assuranceCompany,
            'gross_salary' => $gross,
            'net_salary' => $net,
            'month' => $month,
            'year' => $year,
        ];

        // Load Blade view and generate PDF
        $pdf = Pdf::loadView('employee.payslip_pdf', $data);

        $fileName = 'Payslip_'.$user->name.'_'.now()->subMonth()->format('F-Y').'.pdf';

        return $pdf->download($fileName);
    }
}
