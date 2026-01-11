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
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card" style="animation-delay: 0.1s">
            <div class="stat-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="stat-value">{{ $totalTasks }}</div>
            <div class="stat-label">Total Tasks</div>
        </div>

        <div class="stat-card" style="animation-delay: 0.2s">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value">{{ $pendingTasks }}</div>
            <div class="stat-label">Pending</div>
        </div>

        <div class="stat-card" style="animation-delay: 0.3s">
            <div class="stat-icon">
                <i class="fas fa-spinner"></i>
            </div>
            <div class="stat-value">{{ $inProgressTasks }}</div>
            <div class="stat-label">In Progress</div>
        </div>

        <div class="stat-card" style="animation-delay: 0.4s">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value">{{ $completedTasks }}</div>
            <div class="stat-label">Completed</div>
        </div>
    </div>

    <!-- Recent Tasks -->
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">My Recent Tasks</h3>
            <a href="{{ route('faculty.tasks') }}" class="btn btn-primary">View All Tasks</a>
        </div>
        <table class="data-table">
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
                            <span class="badge badge-success">Completed</span>
                        @elseif($task->status === 'In Progress')
                            <span class="badge badge-warning">In Progress</span>
                        @else
                            <span class="badge badge-danger">Pending</span>
                        @endif
                    </td>
                    <td>
                        @if($task->status !== 'Completed')
                        <form action="{{ route('faculty.update-task-status', $task->task_id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-control" style="width: auto; display: inline; padding: 5px 10px;" onchange="this.form.submit()">
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
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Recent Notifications</h3>
            <a href="{{ route('faculty.notifications') }}" class="btn btn-primary">View All</a>
        </div>
        <table class="data-table">
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
                            <span class="badge badge-success">Read</span>
                        @else
                            <span class="badge badge-warning">Unread</span>
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
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Recent Performance Reviews</h3>
        </div>
        <table class="data-table">
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
                        <span class="badge {{ $report->rating >= 4 ? 'badge-success' : ($report->rating >= 3 ? 'badge-warning' : 'badge-danger') }}">
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
@endsection
