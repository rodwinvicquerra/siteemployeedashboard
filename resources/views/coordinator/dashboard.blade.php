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
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card" style="animation-delay: 0.1s">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value">{{ $totalFaculty }}</div>
            <div class="stat-label">Total Faculty</div>
        </div>

        <div class="stat-card" style="animation-delay: 0.2s">
            <div class="stat-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="stat-value">{{ $myTasks }}</div>
            <div class="stat-label">My Tasks</div>
        </div>

        <div class="stat-card" style="animation-delay: 0.3s">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value">{{ $completedTasks }}</div>
            <div class="stat-label">Completed</div>
        </div>

        <div class="stat-card" style="animation-delay: 0.4s">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value">{{ $pendingTasks }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Quick Actions</h3>
        </div>
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="{{ route('coordinator.create-task') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Task
            </a>
            <a href="{{ route('coordinator.create-faculty') }}" class="btn btn-success">
                <i class="fas fa-user-plus"></i> Add Faculty Member
            </a>
        </div>
    </div>

    <!-- Recent Tasks -->
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Recent Tasks</h3>
            <a href="{{ route('coordinator.tasks') }}" class="btn btn-primary">View All</a>
        </div>
        <table class="data-table">
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
                            <span class="badge badge-success">Completed</span>
                        @elseif($task->status === 'In Progress')
                            <span class="badge badge-warning">In Progress</span>
                        @else
                            <span class="badge badge-danger">Pending</span>
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
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Faculty Members</h3>
            <a href="{{ route('coordinator.faculty') }}" class="btn btn-primary">View All</a>
        </div>
        <table class="data-table">
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
                        <span class="badge badge-success">{{ $faculty->status }}</span>
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
@endsection
