@extends('layouts.supervisor')

@section('title', 'Supervisor Dashboard')
@section('page-title', 'Project Supervisor Dashboard')

@section('content')
<!-- Statistics Cards -->
<div class="quick-actions-top">
    <h2>👋 Welcome {{ auth()->user()->name }} &nbsp;({{ auth()->user()->employee_id }})-&nbsp;{{ auth()->user()->department }}</h2>

</div>

<div class="quick-actions-top">
    <div class="quick-actions">
        <a href="/supervisor/attendance" class="action-btn">
            <h4>⏰ Mark Attendance</h4>
        </a>
        <a href="#" class="action-btn" style="display:none">
            <h4>📋 My Projects</h4>
        </a>
        <a href="/manager/leaves" class="action-btn" style="display:none">
            <h4>📅 Leave Management</h4>
        </a>
         <a href="/manager/loans" class="action-btn" style="display:none">
             <h4>📅 Loan Management</h4>
        </a>
        <a href="/manager/trainings" class="action-btn" style="display:none">
             <h4>👤 Training</h4>
        </a>
       
       
    </div>
</div>
<!-- Key Performance Indicators -->
<div class="kpi-grid" style="display:none">
    <div class="kpi-card employees">
        <div class="kpi-icon">
           <span class="material-icons">work</span>
        </div>
        <div class="kpi-content">
            <h3>{{ $stats['active_projects'] }}</h3>
            <p>Assigned Projects</p>
            
        </div>
    </div>
    
    <div class="kpi-card projects">
        <div class="kpi-icon">
            <i class="material-icons">schedule</i>
        </div>
        <div class="kpi-content">
            <h3>{{ $stats['today_attendance'] }}</h3>
            <p>Attendance Today</p>
          
        </div>
    </div>
    
    <div class="kpi-card attendance">
        <div class="kpi-icon">
            <i class="material-icons">event</i>
        </div>
        <div class="kpi-content">
            <h3>{{ $stats['pending_leaves'] }}</h3>
            <p>Leave Requests</p>
           
        </div>
    </div>
    
    <div class="kpi-card budget">
        <div class="kpi-icon">
            <i class="material-icons">event</i>
        </div>
        <div class="kpi-content">
            <h3>{{ $stats['pending_leaves'] }}</h3>
            <p>LoanRequests</p>
           
        </div>
    </div>
</div>

