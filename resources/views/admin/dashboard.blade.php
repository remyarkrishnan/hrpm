@extends('layouts.admin')
    @php
        $locale = session('locale', config('app.locale'));
        app()->setLocale($locale);
    @endphp

@section('title', __('messages.Dashboard - :app', ['app' => env('COMPANY_NAME', 'Teqin Valley')]))
@section('page-title', __('messages.Dashboard') . ' - ' . __('messages.Overview'))

@section('content')
<!-- Quick Actions - Moved to Top for Better UX -->
<div class="quick-actions-top">
    <h3 class="actions-title">
        <i class="material-icons">flash_on</i>
        {{ __('messages.Quick Actions') }}
    </h3>
    <div class="quick-actions">
        <a href="/admin/users/create" class="action-btn">
            <i class="material-icons">person_add</i>
            <span>{{ __('messages.Add Employee') }}</span>
        </a>
        <a href="/admin/projects/create" class="action-btn">
            <i class="material-icons">engineering</i>
            <span>{{ __('messages.New Project') }}</span>
        </a>
        <a href="/admin/attendance/create" class="action-btn">
            <i class="material-icons">schedule</i>
            <span>{{ __('messages.Mark Attendance') }}</span>
        </a>
        <a href="/admin/project-reports" class="action-btn">
            <i class="material-icons">assessment</i>
            <span>{{ __('messages.Project Report') }}</span>
        </a>
        <a href="#" class="action-btn" style="display:none">
            <i class="material-icons">security</i>
            <span>{{ __('messages.Safety Check') }}</span>
        </a>
        <a href="/admin/shifts" class="action-btn">
            <i class="material-icons">event</i>
            <span>{{ __('messages.Shifts') }}</span>
        </a>
    </div>
</div>

