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
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">All Documents</h3>
            <span class="badge badge-info">{{ $documents->total() }} Files</span>
        </div>
        <table class="data-table">
            <thead>
                <tr>
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
                    <td><strong>{{ $document->document_title }}</strong></td>
                    <td>
                        <span class="badge badge-info">{{ $document->document_type ?? 'General' }}</span>
                    </td>
                    <td>{{ $document->uploader->employee->full_name ?? $document->uploader->username }}</td>
                    <td>{{ $document->created_at->format('M d, Y h:i A') }}</td>
                    <td>
                        <a href="{{ asset($document->file_path) }}" target="_blank" class="btn btn-primary" style="padding: 5px 15px; font-size: 12px; margin-right: 5px;">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ asset($document->file_path) }}" download class="btn btn-success" style="padding: 5px 15px; font-size: 12px;">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-light);">
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
