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
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">All Performance Reports</h3>
            <span class="badge badge-info">{{ $reports->total() }} Reports</span>
        </div>
        <table class="data-table">
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
                        <span class="badge {{ $report->rating >= 4 ? 'badge-success' : ($report->rating >= 3 ? 'badge-warning' : 'badge-danger') }}">
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