<!-- Personal Stats -->
@endsection
@push('styles')
<style>
    /* Quick Actions at Top */
    .quick-actions-top {
        background: white;
        padding: 24px 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 32px;
    }
    
    .actions-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 18px;
        font-weight: 600;
        color: #1C1B1F;
        margin: 0 0 20px 0;
    }
    
    .actions-title i { color: #6750A4; }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }
    
    .kpi-card {
        background: white;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: transform 0.2s;
    }
    
    .kpi-card:hover { transform: translateY(-2px); }
    
    .kpi-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }
    
    .kpi-card.employees .kpi-icon { background: linear-gradient(135deg, #4CAF50, #45a049); }
    .kpi-card.projects .kpi-icon { background: linear-gradient(135deg, #2196F3, #1976d2); }
    .kpi-card.attendance .kpi-icon { background: linear-gradient(135deg, #FF9800, #f57c00); }
    .kpi-card.budget .kpi-icon { background: linear-gradient(135deg, #9C27B0, #7b1fa2); }
    
    .kpi-content h3 {
        font-size: 28px;
        font-weight: bold;
        margin: 0 0 4px 0;
        color: #1C1B1F;
    }
    
    .kpi-content p {
        font-size: 14px;
        color: #666;
        margin: 0 0 8px 0;
    }
    
    .kpi-trend {
        font-size: 12px;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 4px;
    }
    
    .kpi-trend.positive { background: #e8f5e8; color: #2e7d32; }
    .kpi-trend.neutral { background: #fff3e0; color: #f57c00; }
    
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }
    
    .dashboard-card {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .dashboard-card.wide {
        grid-column: 1 / -1;
    }
    
    .card-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 18px;
        font-weight: 600;
        color: #1C1B1F;
        margin: 0 0 24px 0;
    }
    
    .card-title i { color: #6750A4; }
    
    /* Project Overview Styles */
    .projects-overview {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .project-item {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 12px;
        border-left: 4px solid #6750A4;
    }
    
    .project-info {
        flex: 2;
    }
    
    .project-info h4 {
        margin: 0 0 6px 0;
        font-size: 16px;
        font-weight: 600;
    }
    
    .project-info p {
        margin: 0;
        color: #666;
        font-size: 14px;
    }
    
    .project-progress {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .progress-bar {
        flex: 1;
        height: 8px;
        background: #e0e0e0;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #4CAF50, #45a049);
        transition: width 0.3s;
    }
    
    .progress-text {
        font-size: 14px;
        font-weight: 600;
        min-width: 80px;
    }
    
    .project-status {
        padding: 6px 12px;
        border-radius: 16px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        min-width: 80px;
        text-align: center;
    }
    
    .project-status.on-track { background: #e8f5e8; color: #2e7d32; }
    .project-status.delayed { background: #ffebee; color: #c62828; }
    
    /* Attendance Chart Styles */
    .attendance-chart {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }
    
    .chart-container {
        display: flex;
        align-items: center;
        gap: 24px;
    }
    
    .attendance-stats {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-number {
        font-size: 24px;
        font-weight: bold;
        color: #1C1B1F;
    }
    
    .stat-label {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
    }
    
    .department-attendance h4 {
        margin: 0 0 16px 0;
        font-size: 16px;
        font-weight: 600;
    }
    
    .dept-item {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }
    
    .dept-item span:first-child {
        min-width: 100px;
        font-size: 14px;
    }
    
    .dept-bar {
        flex: 1;
        height: 6px;
        background: #e0e0e0;
        border-radius: 3px;
        overflow: hidden;
    }
    
    .dept-fill {
        height: 100%;
        background: linear-gradient(90deg, #4CAF50, #45a049);
    }
    
    .dept-item span:last-child {
        min-width: 40px;
        font-size: 14px;
        font-weight: 600;
        text-align: right;
    }
    
    /* Activity Feed Styles */
    .activity-feed {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    
    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        flex-shrink: 0;
    }
    
    .activity-icon.safety { background: #f44336; }
    .activity-icon.project { background: #4CAF50; }
    .activity-icon.leave { background: #2196F3; }
    .activity-icon.overtime { background: #FF9800; }
    
    .activity-content h4 {
        margin: 0 0 4px 0;
        font-size: 14px;
        font-weight: 600;
    }
    
    .activity-content p {
        margin: 0 0 8px 0;
        font-size: 12px;
        color: #666;
    }
    
    .activity-time {
        font-size: 11px;
        color: #999;
    }
    
    /* Budget Overview Styles */
    .budget-overview {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .budget-breakdown {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .budget-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .budget-color {
        width: 16px;
        height: 16px;
        border-radius: 4px;
    }
    
    .budget-color.labor { background: #4CAF50; }
    .budget-color.materials { background: #2196F3; }
    .budget-color.equipment { background: #FF9800; }
    .budget-color.overhead { background: #9C27B0; }
    
    .budget-label {
        flex: 1;
        font-size: 14px;
    }
    
    .budget-amount {
        font-size: 14px;
        font-weight: 600;
    }
    
    /* Quick Actions Styles */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
    }
    
    .action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 12px;
        text-decoration: none;
        color: #6750A4;
        transition: all 0.2s;
        border: 2px solid transparent;
    }
    
    .action-btn:hover {
        background: rgba(103, 80, 164, 0.08);
        border-color: #6750A4;
        transform: translateY(-2px);
    }
    
    .action-btn i {
        font-size: 32px;
    }
    
    .action-btn span {
        font-size: 14px;
        font-weight: 500;
        text-align: center;
    }
    
    @media (max-width: 768px) {
        .kpi-grid {
            grid-template-columns: 1fr;
        }
        
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
        
        .project-item {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .chart-container {
            flex-direction: column;
        }
        
        .quick-actions {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .quick-actions-top {
            padding: 20px;
        }
    }
</style>
@endpush
