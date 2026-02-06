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
    <style>
        /* Modern Dashboard Styles */
        .modern-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }
        .modern-stat-card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            animation: fadeInUp 0.5s ease;
            border: 1px solid var(--border-color);
        }
        .modern-stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
        }
        .modern-stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            color: var(--white);
        }
        .modern-stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
            line-height: 1.2;
        }
        .modern-stat-label {
            font-size: 0.875rem;
            color: var(--text-light);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .modern-content-card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
            animation: fadeIn 0.5s ease;
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
            text-transform: capitalize;
        }
        .modern-badge-success {
            background: rgba(76, 175, 80, 0.1);
            color: #2e7d32;
        }
        .modern-badge-info {
            background: rgba(33, 150, 243, 0.1);
            color: #1565c0;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @media (max-width: 1024px) {
            .modern-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 640px) {
            .modern-stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <!-- Stats Grid -->
    <div class="modern-stats-grid">
        <div class="modern-stat-card" style="animation-delay: 0.1s">
            <div class="modern-stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="modern-stat-value">{{ $totalEmployees }}</div>
            <div class="modern-stat-label">Total Employees</div>
        </div>

        <div class="modern-stat-card" style="animation-delay: 0.2s">
            <div class="modern-stat-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="modern-stat-value">{{ $totalTasks }}</div>
            <div class="modern-stat-label">Total Tasks</div>
        </div>

        <div class="modern-stat-card" style="animation-delay: 0.3s">
            <div class="modern-stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="modern-stat-value">{{ $completedTasks }}</div>
            <div class="modern-stat-label">Completed Tasks</div>
        </div>

        <div class="modern-stat-card" style="animation-delay: 0.4s">
            <div class="modern-stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="modern-stat-value">{{ $pendingTasks }}</div>
            <div class="modern-stat-label">Pending Tasks</div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="modern-content-card">
        <div class="modern-card-header">
            <h3 class="modern-card-title">Top Performers</h3>
        </div>
        <table class="modern-table">
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
                        <span class="modern-badge modern-badge-success">
                            {{ number_format($performer->avg_rating, 1) }}/5.0
                        </span>
                    </td>
                    <td>
                        <span class="modern-badge modern-badge-success">Active</span>
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
    <div class="modern-content-card">
        <div class="modern-card-header">
            <h3 class="modern-card-title">Recent Activities</h3>
            <span class="modern-badge modern-badge-info">Last 10 Activities</span>
        </div>
        <table class="modern-table">
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
                    <td>
                        <strong>{{ $activity->user->employee->full_name ?? $activity->user->username ?? 'System' }}</strong>
                        @if($activity->targetUser)
                            <i class="fas fa-arrow-right" style="color: var(--text-light); margin: 0 5px;"></i>
                            <span style="color: var(--text-light);">{{ $activity->targetUser->employee->full_name ?? $activity->targetUser->username }}</span>
                        @endif
                    </td>
                    <td>
                        {{ $activity->activity }}
                        @if($activity->activity_type)
                            <span class="modern-badge" style="background: rgba(158, 158, 158, 0.1); color: #616161; font-size: 0.7rem; margin-left: 5px;">
                                {{ ucfirst(str_replace('_', ' ', $activity->activity_type)) }}
                            </span>
                        @endif
                    </td>
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
