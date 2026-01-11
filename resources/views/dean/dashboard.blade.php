@extends('layouts.dashboard')

@section('title', 'Dean Dashboard')

@section('page-title', 'Dean Dashboard')
@section('page-subtitle', 'Comprehensive overview of employee analytics')

@section('sidebar')
    <a href="{{ route('dean.dashboard') }}" class="menu-item active">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>
    <a href="{{ route('dean.employees') }}" class="menu-item">
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
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card" style="animation-delay: 0.1s">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value">{{ $totalEmployees }}</div>
            <div class="stat-label">Total Employees</div>
        </div>

        <div class="stat-card" style="animation-delay: 0.2s">
            <div class="stat-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="stat-value">{{ $totalTasks }}</div>
            <div class="stat-label">Total Tasks</div>
        </div>

        <div class="stat-card" style="animation-delay: 0.3s">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value">{{ $completedTasks }}</div>
            <div class="stat-label">Completed Tasks</div>
        </div>

        <div class="stat-card" style="animation-delay: 0.4s">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value">{{ $pendingTasks }}</div>
            <div class="stat-label">Pending Tasks</div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Top Performers</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Department</th>
                    <th>Average Rating</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topPerformers as $performer)
                <tr>
                    <td><strong>{{ $performer->employee->full_name }}</strong></td>
                    <td>{{ $performer->employee->department ?? 'N/A' }}</td>
                    <td>
                        <span class="badge badge-success">
                            {{ number_format($performer->avg_rating, 1) }}/5.0
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-success">Active</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: var(--text-light);">
                        No performance data available yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Recent Activities -->
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Recent Activities</h3>
            <span class="badge badge-info">Last 10 Activities</span>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Activity</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentActivities as $activity)
                <tr>
                    <td><strong>{{ $activity->user->username ?? 'System' }}</strong></td>
                    <td>{{ $activity->activity }}</td>
                    <td>{{ $activity->log_date->format('M d, Y h:i A') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: var(--text-light);">
                        No recent activities
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
<script>
    // Auto refresh stats every 30 seconds
    setTimeout(() => {
        location.reload();
    }, 30000);
</script>
@endpush