<!-- Key Performance Indicators -->
<div class="kpi-grid">
    <div class="kpi-card employees">
        <div class="kpi-icon">
            <i class="material-icons">people</i>
        </div>
        <div class="kpi-content">
            <h3>{{ $stats['total_employees'] }}</h3>
            <p>{{ __('messages.Total Employees') }}</p>
            <span class="kpi-trend positive">{{ $stats['active_employees'] }} {{ __('messages.Active') }}</span>
        </div>
    </div>
    
    <div class="kpi-card projects">
        <div class="kpi-icon">
            <i class="material-icons">engineering</i>
        </div>
        <div class="kpi-content">
            <h3>{{ $stats['total_projects'] ?? 8 }}</h3>
            <p>{{ __('messages.Active Projects') }}</p>
            <span class="kpi-trend positive">{{ __('messages.On Schedule') }}</span>
        </div>
    </div>
    
    <div class="kpi-card attendance">
        <div class="kpi-icon">
            <i class="material-icons">schedule</i>
        </div>
        <div class="kpi-content">
            <h3>{{ $stats['today_attendance'] ?? 45 }}</h3>
            <p>{{ __('messages.Todays Attendance') }}</p>
            <span class="kpi-trend neutral">{{ __('messages.Present Rate', ['rate' => '89%']) }}</span>
        </div>
    </div>
    
    <div class="kpi-card budget">
        <div class="kpi-icon">
            <i class="material-icons">account_balance_wallet</i>
        </div>
        <div class="kpi-content">
            <h3>₹2.4M</h3>
            <p>{{ __('messages.Project Budget') }}</p>
            <span class="kpi-trend positive">{{ __('messages.Utilized', ['percent' => '75%']) }}</span>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Project Status Overview -->
    <div class="dashboard-card wide">
        <h3 class="card-title">
            <i class="material-icons">timeline</i>
            {{ __('messages.Active Projects') }}
        </h3>
        <div class="projects-overview">
        @foreach($projects as $prj)
        <div class="project-item">
            <div class="project-info">
                <h4>{{ $prj->name }}</h4>
                <p>{{ __('messages.Location') }}: {{ $prj->location }} • {{ __('messages.PM') }}: {{ $prj->Manager->name }}</p>
            </div>
            <div class="project-progress">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ number_format($prj->progress_percentage, 1) }}%"></div>
                </div>
                <span class="progress-text">{{ number_format($prj->progress_percentage, 1) }}% {{ __('messages.Complete') }}</span>
            </div>
            <div class="project-status on-track">{{ $prj->status }}</div>
        </div>
    @endforeach 
    </div>
    </div>

    <!-- Attendance Overview -->
    <div class="dashboard-card" style="display:none">
        <h3 class="card-title">
            <i class="material-icons">access_time</i>
            {{ __('messages.Attendance Overview') }}
        </h3>
        <div class="attendance-chart">
            <div class="chart-container">
                <div class="attendance-stats">
                    <div class="stat-item">
                        <div class="stat-number">89%</div>
                        <div class="stat-label">{{ __('messages.Present') }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">8%</div>
                        <div class="stat-label">{{ __('messages.Leave') }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">3%</div>
                        <div class="stat-label">{{ __('messages.Absent') }}</div>
                    </div>
                </div>
                <canvas id="attendanceChart" width="200" height="200"></canvas>
            </div>
            
            <div class="department-attendance">
                <h4>{{ __('messages.By Department') }}</h4>
                <div class="dept-item">
                    <span>{{ __('messages.Construction') }}</span>
                    <div class="dept-bar">
                        <div class="dept-fill" style="width: 92%"></div>
                    </div>
                    <span>92%</span>
                </div>
                <div class="dept-item">
                    <span>{{ __('messages.Engineering') }}</span>
                    <div class="dept-bar">
                        <div class="dept-fill" style="width: 88%"></div>
                    </div>
                    <span>88%</span>
                </div>
                <div class="dept-item">
                    <span>{{ __('messages.Safety') }}</span>
                    <div class="dept-bar">
                        <div class="dept-fill" style="width: 95%"></div>
                    </div>
                    <span>95%</span>
                </div>
                <div class="dept-item">
                    <span>{{ __('messages.Quality Control') }}</span>
                    <div class="dept-bar">
                        <div class="dept-fill" style="width: 85%"></div>
                    </div>
                    <span>85%</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Second Row -->
<div class="dashboard-grid" style="display:none">
    <!-- Recent Activity -->
    <div class="dashboard-card">
        <h3 class="card-title">
            <i class="material-icons">notifications</i>
            {{ __('messages.Recent Activity') }}
        </h3>
        <div class="activity-feed">
            <div class="activity-item">
                <div class="activity-icon safety">
                    <i class="material-icons">security</i>
                </div>
                <div class="activity-content">
                    <h4>Safety Inspection Completed</h4>
                    <p>Residential Complex - Phase 2</p>
                    <span class="activity-time">2 hours ago</span>
                </div>
            </div>
            
            <div class="activity-item">
                <div class="activity-icon project">
                    <i class="material-icons">check_circle</i>
                </div>
                <div class="activity-content">
                    <h4>Milestone Achieved</h4>
                    <p>Highway Bridge Project - Foundation Complete</p>
                    <span class="activity-time">5 hours ago</span>
                </div>
            </div>
            
            <div class="activity-item">
                <div class="activity-icon leave">
                    <i class="material-icons">event_available</i>
                </div>
                <div class="activity-content">
                    <h4>Leave Request Approved</h4>
                    <p>Rajesh Kumar - 3 days medical leave</p>
                    <span class="activity-time">1 day ago</span>
                </div>
            </div>
            
            <div class="activity-item">
                <div class="activity-icon overtime">
                    <i class="material-icons">schedule</i>
                </div>
                <div class="activity-content">
                    <h4>Overtime Approved</h4>
                    <p>Construction Team - Weekend work</p>
                    <span class="activity-time">2 days ago</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Budget Overview -->
    <div class="dashboard-card" style="display:none">
        <h3 class="card-title">
            <i class="material-icons">trending_up</i>
            Budget Utilization
        </h3>
        <div class="budget-overview">
            <div class="budget-chart">
                <canvas id="budgetChart" width="300" height="150"></canvas>
            </div>
            
            <div class="budget-breakdown">
                <div class="budget-item">
                    <span class="budget-color labor"></span>
                    <span class="budget-label">Labor</span>
                    <span class="budget-amount">₹95L (40%)</span>
                </div>
                <div class="budget-item">
                    <span class="budget-color materials"></span>
                    <span class="budget-label">Materials</span>
                    <span class="budget-amount">₹85L (35%)</span>
                </div>
                <div class="budget-item">
                    <span class="budget-color equipment"></span>
                    <span class="budget-label">Equipment</span>
                    <span class="budget-amount">₹45L (19%)</span>
                </div>
                <div class="budget-item">
                    <span class="budget-color overhead"></span>
                    <span class="budget-label">Overhead</span>
                    <span class="budget-amount">₹15L (6%)</span>
                </div>
            </div>
        </div>
    </div>
</div>

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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Attendance Pie Chart
    const attendanceCtx = document.getElementById('attendanceChart');
    if (attendanceCtx) {
        new Chart(attendanceCtx, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'On Leave', 'Absent'],
                datasets: [{
                    data: [89, 8, 3],
                    backgroundColor: ['#4CAF50', '#FF9800', '#f44336'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
    
    // Budget Chart
    const budgetCtx = document.getElementById('budgetChart');
    if (budgetCtx) {
        new Chart(budgetCtx, {
            type: 'bar',
            data: {
                labels: ['Labor', 'Materials', 'Equipment', 'Overhead'],
                datasets: [{
                    data: [95, 85, 45, 15],
                    backgroundColor: ['#4CAF50', '#2196F3', '#FF9800', '#9C27B0'],
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₹' + value + 'L';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
