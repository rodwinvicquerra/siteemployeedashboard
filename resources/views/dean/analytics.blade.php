@extends('layouts.dashboard')

@section('title', 'Analytics - Dean')

@section('page-title', 'Data Analytics')
@section('page-subtitle', 'Comprehensive insights and trends')

@section('sidebar')
    <a href="{{ route('dean.dashboard') }}" class="menu-item">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>
    <a href="{{ route('dean.employees') }}" class="menu-item">
        <i class="fas fa-users"></i> Employees
    </a>
    <a href="{{ route('dean.reports') }}" class="menu-item">
        <i class="fas fa-file-alt"></i> Performance Reports
    </a>
    <a href="{{ route('dean.analytics') }}" class="menu-item active">
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
            margin-bottom: 1.5rem;
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
        .modern-progress-item {
            margin-bottom: 1.25rem;
        }
        .modern-progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        .modern-progress-label-text {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text-dark);
        }
        .modern-progress-label-count {
            font-size: 0.875rem;
            color: var(--text-light);
        }
        .modern-progress-bar-bg {
            background: var(--border-color);
            height: 10px;
            border-radius: 8px;
            overflow: hidden;
        }
        .modern-progress-bar-fill {
            background: linear-gradient(90deg, var(--primary-light), var(--primary-color));
            height: 100%;
            transition: width 0.5s ease;
            border-radius: 8px;
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
            background: rgba(33, 150, 243, 0.1);
            color: #1565c0;
        }
    </style>

    <div class="modern-content-card">
        <div class="modern-card-header">
            <h3 class="modern-card-title">Task Status Distribution</h3>
        </div>
        <div style="padding: 0.5rem 0;">
            @forelse($taskStatusData as $status)
                <div class="modern-progress-item">
                    <div class="modern-progress-label">
                        <span class="modern-progress-label-text">{{ $status->status }}</span>
                        <span class="modern-progress-label-count">{{ $status->count }} tasks</span>
                    </div>
                    <div class="modern-progress-bar-bg">
                        <div class="modern-progress-bar-fill" style="width: {{ ($status->count / $taskStatusData->sum('count')) * 100 }}%;"></div>
                    </div>
                </div>
            @empty
                <p style="text-align: center; color: var(--text-light); padding: 2rem 0;">No task data available</p>
            @endforelse
        </div>
    </div>

    <div class="modern-content-card">
        <div class="modern-card-header">
            <h3 class="modern-card-title">Department Distribution</h3>
        </div>
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Department</th>
                    <th>Employee Count</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departmentData as $dept)
                <tr>
                    <td><strong>{{ $dept->department }}</strong></td>
                    <td>{{ $dept->count }}</td>
                    <td>
                        <span class="modern-badge">
                            {{ number_format(($dept->count / $departmentData->sum('count')) * 100, 1) }}%
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: var(--text-light);">
                        No department data available
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Monthly Performance Trends</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Average Rating</th>
                    <th>Total Reports</th>
                </tr>
            </thead>
            <tbody>
                @forelse($monthlyPerformance as $month)
                <tr>
                    <td><strong>{{ $month->month }}</strong></td>
                    <td>
                        <span class="badge {{ $month->avg_rating >= 4 ? 'badge-success' : ($month->avg_rating >= 3 ? 'badge-warning' : 'badge-danger') }}">
                            {{ number_format($month->avg_rating, 2) }}/5.0
                        </span>
                    </td>
                    <td>{{ $month->total_reports }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: var(--text-light);">
                        No performance data available
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
