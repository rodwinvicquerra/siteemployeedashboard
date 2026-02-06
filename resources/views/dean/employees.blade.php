@extends('layouts.dashboard')

@section('title', 'Employees - Dean')

@section('page-title', 'All Employees')
@section('page-subtitle', 'Manage and view all employee information')

@section('sidebar')
    <a href="{{ route('dean.dashboard') }}" class="menu-item">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>
    <a href="{{ route('dean.employees') }}" class="menu-item active">
        <i class="fas fa-users"></i> Employees
    </a>
    <a href="{{ route('dean.reports') }}" class="menu-item">
        <i class="fas fa-file-alt"></i> Performance Reports
    </a>
    <a href="{{ route('dean.analytics') }}" class="menu-item">
        <i class="fas fa-chart-pie"></i> Analytics
    </a>
    <a href="{{ route('dean.documents') }}" class="menu-item">
        <i class="fas fa-folder"></i> Documents
    </a>
@endsection

@section('content')
    <style>
        .modern-content-card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
        }
        .modern-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
        }
        .modern-card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }
        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .modern-table thead th {
            background: transparent;
            color: var(--text-light);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.875rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        .modern-table tbody td {
            padding: 1rem 0.875rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-dark);
            font-size: 0.875rem;
        }
        .modern-table tbody tr {
            transition: all 0.2s ease;
        }
        .modern-table tbody tr:hover {
            background: var(--bg-light);
        }
        .modern-badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 6px;
        }
        .modern-badge-info {
            background: rgba(33, 150, 243, 0.1);
            color: #1565c0;
        }
        .modern-badge-success {
            background: rgba(76, 175, 80, 0.1);
            color: #2e7d32;
        }
        .modern-badge-danger {
            background: rgba(244, 67, 54, 0.1);
            color: #c62828;
        }
        .modern-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.2s ease;
            background: var(--primary-color);
            color: var(--white);
        }
        .modern-btn:hover {
            background: #388e3c;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
        }
    </style>

    <div class="modern-content-card">
        <div class="modern-card-header">
            <h3 class="modern-card-title">Employee Directory</h3>
            <span class="modern-badge modern-badge-info">{{ $employees->total() }} Total</span>
        </div>
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Employee No.</th>
                    <th>Full Name</th>
                    <th>Department</th>
                    <th>Position</th>
                    <th>Role</th>
                    <th>Action</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                <tr>
                    <td><strong>{{ $employee->employee_no ?? 'N/A' }}</strong></td>
                    <td>{{ $employee->full_name }}</td>
                    <td>{{ $employee->department ?? 'N/A' }}</td>
                    <td>{{ $employee->position ?? 'N/A' }}</td>
                    <td>
                        <span class="modern-badge modern-badge-info">
                            {{ $employee->user->role->role_name }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('dean.employee-profile', $employee->employee_id) }}" class="modern-btn">
                            <i class="fas fa-eye"></i> View Profile
                        </a>
                    </td>
                    <td>
                        @if($employee->user->status === 'Active')
                            <span class="modern-badge modern-badge-success">Active</span>
                        @else
                            <span class="modern-badge modern-badge-danger">Inactive</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-light);">
                        No employees found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div style="margin-top: 20px;">
            {{ $employees->links() }}
        </div>
    </div>
@endsection
