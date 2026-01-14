@extends('layouts.dashboard')

@section('title', 'Documents - Coordinator')

@section('page-title', 'Document Management')
@section('page-subtitle', 'Upload and manage documents')

@section('sidebar')
    <a href="{{ route('coordinator.dashboard') }}" class="menu-item">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>
    <a href="{{ route('coordinator.tasks') }}" class="menu-item">
        <i class="fas fa-tasks"></i> Tasks
    </a>
    <a href="{{ route('coordinator.faculty') }}" class="menu-item">
        <i class="fas fa-users"></i> Faculty Members
    </a>
    <a href="{{ route('coordinator.documents') }}" class="menu-item active">
        <i class="fas fa-folder"></i> Documents
    </a>
@endsection

@section('content')
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Upload New Document</h3>
        </div>
        
        <form action="{{ route('coordinator.upload-document') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px;">
                <div class="form-group">
                    <label class="form-label">Document Title *</label>
                    <input type="text" name="document_title" class="form-control" 
                           placeholder="Enter document title" required maxlength="150">
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-control">
                        <option value="">Select Category</option>
                        @foreach(\App\Models\DocumentCategory::all() as $cat)
                            <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Document Type</label>
                    <input type="text" name="document_type" class="form-control" 
                           placeholder="e.g., Report, Policy, etc." maxlength="50">
                </div>

                <div class="form-group">
                    <label class="form-label">Tags (comma-separated)</label>
                    <input type="text" name="tags" class="form-control" placeholder="e.g. urgent, confidential">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Select Files * (Multiple supported)</label>
                <input type="file" name="documents[]" class="form-control" multiple required>
                <small style="color: var(--text-light);">Max file size: 10MB each. Select multiple files to upload at once.</small>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-upload"></i> Upload Documents
            </button>
        </form>
    </div>

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
                    <td>{{ $document->created_at->format('M d, Y') }}</td>
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
                        No documents uploaded yet
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
