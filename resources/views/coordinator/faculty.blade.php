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
    <!-- Tab Navigation -->
    <div class="tabs-container" style="margin-bottom: 30px;">
        <div class="tabs-header" style="display: flex; border-bottom: 2px solid rgba(2,138,15,0.2); gap: 5px;">
            <button class="tab-button active" onclick="switchTab('list')" id="listTab">
                <i class="fas fa-users"></i> Faculty Directory
            </button>
            <button class="tab-button" onclick="switchTab('create')" id="createTab">
                <i class="fas fa-user-plus"></i> Create New Faculty
            </button>
        </div>
    </div>

    <!-- Tab Content: Faculty List -->
    <div class="tab-content active" id="listContent">
        <div class="content-card">
            <div class="card-header">
                <h3 class="card-title">Faculty Directory</h3>
            </div>

            @if(session('success'))
                <div style="background: rgba(76, 175, 80, 0.1); border-left: 4px solid #4CAF50; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
                    <strong style="color: #4CAF50;"><i class="fas fa-check-circle"></i> Success!</strong>
                    <p style="margin: 5px 0 0 0; color: #4CAF50;">{{ session('success') }}</p>
                </div>
            @endif

            <table class="data-table">
            <thead>
                <tr>
                    <th>Employee No.</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Department</th>
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
                        No facult6 members yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div style="margin-top: 20px;">
            {{ $facultyMembers->links() }}
        </div>
        </div>
    </div>

    <!-- Tab Content: Create Faculty Form -->
    <div class="tab-content" id="createContent" style="display: none;">
        <div class="content-card">
            <div class="card-header">
                <h3 class="card-title">Faculty Account Information</h3>
            </div>

            @if($errors->any())
                <div style="background: rgba(244, 67, 54, 0.1); border-left: 4px solid #f44336; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
                    <strong style="color: #f44336;"><i class="fas fa-exclamation-circle"></i> Validation Errors:</strong>
                    <ul style="margin: 10px 0 0 20px; color: #f44336;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('coordinator.store-faculty') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="full_name" class="form-control" 
                           placeholder="Enter full name" required maxlength="100" value="{{ old('full_name') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Employee Number</label>
                    <input type="text" name="employee_no" class="form-control" 
                           placeholder="Enter employee number (optional)" maxlength="30" value="{{ old('employee_no') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Department *</label>
                    <select name="department" class="form-control" required>
                        <option value="">Select Department</option>
                        <option value="Engineering" {{ old('department') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                        <option value="Information Technology" {{ old('department') == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                    </select>
                    <small style="color: var(--text-light); font-size: 12px; margin-top: 5px; display: block;">
                        <i class="fas fa-info-circle"></i> Only Engineering and Information Technology departments available
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label">Username *</label>
                    <input type="text" name="username" class="form-control" 
                           placeholder="Enter username" required maxlength="50" value="{{ old('username') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control" 
                           placeholder="Enter email address" required maxlength="100" value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control" 
                           placeholder="Enter password (min 8 characters)" required minlength="8">
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-user-plus"></i> Create Faculty Account
                    </button>
                    <button type="button" class="btn" style="background: #6c757d; color: white;" onclick="switchTab('list')">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .tab-button {
            padding: 12px 24px;
            background: transparent;
            border: none;
            border-bottom: 3px solid transparent;
            color: var(--text-light);
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .tab-button:hover {
            color: var(--primary-color);
            background: rgba(2,138,15,0.05);
        }

        .tab-button.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
            background: rgba(2,138,15,0.1);
        }

        .tab-button i {
            margin-right: 8px;
        }

        .tab-content {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <script>
        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.style.display = 'none';
            });

            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active');
            });

            // Show selected tab content and activate button
            if (tabName === 'list') {
                document.getElementById('listContent').style.display = 'block';
                document.getElementById('listTab').classList.add('active');
            } else if (tabName === 'create') {
                document.getElementById('createContent').style.display = 'block';
                document.getElementById('createTab').classList.add('active');
            }
        }

        // Check if there are validation errors, if so, show create tab
        @if($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                switchTab('create');
            });
        @endif
    </script>
@endsection
