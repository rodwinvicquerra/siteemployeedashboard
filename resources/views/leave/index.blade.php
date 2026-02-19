@extends('layouts.dashboard')

@section('title', 'Leave Requests')

@section('page-title', 'Leave Management')
@section('page-subtitle', 'Manage leave requests and view leave balance')

@section('sidebar')
    @if(auth()->user()->isFaculty())
    <a href="{{ route('faculty.dashboard') }}" class="menu-item">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>
    <a href="{{ route('faculty.tasks') }}" class="menu-item">
        <i class="fas fa-tasks"></i> My Tasks
    </a>
    <a href="{{ route('leave.index') }}" class="menu-item active">
        <i class="fas fa-calendar-alt"></i> Leave Requests
    </a>
    <a href="{{ route('calendar.index') }}" class="menu-item">
        <i class="fas fa-calendar"></i> Calendar
    </a>
    <a href="{{ route('faculty.documents') }}" class="menu-item">
        <i class="fas fa-folder"></i> Documents
    </a>
    @elseif(auth()->user()->isProgramCoordinator())
    <a href="{{ route('coordinator.dashboard') }}" class="menu-item">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>
    <a href="{{ route('coordinator.tasks') }}" class="menu-item">
        <i class="fas fa-tasks"></i> Tasks
    </a>
    <a href="{{ route('leave.index') }}" class="menu-item active">
        <i class="fas fa-calendar-alt"></i> Leave Requests
    </a>
    <a href="{{ route('calendar.index') }}" class="menu-item">
        <i class="fas fa-calendar"></i> Calendar
    </a>
    @else
    <a href="{{ route('dean.dashboard') }}" class="menu-item">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>
    <a href="{{ route('leave.index') }}" class="menu-item active">
        <i class="fas fa-calendar-alt"></i> Leave Requests
    </a>
    <a href="{{ route('calendar.index') }}" class="menu-item">
        <i class="fas fa-calendar"></i> Calendar
    </a>
    @endif
@endsection

@section('content')
    <!-- Leave Balance Card (Faculty Only) -->
    @if(auth()->user()->isFaculty())
    <div class="stats-grid" style="margin-bottom: 30px;">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-medkit"></i>
            </div>
            <div class="stat-value">{{ $leaveBalance->getRemainingSickLeave() }}</div>
            <div class="stat-label">Sick Leave Remaining</div>
            <small style="color: var(--text-light); font-size: 12px; margin-top: 5px; display: block;">
                Used: {{ $leaveBalance->sick_leave_used }} / {{ $leaveBalance->sick_leave_balance }} days
            </small>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-umbrella-beach"></i>
            </div>
            <div class="stat-value">{{ $leaveBalance->getRemainingVacationLeave() }}</div>
            <div class="stat-label">Vacation Leave Remaining</div>
            <small style="color: var(--text-light); font-size: 12px; margin-top: 5px; display: block;">
                Used: {{ $leaveBalance->vacation_leave_used }} / {{ $leaveBalance->vacation_leave_balance }} days
            </small>
        </div>
    </div>
    @endif

    <!-- Leave Requests Table -->
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">
                @if(auth()->user()->isFaculty())
                    My Leave Requests
                @else
                    All Leave Requests
                @endif
            </h3>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('leave.calendar') }}" class="btn btn-primary">
                    <i class="fas fa-calendar"></i> Calendar View
                </a>
                @if(auth()->user()->isFaculty())
                <a href="{{ route('leave.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> File Leave Request
                </a>
                @endif
            </div>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    @if(!auth()->user()->isFaculty())
                    <th>Employee</th>
                    @endif
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Days</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaveRequests as $leave)
                <tr>
                    @if(!auth()->user()->isFaculty())
                    <td>{{ $leave->user->employee->full_name ?? $leave->user->username }}</td>
                    @endif
                    <td>{{ $leave->leave_type }}</td>
                    <td>{{ $leave->start_date->format('M d, Y') }}</td>
                    <td>{{ $leave->end_date->format('M d, Y') }}</td>
                    <td>{{ $leave->days_count }} day(s)</td>
                    <td><small>{{ Str::limit($leave->reason, 50) }}</small></td>
                    <td>
                        @if($leave->status === 'Pending')
                            <span class="badge badge-warning">Pending</span>
                        @elseif($leave->status === 'Approved')
                            <span class="badge badge-success">Approved</span>
                        @else
                            <span class="badge badge-danger">Rejected</span>
                        @endif
                    </td>
                    <td>
                        @if($leave->isPending() && (auth()->user()->isProgramCoordinator() || auth()->user()->isDean()))
                            <button class="btn btn-success" style="padding: 5px 10px; font-size: 12px; margin-right: 5px;" 
                                    onclick="approveLeave({{ $leave->leave_id }})">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;" 
                                    onclick="openRejectModal({{ $leave->leave_id }})">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        @elseif($leave->isRejected())
                            <small style="color: var(--text-light);">{{ $leave->review_notes }}</small>
                        @elseif($leave->isApproved())
                            <small style="color: var(--text-light);">
                                Approved by {{ $leave->reviewer->username ?? 'N/A' }}
                            </small>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-light);">
                        No leave requests found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $leaveRequests->links() }}
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="search-modal" id="rejectModal" style="align-items: center;">
        <div class="search-modal-content" style="max-width: 500px;">
            <div style="padding: 20px; border-bottom: 2px solid var(--border-color);">
                <h3 style="margin: 0;">Reject Leave Request</h3>
            </div>
            <form id="rejectForm" method="POST" style="padding: 20px;">
                @csrf
                <div class="form-group">
                    <label class="form-label">Reason for Rejection *</label>
                    <textarea name="review_notes" class="form-control" rows="4" required 
                              placeholder="Please provide a reason for rejecting this leave request..."></textarea>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn" onclick="closeRejectModal()" 
                            style="background: var(--border-color);">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Leave</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function approveLeave(leaveId) {
        if (confirm('Are you sure you want to approve this leave request?')) {
            fetch(`/leave/${leaveId}/approve`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => {
                if (response.ok) {
                    location.reload();
                } else {
                    alert('Failed to approve leave request');
                }
            });
        }
    }

    function openRejectModal(leaveId) {
        document.getElementById('rejectForm').action = `/leave/${leaveId}/reject`;
        document.getElementById('rejectModal').classList.add('active');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.remove('active');
    }

    // Close modal on outside click
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectModal();
        }
    });
</script>
@endpush
