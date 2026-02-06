@extends('layouts.dashboard')

@section('title', 'Edit Faculty - Coordinator')

@section('page-title', 'Edit Faculty Information')
@section('page-subtitle', 'Update faculty member details and reset password')

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
    <style>
        .modern-content-card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
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
        .modern-form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.25rem;
        }
        .modern-form-group {
            margin-bottom: 1.25rem;
        }
        .modern-form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        .modern-form-control {
            width: 100%;
            padding: 0.75rem;
            font-size: 0.875rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .modern-form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }
        .modern-form-control:disabled {
            background: var(--bg-light);
            cursor: not-allowed;
        }
        .modern-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .modern-btn-primary {
            background: var(--primary-color);
            color: var(--white);
        }
        .modern-btn-primary:hover {
            background: #388e3c;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
        }
        .modern-btn-secondary {
            background: #6c757d;
            color: var(--white);
        }
        .modern-btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }
        .modern-btn-danger {
            background: #f44336;
            color: var(--white);
        }
        .modern-btn-danger:hover {
            background: #d32f2f;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(244, 67, 54, 0.3);
        }
        .modern-alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.25rem;
            border-left: 4px solid;
        }
        .modern-alert-success {
            background: rgba(76, 175, 80, 0.1);
            border-left-color: #4caf50;
            color: #2e7d32;
        }
        .modern-alert-error {
            background: rgba(244, 67, 54, 0.1);
            border-left-color: #f44336;
            color: #c62828;
        }
        .modern-help-text {
            font-size: 0.75rem;
            color: var(--text-light);
            margin-top: 0.375rem;
        }
    </style>

    <!-- Back Button -->
    <div style="margin-bottom: 20px;">
        <a href="{{ route('coordinator.faculty-profile', $employee->employee_id) }}" class="modern-btn modern-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Profile
        </a>
    </div>

    @if(session('success'))
        <div class="modern-alert modern-alert-success">
            <strong><i class="fas fa-check-circle"></i> Success!</strong>
            <p style="margin: 0.5rem 0 0 0;">{{ session('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="modern-alert modern-alert-error">
            <strong><i class="fas fa-exclamation-circle"></i> Error!</strong>
            <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Edit Faculty Information -->
    <div class="modern-content-card">
        <div class="modern-card-header">
            <h3 class="modern-card-title">Edit Faculty Information</h3>
        </div>

        <form action="{{ route('coordinator.update-faculty', $employee->employee_id) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="modern-form-grid">
                <div class="modern-form-group">
                    <label class="modern-form-label">Full Name *</label>
                    <input type="text" name="full_name" class="modern-form-control" 
                           value="{{ old('full_name', $employee->full_name) }}" 
                           required maxlength="100" placeholder="Enter full name">
                </div>

                <div class="modern-form-group">
                    <label class="modern-form-label">Employee Number</label>
                    <input type="text" name="employee_no" class="modern-form-control" 
                           value="{{ old('employee_no', $employee->employee_no) }}" 
                           maxlength="30" placeholder="e.g. FAC001">
                </div>

                <div class="modern-form-group">
                    <label class="modern-form-label">Email Address *</label>
                    <input type="email" name="email" class="modern-form-control" 
                           value="{{ old('email', $employee->user->email) }}" 
                           required maxlength="100" placeholder="faculty@example.com">
                </div>

                <div class="modern-form-group">
                    <label class="modern-form-label">Department *</label>
                    <select name="department" class="modern-form-control" required>
                        <option value="">Select Department</option>
                        <option value="Information Technology" {{ old('department', $employee->department) === 'Information Technology' ? 'selected' : '' }}>
                            Information Technology
                        </option>
                        <option value="Engineering" {{ old('department', $employee->department) === 'Engineering' ? 'selected' : '' }}>
                            Engineering
                        </option>
                    </select>
                    <small class="modern-help-text">
                        <i class="fas fa-info-circle"></i> Select the department where this faculty is assigned
                    </small>
                </div>
            </div>

            <div class="modern-form-group">
                <label class="modern-form-label">Username (Read-only)</label>
                <input type="text" class="modern-form-control" 
                       value="{{ $employee->user->username }}" disabled>
                <small class="modern-help-text">Username cannot be changed</small>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="modern-btn modern-btn-primary">
                    <i class="fas fa-save"></i> Update Information
                </button>
                <a href="{{ route('coordinator.faculty-profile', $employee->employee_id) }}" class="modern-btn modern-btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Reset Password Section -->
    <div class="modern-content-card">
        <div class="modern-card-header">
            <h3 class="modern-card-title">Reset Password</h3>
        </div>

        <div style="background: rgba(255, 152, 0, 0.1); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border-left: 4px solid #ff9800;">
            <p style="margin: 0; color: #e65100; font-size: 0.875rem;">
                <i class="fas fa-exclamation-triangle"></i> <strong>Warning:</strong> Resetting the password will immediately change the faculty member's login credentials. Make sure to inform them of the new password.
            </p>
        </div>

        <form action="{{ route('coordinator.reset-faculty-password', $employee->employee_id) }}" method="POST" id="resetPasswordForm">
            @csrf

            <div class="modern-form-grid">
                <div class="modern-form-group">
                    <label class="modern-form-label">New Password *</label>
                    <input type="password" name="new_password" class="modern-form-control" 
                           required minlength="8" placeholder="Enter new password">
                    <small class="modern-help-text">Minimum 8 characters</small>
                </div>

                <div class="modern-form-group">
                    <label class="modern-form-label">Confirm New Password *</label>
                    <input type="password" name="new_password_confirmation" class="modern-form-control" 
                           required minlength="8" placeholder="Confirm new password">
                    <small class="modern-help-text">Must match the new password</small>
                </div>
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="button" class="modern-btn modern-btn-danger" onclick="confirmPasswordReset()">
                    <i class="fas fa-key"></i> Reset Password
                </button>
            </div>
        </form>
    </div>

    <script>
        function confirmPasswordReset() {
            if (confirm('Are you sure you want to reset the password for {{ $employee->full_name }}?\n\nThis action cannot be undone and will immediately change their login credentials.')) {
                document.getElementById('resetPasswordForm').submit();
            }
        }
    </script>
@endsection
