@extends('layouts.dashboard')

@section('title', 'All Tasks - Coordinator')

@section('page-title', 'Task Management')
@section('page-subtitle', 'View and manage all assigned tasks')

@section('sidebar')
    <a href="{{ route('coordinator.dashboard') }}" class="menu-item">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>
    <a href="{{ route('coordinator.tasks') }}" class="menu-item active">
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
        .modern-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            background: var(--primary-color);
            color: var(--white);
        }
        .modern-btn:hover {
            background: #388e3c;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
        }
    </style>

    <div class="modern-content-card">
        <div class="modern-card-header">
            <h3 class="modern-card-title">All Tasks</h3>
            <a href="{{ route('coordinator.create-task') }}" class="modern-btn">
                <i class="fas fa-plus"></i> Create New Task
            </a>
        </div>
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Task Title</th>
                    <th>Assigned To</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                <tr>
                    <td><strong>{{ $task->task_title }}</strong></td>
                    <td>{{ $task->assignedTo->employee->full_name ?? 'N/A' }}</td>
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
                    <td>{{ $task->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-light);">
                        No tasks created yet
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
