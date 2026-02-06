@extends('layouts.dashboard')

@section('title', 'Documents - Dean')

@section('page-title', 'Documents')
@section('page-subtitle', 'View all uploaded documents')

@section('sidebar')
    <a href="{{ route('dean.dashboard') }}" class="menu-item">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>
    <a href="{{ route('dean.employees') }}" class="menu-item">
        <i class="fas fa-users"></i> Employees
    </a>
    <a href="{{ route('dean.reports') }}" class="menu-item">
        <i class="fas fa-file-alt"></i> Performance Reports
    </a>
    <a href="{{ route('dean.analytics') }}" class="menu-item">
        <i class="fas fa-chart-pie"></i> Analytics
    </a>
    <a href="{{ route('dean.documents') }}" class="menu-item active">
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
        }
        .modern-badge-info {
            background: rgba(33, 150, 243, 0.1);
            color: #1565c0;
        }
        .modern-badge-pdf {
            background: rgba(211, 47, 47, 0.1);
            color: #c62828;
        }
        .modern-badge-image {
            background: rgba(25, 118, 210, 0.1);
            color: #1565c0;
        }
        .modern-file-icon {
            font-size: 1.25rem;
            text-align: center;
        }
        .modern-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-right: 0.375rem;
        }
        .modern-btn-primary {
            background: var(--primary-color);
            color: var(--white);
        }
        .modern-btn-primary:hover {
            background: #388e3c;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
        }
        .modern-btn-success {
            background: #4caf50;
            color: var(--white);
        }
        .modern-btn-success:hover {
            background: #388e3c;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
        }
    </style>

    <div class="modern-content-card">
        <div class="modern-card-header">
            <h3 class="modern-card-title">All Documents</h3>
            <span class="modern-badge modern-badge-info">{{ $documents->total() }} Files</span>
        </div>
        <table class="modern-table">
            <thead>
                <tr>
                    <th style="width: 50px;"></th>
                    <th>Document Title</th>
                    <th>Type</th>
                    <th>Uploaded By</th>
                    <th>Upload Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $document)
                <tr>
                    <td class="modern-file-icon">
                        @php
                            $extension = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
                        @endphp
                        @if($extension === 'pdf')
                            <i class="fas fa-file-pdf" style="color: #d32f2f;"></i>
                        @elseif(in_array($extension, ['png', 'jpg', 'jpeg']))
                            <i class="fas fa-file-image" style="color: #1976d2;"></i>
                        @else
                            <i class="fas fa-file" style="color: #757575;"></i>
                        @endif
                    </td>
                    <td><strong>{{ $document->document_title }}</strong></td>
                    <td>
                        @if($document->category)
                            <span class="modern-badge" style="background: {{ $document->category->color }}20; color: {{ $document->category->color }};">
                                {{ $document->category->category_name }}
                            </span>
                        @elseif($document->document_type === 'pdf')
                            <span class="modern-badge modern-badge-pdf">PDF Document</span>
                        @elseif($document->document_type === 'image')
                            <span class="modern-badge modern-badge-image">Image File</span>
                        @else
                            <span class="modern-badge modern-badge-info">{{ $document->document_type ?? 'General' }}</span>
                        @endif
                    </td>
                    <td>{{ $document->uploader->employee->full_name ?? $document->uploader->username }}</td>
                    <td>{{ $document->created_at->format('M d, Y h:i A') }}</td>
                    <td>
                        <a href="{{ route('dean.view-document', $document->document_id) }}" target="_blank" class="modern-btn modern-btn-primary">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('dean.download-document', $document->document_id) }}" class="modern-btn modern-btn-success">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-light);">
                        No documents available
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div style="margin-top: 20px;">
            {{ $documents->links() }}
        </div>
    </div>
@endsection
