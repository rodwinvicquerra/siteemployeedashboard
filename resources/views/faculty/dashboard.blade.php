@extends('layouts.dashboard')

@section('title', 'Faculty Dashboard')

@section('page-title', 'My Dashboard')
@section('page-subtitle', 'Track your tasks and performance')

@section('sidebar')
    <a href="{{ route('faculty.dashboard') }}" class="menu-item active">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>
    <a href="{{ route('faculty.tasks') }}" class="menu-item">
        <i class="fas fa-tasks"></i> My Tasks
    </a>
    <a href="{{ route('faculty.notifications') }}" class="menu-item">
        <i class="fas fa-bell"></i> Notifications
        @if($unreadNotifications > 0)
        <span class="badge badge-danger" style="margin-left: auto;">{{ $unreadNotifications }}</span>
        @endif
    </a>
    <a href="{{ route('faculty.profile') }}" class="menu-item">
        <i class="fas fa-user"></i> My Profile
    </a>
    <a href="{{ route('faculty.documents') }}" class="menu-item">
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
            background: var(--bg-light);
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.875rem;
            text-align: left;
            border-bottom: 2px solid var(--border-color);
        }

        .modern-table tbody td {
            padding: 0.875rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .modern-table tbody tr {
            transition: background-color 0.2s ease;
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
            text-transform: uppercase;
            letter-spacing: 0.5px;
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

        .modern-select {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 0.875rem;
            background: var(--white);
            color: var(--text-dark);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .modern-select:hover {
            border-color: var(--primary-color);
        }

        .modern-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(2, 138, 15, 0.1);
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

        /* Responsive Design */
        @media (max-width: 1024px) {
            .modern-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .modern-stats-grid {
                grid-template-columns: 1fr;
            }

            .modern-stat-card {
                padding: 1.25rem;
            }

            .modern-content-card {
                padding: 1.25rem;
            }

            .modern-table thead th,
            .modern-table tbody td {
                padding: 0.75rem;
                font-size: 0.85rem;
            }
        }
    </style>

    <!-- Stats Grid -->
    <div class="modern-stats-grid">
        <div class="modern-stat-card" style="animation-delay: 0.1s">
            <div class="modern-stat-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="modern-stat-value">{{ $totalTasks }}</div>
            <div class="modern-stat-label">Total Tasks</div>
        </div>

        <div class="modern-stat-card" style="animation-delay: 0.2s">
            <div class="modern-stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="modern-stat-value">{{ $pendingTasks }}</div>
            <div class="modern-stat-label">Pending</div>
        </div>

        <div class="modern-stat-card" style="animation-delay: 0.3s">
            <div class="modern-stat-icon">
                <i class="fas fa-spinner"></i>
            </div>
            <div class="modern-stat-value">{{ $inProgressTasks }}</div>
            <div class="modern-stat-label">In Progress</div>
        </div>

        <div class="modern-stat-card" style="animation-delay: 0.4s">
            <div class="modern-stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="modern-stat-value">{{ $completedTasks }}</div>
            <div class="modern-stat-label">Completed</div>
        </div>
    </div>

    <!-- Recent Tasks -->
    <div class="modern-content-card">
        <div class="modern-card-header">
            <h3 class="modern-card-title">My Recent Tasks</h3>
            <a href="{{ route('faculty.tasks') }}" class="btn btn-primary">View All Tasks</a>
        </div>
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Task Title</th>
                    <th>Assigned By</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTasks as $task)
                <tr>
                    <td><strong>{{ $task->task_title }}</strong></td>
                    <td>{{ $task->assignedBy->employee->full_name ?? $task->assignedBy->username }}</td>
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
                    <td>
                        @if($task->status !== 'Completed')
                        <form action="{{ route('faculty.update-task-status', $task->task_id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="modern-select" onchange="this.form.submit()">
                                <option value="Pending" {{ $task->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="In Progress" {{ $task->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Completed" {{ $task->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-light);">
                        No tasks assigned yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Recent Notifications -->
    <div class="modern-content-card">
        <div class="modern-card-header">
            <h3 class="modern-card-title">Recent Notifications</h3>
            <a href="{{ route('faculty.notifications') }}" class="btn btn-primary">View All</a>
        </div>
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentNotifications as $notification)
                <tr style="{{ !$notification->is_read ? 'background: rgba(2, 138, 15, 0.05); font-weight: 600;' : '' }}">
                    <td>{{ $notification->message }}</td>
                    <td>{{ $notification->created_at->format('M d, Y h:i A') }}</td>
                    <td>
                        @if($notification->is_read)
                            <span class="modern-badge modern-badge-success">Read</span>
                        @else
                            <span class="modern-badge modern-badge-warning">Unread</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: var(--text-light);">
                        No notifications
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Performance Reports -->
    @if($performanceReports->count() > 0)
    <div class="modern-content-card">
        <div class="modern-card-header">
            <h3 class="modern-card-title">Recent Performance Reviews</h3>
        </div>
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Evaluator</th>
                    <th>Rating</th>
                    <th>Remarks</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($performanceReports as $report)
                <tr>
                    <td>{{ $report->evaluator->employee->full_name ?? $report->evaluator->username }}</td>
                    <td>
                        <span class="modern-badge {{ $report->rating >= 4 ? 'modern-badge-success' : ($report->rating >= 3 ? 'modern-badge-warning' : 'modern-badge-danger') }}">
                            {{ $report->rating }}/5
                        </span>
                    </td>
                    <td>{{ $report->remarks ?? 'No remarks' }}</td>
                    <td>{{ $report->report_date->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Recent Activities / Notifications -->
    <div class="modern-content-card">
        <div class="modern-card-header">
            <h3 class="modern-card-title">My Recent Activities</h3>
            <span class="modern-badge modern-badge-info">Last 10 Activities</span>
        </div>
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>Type</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentActivities as $activity)
                <tr>
                    <td>
                        {{ $activity->activity }}
                        @if($activity->user_id !== auth()->id() && $activity->user)
                            <br><small style="color: var(--text-light);">
                                <i class="fas fa-info-circle"></i> By {{ $activity->user->employee->full_name ?? $activity->user->username }}
                            </small>
                        @endif
                    </td>
                    <td>
                        @if($activity->activity_type)
                            <span class="modern-badge" style="background: rgba(158, 158, 158, 0.1); color: #616161;">
                                {{ ucfirst(str_replace('_', ' ', $activity->activity_type)) }}
                            </span>
                        @else
                            <span style="color: var(--text-light);">—</span>
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
