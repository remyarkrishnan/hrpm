<!DOCTYPE html>
<html>
    @php
        $locale = session('locale', config('app.locale'));
        app()->setLocale($locale);
    @endphp
<head>
    <meta charset="utf-8">
    <title>{{ __('messages.Payslip - :name', ['name' => $employee->name]) }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background: #f5f5f5; }
        h2, h4 { text-align: center; margin: 0; }
        .summary { margin-top: 20px; }
        .summary td { border: none; padding: 4px; }
        .totals { font-weight: bold; }
    </style>
</head>
<body>
    <h2>{{ config('app.name') }}</h2>
    <h4>{{ __('messages.Payslip for :month :year', [
            'month' => \Carbon\Carbon::create()->month($month)->format('F'),
            'year' => $year
        ]) }}</h4>
    <h4>{{ __('messages.Employee') }}: {{ $employee->name }}</h4>

    <!-- Salary Breakdown -->
    <table>
        <tr>
            <th>{{ __('messages.Description') }}</th>
            <th>{{ __('messages.Amount') }}</th>
        </tr>
        <tr>
            <td>{{ __('messages.Basic Salary') }}</td>
            <td>{{ number_format($basic_salary, 2) }}</td>
        </tr>
        @foreach($allowances as $allowance)
        <tr>
            <td>{{ ucfirst(str_replace('_', ' ', $allowance->allowance_name)) }}</td>
            <td>{{ number_format($allowance->amount, 2) }}</td>
        </tr>
        @endforeach
        <tr class="totals">
            <td>{{ __('messages.Total Allowances') }}</td>
            <td>{{ number_format($allowances_total, 2) }}</td>
        </tr>
        <tr>
            <td>{{ __('messages.Employee Assurance (9%)') }}</td>
            <td>{{ number_format($assurance_emp, 2) }}</td>
        </tr>
        <tr>
            <td>{{ __('messages.Company Assurance (12%)') }}</td>
            <td>{{ number_format($assurance_company, 2) }}</td>
        </tr>
        <tr class="totals">
            <td>{{ __('messages.Gross Salary') }}</td>
            <td>{{ number_format($gross_salary, 2) }}</td>
        </tr>
        <tr class="totals">
            <td>{{ __('messages.Net Salary') }}</td>
            <td>{{ number_format($net_salary, 2) }}</td>
        </tr>
    </table>

    <!-- Footer -->
    <p style="text-align:center; margin-top:20px;">{{ __('messages.System generated payslip') }}</p>
</body>
</html>
