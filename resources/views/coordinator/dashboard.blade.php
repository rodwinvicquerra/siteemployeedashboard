@extends('layouts.dashboard')

@section('title', 'Program Coordinator Dashboard')

@section('page-title', 'Coordinator Dashboard')
@section('page-subtitle', 'Manage tasks and faculty members')

@section('sidebar')
    <a href="{{ route('coordinator.dashboard') }}" class="menu-item active">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>
    <a href="{{ route('coordinator.tasks') }}" class="menu-item">
        <i class="fas fa-tasks"></i> Tasks
    </a>
    <a href="{{ route('coordinator.faculty') }}" class="menu-item">
        <i class="fas fa-users"></i> Faculty Members
    </a>
    <a href="{{ route('coordinator.documents') }}" class="menu-item">
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
        .modern-quick-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .modern-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .modern-action-btn:hover {
            background: #388e3c;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
        }
        .modern-action-btn.success {
            background: #4caf50;
        }
        .modern-action-btn.success:hover {
            background: #388e3c;
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
            <div class="modern-stat-value">{{ $totalFaculty }}</div>
            <div class="modern-stat-label">Total Faculty</div>
        </div>

        <div class="modern-stat-card" style="animation-delay: 0.2s">
            <div class="modern-stat-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="modern-stat-value">{{ $myTasks }}</div>
            <div class="modern-stat-label">My Tasks</div>
        </div>

        <div class="modern-stat-card" style="animation-delay: 0.3s">
            <div class="modern-stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="modern-stat-value">{{ $completedTasks }}</div>
            <div class="modern-stat-label">Completed</div>
        </div>

        <div class="modern-stat-card" style="animation-delay: 0.4s">
            <div class="modern-stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="modern-stat-value">{{ $pendingTasks }}</div>
            <div class="modern-stat-label">Pending</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="modern-content-card">
        <div class="modern-card-header">
            <h3 class="modern-card-title">Quick Actions</h3>
        </div>
        <div class="modern-quick-actions">
            <a href="{{ route('coordinator.create-task') }}" class="modern-action-btn">
                <i class="fas fa-plus"></i> Create New Task
            </a>
            <a href="{{ route('coordinator.create-faculty') }}" class="modern-action-btn success">
                <i class="fas fa-user-plus"></i> Add Faculty Member
            </a>
        </div>
    </div>

    <!-- Recent Tasks -->
    <div class="modern-content-card">
        <div class="modern-card-header">
            <h3 class="modern-card-title">Recent Tasks</h3>
            <a href="{{ route('coordinator.tasks') }}" class="modern-badge modern-badge-info" style="text-decoration: none; cursor: pointer;">View All</a>
        </div>
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Task Title</th>
                    <th>Assigned To</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTasks as $task)
                <tr>
                    <td><strong>{{ $task->task_title }}</strong></td>
                    <td>{{ $task->assignedTo->employee->full_name ?? 'N/A' }}</td>
                    <td>{{ $task->due_date ? $task->due_date->format('M d, Y') : 'N/A' }}</td>
                    <td>
                        @if($task->status === 'Completed')
                            <span class="modern-badge modern-badge-success">Completed</span>
                        @elseif($task->status === 'In Progress')
                            <span class="modern-badge modern-badge-warning">In Progress</span>
                        @else
                            <span class="modern-badge modern-badge-danger">Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: var(--text-light);">
                        No tasks created yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Faculty List -->
    <div class="modern-content-card">
        <div class="modern-card-header">
            <h3 class="modern-card-title">Faculty Members</h3>
            <a href="{{ route('coordinator.faculty') }}" class="modern-badge modern-badge-info" style="text-decoration: none; cursor: pointer;">View All</a>
        </div>
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($facultyList as $faculty)
                <tr>
                    <td><strong>{{ $faculty->employee->full_name ?? 'N/A' }}</strong></td>
                    <td>{{ $faculty->email }}</td>
                    <td>{{ $faculty->employee->department ?? 'N/A' }}</td>
                    <td>
                        <span class="modern-badge modern-badge-success">{{ $faculty->status }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: var(--text-light);">
                        No faculty members yet
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
                        <strong>{{ $activity->user->employee->full_name ?? $activity->user->username }}</strong>
                        @if($activity->targetUser)
                            <i class="fas fa-arrow-right" style="color: var(--text-light); margin: 0 5px;"></i>
                            <span style="color: var(--text-light);">{{ $activity->targetUser->employee->full_name ?? $activity->targetUser->username }}</span>
                        @endif
                    </td>
                    <td>
                        {{ $activity->activity }}
                        @if($activity->activity_type)
                            <span class="modern-badge modern-badge-secondary" style="background: rgba(158, 158, 158, 0.1); color: #616161; font-size: 0.7rem; margin-left: 5px;">
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
