@extends('layouts.employee')

@section('title', 'Employee Profile - ' . $user->name . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', 'Employee Profile')

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('employee.dashboard') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            Back to Dashboard
        </a>
    </div>
     <div class="profile-header">
        <div class="profile-avatar">
            @if(!empty($user->photo) && Storage::disk('public')->exists($user->photo))
                <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}" 
                    style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
            @else
                <span >
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </span>
            @endif

        </div>
        <div class="profile-info">
            <h2>{{ $user->name }}</h2>
            <p>{{ $user->designation }} • {{ $user->department }}</p>
            <div class="profile-badges">
                <span class="role-badge role-{{ $user->getRoleNames()->first() }}">
                    {{ ucwords(str_replace('-', ' ', $user->getRoleNames()->first() ?? 'No Role')) }}
                </span>
                <span class="status-badge {{ $user->status ? 'active' : 'inactive' }}">
                    {{ $user->status ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
       
    </div>
</div>

<div class="profile-content">
    <!-- Basic Information -->
    <div class="info-card">
        <h3 class="card-title">
            <i class="material-icons">person</i>
            Basic Information
        </h3>
        <div class="info-grid">
            <div class="info-item">
                <label>Full Name</label>
                <value>{{ $user->name }}</value>
            </div>
            <div class="info-item">
                <label>Email Address</label>
                <value>{{ $user->email }}</value>
            </div>
            <div class="info-item">
                <label>Employee ID</label>
                <value class="employee-id">{{ $user->employee_id }}</value>
            </div>
            <div class="info-item">
                <label>Phone Number</label>
                <value>{{ $user->phone ?? 'Not provided' }}</value>
            </div>
            <div class="info-item">
                <label>Date of Birth</label>
                <value>{{ $user->date_of_birth ? $user->date_of_birth->format('M d, Y') : 'Not provided' }}</value>
            </div>
            <div class="info-item">
                <label>Gender</label>
                <value>{{ $user->gender ? ucfirst($user->gender) : 'Not provided' }}</value>
            </div>
             <div class="info-item">
                <label>Address</label>
                <value>{{ $user->address ? $user->address : 'Not provided' }}</value>
            </div>
        </div>
    </div>

    <!-- Employment Information -->
    <div class="info-card">
        <h3 class="card-title">
            <i class="material-icons">work</i>
            Employment Information
        </h3>
        <div class="info-grid">
            <div class="info-item">
                <label>Department</label>
                <value>{{ $user->department }}</value>
            </div>
            <div class="info-item">
                <label>Designation</label>
                <value>{{ $user->designation }}</value>
            </div>
            <div class="info-item">
                <label>System Role</label>
                <value>
                    <span class="role-badge role-{{ $user->getRoleNames()->first() }}">
                        {{ ucwords(str_replace('-', ' ', $user->getRoleNames()->first() ?? 'No Role')) }}
                    </span>
                </value>
            </div>

            @if($user->getRoleNames()->first() === 'employee')
                <div class="info-item">
                    <label>Reporting Manager</label>
                    <value>
                        <span class="role-badge role-{{ $user->getRoleNames()->first() }}">
                            @if($user->reporting_manager)
                                @php
                                    $manager = \App\Models\User::find($user->reporting_manager);
                                @endphp
                                @if($manager)
                                    {{ $manager->name ?? 'No Manager Assigned' }}
                                @else
                                    No Manager Assigned
                                @endif
                            @else
                                No Manager Assigned
                            @endif
                        </span>
                    </value>
                </div>
            @endif


            <div class="info-item">
                <label>Joining Date</label>
                <value>{{ $user->joining_date ? $user->joining_date->format('M d, Y') : 'Not provided' }}</value>
            </div>
            <div class="info-item">
                <label>Employee Type</label>
                <value>{{ $user->employee_type ?  ucfirst($user->employee_type) : 'Not disclosed' }}</value>
            </div>
            <div class="info-item">
                <label>Employment Status</label>
                <value>
                    <span class="status-badge {{ $user->status ? 'active' : 'inactive' }}">
                        {{ $user->status ? 'Active' : 'Inactive' }}
                    </span>
                </value>
            </div>
           
        </div>
    </div>

    <!-- Contact Information -->
    <div class="info-card">
        <h3 class="card-title">
            <i class="material-icons">work</i>
            Salary Information
        </h3>
        <div class="info-grid">
            <div class="info-item full-width">
                <label>Basic Salary</label>
                <value>{{ $user->basic_salary ? '₹' . number_format($user->basic_salary, 2) : 'Not added' }}</value>
            </div>
             <div class="info-item full-width">
            <label>Allowances</label> 
              </div>
              @if($user->allowances->isNotEmpty())
                @foreach($user->allowances->where('allowance_name', '!=', 'all_allowance') as $allowance)
                    <div class="info-item">
                        <label>{{ ucwords(str_replace('_', ' ', $allowance->allowance_name)) }}:</label>
                        <value>
                            ₹{{ number_format($allowance->amount, 2) }}
                        </value>
                    </div>
                @endforeach
            @else
                <div class="info-item">
                    <label>Allowances</label>
                    <value><span>No Allowances Assigned</span></value>
                </div>
            @endif

        </div>
    </div>


<!-- Employment Information -->
    <div class="info-card">
        <h3 class="card-title">
            <i class="material-icons">work</i>
            Bank Information
        </h3>
        <div class="info-grid">
            <div class="info-item">
                <label>Bank Name</label>
                <value>{{ $user->bank_name ? $user->bank_name : 'Not provided' }}</value>
            </div>
            <div class="info-item">
                <label>IBAN No</label>
                <value>{{ $user->iban_no ? $user->iban_no : 'Not provided' }}</value>
            </div>
            <div class="info-item">
                <label>Document</label>
                <value>
                   @if(!empty($user->bank_proof_document) && Storage::disk('public')->exists($user->bank_proof_document))
                <img src="{{ asset('storage/' . $user->bank_proof_document) }}" alt="{{ $user->name }}" 
                    style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
                    @else
                        <span >
                            No document uploaded
                        </span>
                    @endif
                </value>
            </div>
           
        </div>
    </div>

<!-- Employment Information -->
    <div class="info-card">
        <h3 class="card-title">
            <i class="material-icons">work</i>
            Attachements
        </h3>
        <div class="info-grid">
            @if($user->attachments->isNotEmpty())
             @foreach($user->attachments as $attachment)
            <div class="info-item">
                <label></label>
                <value>
                  @php
                            $fileUrl = asset('storage/' . $attachment->file_path);
                            $extension = pathinfo($attachment->file_name, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                        @endphp

                        @if($isImage)
                            <a href="{{ $fileUrl }}" target="_blank">
                                <img src="{{ $fileUrl }}" alt="{{ $attachment->file_name }}"
                                     style="width:60px; height:60px; object-fit:cover; border-radius:6px; border:1px solid #ddd;">
                            </a>   
                        @else
                            <a href="{{ $fileUrl }}" target="_blank" style="text-decoration: none; color: #007bff;">
                                📎 {{ $attachment->file_name }}
                            </a>
                        @endif

                </value>
            </div>
            @endforeach
          @else
    <div class="info-item">
        <label></label>
        <value><span>No Attachments Found</span></value>
    </div>
@endif
           
        </div>
    </div>

<!-- Employment Information -->
    <div class="info-card">
        <h3 class="card-title">
            <i class="material-icons">work</i>
            Documents
        </h3>
        <div class="info-grid">
            @if($user->documents->isNotEmpty())
             @foreach($user->documents as $document)
             <div class="info-item">
                <label>{{ $document->document_name }}</label>
             </div>
            <div class="info-item">
                <label></label>
                <value>
                  @php
                            $fileUrldoc = asset('storage/' . $document->file_path);
                            $extensiondoc = pathinfo($document->file_name, PATHINFO_EXTENSION);
                            $isImagedoc = in_array(strtolower($extensiondoc), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                        @endphp

                        @if($isImagedoc)
                            <a href="{{ $fileUrldoc }}" target="_blank">
                                <img src="{{ $fileUrldoc }}" alt="{{ $document->file_name }}"
                                     style="width:60px; height:60px; object-fit:cover; border-radius:6px; border:1px solid #ddd;">
                            </a>   
                        @else
                            <a href="{{ $fileUrldoc }}" target="_blank" style="text-decoration: none; color: #007bff;">
                                📎 View
                            </a>
                        @endif

                </value>
            </div>
            @endforeach
          @else
    <div class="info-item">
        <label></label>
        <value><span>No Documents Found</span></value>
    </div>
@endif
           
        </div>
    </div>


    <!-- Account Information -->
    <div class="info-card">
        <h3 class="card-title">
            <i class="material-icons">info</i>
            Account Information
        </h3>
        <div class="info-grid">
            <div class="info-item">
                <label>Account Created</label>
                <value>{{ $user->created_at->format('M d, Y \a\t g:i A') }}</value>
            </div>
            <div class="info-item">
                <label>Last Updated</label>
                <value>{{ $user->updated_at->format('M d, Y \a\t g:i A') }}</value>
            </div>
            <div class="info-item">
                <label>Email Verified</label>
                <value>{{ $user->email_verified_at ? 'Yes (' . $user->email_verified_at->format('M d, Y') . ')' : 'No' }}</value>
            </div>
            <div class="info-item">
                <label>Last Login</label>
                <value>{{ $user->last_login_at ? $user->last_login_at->format('M d, Y \a\t g:i A') : 'Never' }}</value>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .page-header { margin-bottom: 32px; }

    .page-nav { margin-bottom: 24px; }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #6750A4;
        text-decoration: none;
        font-weight: 500;
        padding: 8px 12px;
        border-radius: 6px;
        transition: background 0.2s;
    }

    .btn-back:hover { background: rgba(103, 80, 164, 0.08); }

    .profile-header {
        display: flex;
        align-items: flex-start;
        gap: 24px;
        background: white;
        padding: 32px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 32px;
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #6750A4;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 32px;
        font-weight: 600;
        flex-shrink: 0;
    }

    .profile-info {
        flex: 1;
    }

    .profile-info h2 {
        margin: 0 0 8px 0;
        font-size: 28px;
        font-weight: 500;
        color: #1C1B1F;
    }

    .profile-info p {
        margin: 0 0 16px 0;
        color: #666;
        font-size: 16px;
    }

    .profile-badges {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .profile-actions {
        flex-shrink: 0;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #6750A4;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: background 0.2s;
    }

    .btn-primary:hover { background: #5A4A94; }

    .profile-content {
        display: grid;
        gap: 24px;
    }

    .info-card {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .card-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0 0 24px 0;
        font-size: 20px;
        font-weight: 500;
        color: #1C1B1F;
    }

    .card-title i { color: #6750A4; }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .info-item.full-width { grid-column: 1 / -1; }

    .info-item label {
        font-size: 12px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-item value {
        font-size: 16px;
        color: #1C1B1F;
        font-weight: 500;
    }

    .employee-id {
        background: #e3f2fd;
        color: #1565c0;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        display: inline-block;
        width: fit-content;
    }

    .role-badge {
        padding: 6px 16px;
        border-radius: 16px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .role-super-admin { background: #e8f5e8; color: #2e7d32; }
    .role-admin { background: #fff3e0; color: #f57c00; }
    .role-project-manager { background: #e3f2fd; color: #1565c0; }
    .role-employee { background: #f3e5f5; color: #7b1fa2; }
    .role-consultant { background: #fce4ec; color: #c2185b; }

    .status-badge {
        padding: 6px 16px;
        border-radius: 16px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.active { background: #e8f5e8; color: #2e7d32; }
    .status-badge.inactive { background: #ffebee; color: #c62828; }

    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .profile-actions {
            width: 100%;
        }

        .btn-primary {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush