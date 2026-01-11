@extends('layouts.dashboard')

@section('title', 'Faculty Members - Coordinator')

@section('page-title', 'Faculty Management')
@section('page-subtitle', 'Manage faculty employee accounts')

@section('sidebar')
    <a href="{{ route('coordinator.dashboard') }}" class="menu-item">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>
    <a href="{{ route('coordinator.tasks') }}" class="menu-item">
        <i class="fas fa-tasks"></i> Tasks
    </a>
    <a href="{{ route('coordinator.faculty') }}" class="menu-item active">
        <i class="fas fa-users"></i> Faculty Members
    </a>
    <a href="{{ route('coordinator.documents') }}" class="menu-item">
        <i class="fas fa-folder"></i> Documents
    </a>
@endsection

@section('content')
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Faculty Directory</h3>
            <a href="{{ route('coordinator.create-faculty') }}" class="btn btn-success">
                <i class="fas fa-user-plus"></i> Add New Faculty
            </a>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Employee No.</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Hire Date</th>
                    <th>Action</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($facultyMembers as $faculty)
                <tr>
                    <td><strong>{{ $faculty->employee->employee_no ?? 'N/A' }}</strong></td>
                    <td>{{ $faculty->employee->full_name ?? 'N/A' }}</td>
                    <td>{{ $faculty->email }}</td>
                    <td>{{ $faculty->employee->department ?? 'N/A' }}</td>
                    <td>{{ $faculty->employee->hire_date ? $faculty->employee->hire_date->format('M d, Y') : 'N/A' }}</td>
                    <td>
                        <a href="{{ route('coordinator.faculty-profile', $faculty->employee->employee_id) }}" class="btn btn-primary" style="padding: 5px 15px; font-size: 12px;">
                            <i class="fas fa-eye"></i> View Profile
                        </a>
                    </td>
                    <td>
                        @if($faculty->status === 'Active')
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-light);">
                        No faculty members yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div style="margin-top: 20px;">
            {{ $facultyMembers->links() }}
        </div>
    </div>
@endsection
