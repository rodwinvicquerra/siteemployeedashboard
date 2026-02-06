@extends('layouts.dashboard')

@section('title', 'Performance Reports - Dean')

@section('page-title', 'Performance Reports')
@section('page-subtitle', 'View employee performance evaluations')

@section('sidebar')
    <a href="{{ route('dean.dashboard') }}" class="menu-item">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>
    <a href="{{ route('dean.employees') }}" class="menu-item">
        <i class="fas fa-users"></i> Employees
    </a>
    <a href="{{ route('dean.reports') }}" class="menu-item active">
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
        .modern-badge-warning {
            background: rgba(255, 152, 0, 0.1);
            color: #e65100;
        }
        .modern-badge-danger {
            background: rgba(244, 67, 54, 0.1);
            color: #c62828;
        }
    </style>

    <div class="modern-content-card">
        <div class="modern-card-header">
            <h3 class="modern-card-title">All Performance Reports</h3>
            <span class="modern-badge modern-badge-info">{{ $reports->total() }} Reports</span>
        </div>
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Evaluator</th>
                    <th>Rating</th>
                    <th>Remarks</th>
                    <th>Report Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                <tr>
                    <td><strong>{{ $report->employee->full_name }}</strong></td>
                    <td>{{ $report->evaluator->employee->full_name ?? $report->evaluator->username }}</td>
                    <td>
                        <span class="modern-badge {{ $report->rating >= 4 ? 'modern-badge-success' : ($report->rating >= 3 ? 'modern-badge-warning' : 'modern-badge-danger') }}">
                            {{ $report->rating }}/5
                        </span>
                    </td>
                    <td>{{ Str::limit($report->remarks ?? 'No remarks', 60) }}</td>
                    <td>{{ $report->report_date->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-light);">
                        No performance reports available
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div style="margin-top: 20px;">
            {{ $reports->links() }}
        </div>
    </div>
@endsection
