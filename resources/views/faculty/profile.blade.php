@extends('layouts.dashboard')

@section('title', 'My Profile - Faculty')

@section('page-title', 'My Profile')
@section('page-subtitle', 'View your information and performance')

@section('sidebar')
    <a href="{{ route('faculty.dashboard') }}" class="menu-item">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>
    <a href="{{ route('faculty.tasks') }}" class="menu-item">
        <i class="fas fa-tasks"></i> My Tasks
    </a>
    <a href="{{ route('faculty.notifications') }}" class="menu-item">
        <i class="fas fa-bell"></i> Notifications
    </a>
    <a href="{{ route('faculty.profile') }}" class="menu-item active">
        <i class="fas fa-user"></i> My Profile
    </a>
    <a href="{{ route('faculty.documents') }}" class="menu-item">
        <i class="fas fa-folder"></i> Documents
    </a>
@endsection

@section('content')
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Employee Information</h3>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; padding: 10px 0;">
            <div>
                <p style="color: var(--text-light); margin-bottom: 5px; font-size: 14px;">Employee Number</p>
                <p style="font-weight: 600; font-size: 16px;">{{ $employee->employee_no ?? 'N/A' }}</p>
            </div>
            <div>
                <p style="color: var(--text-light); margin-bottom: 5px; font-size: 14px;">Full Name</p>
                <p style="font-weight: 600; font-size: 16px;">{{ $employee->full_name }}</p>
            </div>
            <div>
                <p style="color: var(--text-light); margin-bottom: 5px; font-size: 14px;">Department</p>
                <p style="font-weight: 600; font-size: 16px;">{{ $employee->department ?? 'N/A' }}</p>
            </div>
            <div>
                <p style="color: var(--text-light); margin-bottom: 5px; font-size: 14px;">Position</p>
                <p style="font-weight: 600; font-size: 16px;">{{ $employee->position }}</p>
            </div>
            <div>
                <p style="color: var(--text-light); margin-bottom: 5px; font-size: 14px;">Hire Date</p>
                <p style="font-weight: 600; font-size: 16px;">{{ $employee->hire_date ? $employee->hire_date->format('M d, Y') : 'N/A' }}</p>
            </div>
            <div>
                <p style="color: var(--text-light); margin-bottom: 5px; font-size: 14px;">Email</p>
                <p style="font-weight: 600; font-size: 16px;">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>

    @if($performanceReports->count() > 0)
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Performance History</h3>
            <span class="badge badge-info">{{ $performanceReports->count() }} Reviews</span>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Evaluator</th>
                    <th>Rating</th>
                    <th>Remarks</th>
                    <th>Review Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($performanceReports as $report)
                <tr>
                    <td><strong>{{ $report->evaluator->employee->full_name ?? $report->evaluator->username }}</strong></td>
                    <td>
                        <span class="badge {{ $report->rating >= 4 ? 'badge-success' : ($report->rating >= 3 ? 'badge-warning' : 'badge-danger') }}">
                            {{ $report->rating }}/5
                        </span>
                    </td>
                    <td>{{ $report->remarks ?? 'No remarks provided' }}</td>
                    <td>{{ $report->report_date->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="content-card">
        <div style="text-align: center; padding: 40px; color: var(--text-light);">
            <i class="fas fa-chart-line" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <p>No performance reviews yet</p>
        </div>
    </div>
    @endif
@endsection
