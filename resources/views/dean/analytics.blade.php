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
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Task Status Distribution</h3>
        </div>
        <div style="padding: 20px;">
            @forelse($taskStatusData as $status)
                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span style="font-weight: 600;">{{ $status->status }}</span>
                        <span style="color: var(--text-light);">{{ $status->count }} tasks</span>
                    </div>
                    <div style="background: var(--border-color); height: 12px; border-radius: 6px; overflow: hidden;">
                        <div style="background: var(--primary-color); height: 100%; width: {{ ($status->count / $taskStatusData->sum('count')) * 100 }}%; transition: width 0.3s ease;"></div>
                    </div>
                </div>
            @empty
                <p style="text-align: center; color: var(--text-light);">No task data available</p>
            @endforelse
        </div>
    </div>

    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Department Distribution</h3>
        </div>
        <table class="data-table">
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
                        <span class="badge badge-info">
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
