@extends('layouts.dashboard')

@section('title', 'Notifications - Faculty')

@section('page-title', 'Notifications')
@section('page-subtitle', 'View all your notifications')

@section('sidebar')
    <a href="{{ route('faculty.dashboard') }}" class="menu-item">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>
    <a href="{{ route('faculty.tasks') }}" class="menu-item">
        <i class="fas fa-tasks"></i> My Tasks
    </a>
    <a href="{{ route('faculty.notifications') }}" class="menu-item active">
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
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">All Notifications</h3>
            <span class="badge badge-info">{{ $notifications->total() }} Total</span>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Message</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $notification)
                <tr style="{{ !$notification->is_read ? 'background: rgba(2, 138, 15, 0.05);' : '' }}">
                    <td style="{{ !$notification->is_read ? 'font-weight: 600;' : '' }}">
                        {{ $notification->message }}
                    </td>
                    <td>{{ $notification->created_at->format('M d, Y h:i A') }}</td>
                    <td>
                        @if($notification->is_read)
                            <span class="badge badge-success">Read</span>
                        @else
                            <span class="badge badge-warning">Unread</span>
                        @endif
                    </td>
                    <td>
                        @if(!$notification->is_read)
                        <form action="{{ route('faculty.mark-notification-read', $notification->notification_id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary" style="padding: 5px 15px; font-size: 12px;">
                                Mark as Read
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: var(--text-light);">
                        No notifications
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div style="margin-top: 20px;">
            {{ $notifications->links() }}
        </div>
    </div>
@endsection
