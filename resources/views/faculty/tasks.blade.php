@extends('layouts.dashboard')

@section('title', 'My Tasks - Faculty')

@section('page-title', 'My Tasks')
@section('page-subtitle', 'View and manage your assigned tasks')

@section('sidebar')
    <a href="{{ route('faculty.dashboard') }}" class="menu-item">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>
    <a href="{{ route('faculty.tasks') }}" class="menu-item active">
        <i class="fas fa-tasks"></i> My Tasks
    </a>
    <a href="{{ route('faculty.notifications') }}" class="menu-item">
        <i class="fas fa-bell"></i> Notifications
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
        .modern-content-card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
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
            margin-left: 0.5rem;
        }
        .modern-badge-info {
            background: rgba(33, 150, 243, 0.1);
            color: #1565c0;
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
        .modern-select {
            display: inline-block;
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border: 2px solid var(--border-color);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .modern-select:focus {
            outline: none;
            border-color: var(--primary-color);
        }
    </style>

    <div class="modern-content-card">
        <div class="modern-card-header">
            <h3 class="modern-card-title">All Tasks</h3>
            <span class="modern-badge modern-badge-info">{{ $tasks->total() }} Total</span>
        </div>
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Task Title</th>
                    <th>Description</th>
                    <th>Assigned By</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                <tr>
                    <td><strong>{{ $task->task_title }}</strong></td>
                    <td>{{ Str::limit($task->task_description ?? 'No description', 50) }}</td>
                    <td>{{ $task->assignedBy->employee->full_name ?? $task->assignedBy->username }}</td>
                    <td>
                        {{ $task->due_date ? $task->due_date->format('M d, Y') : 'N/A' }}
                        @if($task->due_date && $task->due_date->isPast() && $task->status !== 'Completed')
                            <span class="modern-badge modern-badge-danger">Overdue</span>
                        @endif
                    </td>
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
                        @else
                            <span style="color: var(--text-light); font-size: 0.75rem;">No action needed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-light);">
                        No tasks assigned yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div style="margin-top: 20px;">
            {{ $tasks->links() }}
        </div>
    </div>
@endsection
