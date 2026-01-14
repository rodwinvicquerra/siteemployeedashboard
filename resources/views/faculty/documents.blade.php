@extends('layouts.dashboard')

@section('title', 'Documents - Faculty')

@section('page-title', 'Documents')
@section('page-subtitle', 'Access shared documents')

@section('sidebar')
    <a href="{{ route('faculty.dashboard') }}" class="menu-item">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>
    <a href="{{ route('faculty.tasks') }}" class="menu-item">
        <i class="fas fa-tasks"></i> My Tasks
    </a>
    <a href="{{ route('faculty.notifications') }}" class="menu-item">
        <i class="fas fa-bell"></i> Notifications
    </a>
    <a href="{{ route('faculty.profile') }}" class="menu-item">
        <i class="fas fa-user"></i> My Profile
    </a>
    <a href="{{ route('faculty.documents') }}" class="menu-item active">
        <i class="fas fa-folder"></i> Documents
    </a>
@endsection

@section('content')
    <!-- Upload Document Section -->
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Upload Document</h3>
        </div>
        <form action="{{ route('faculty.upload-document') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Document Title *</label>
                    <input type="text" name="document_title" class="form-control" placeholder="Enter document title" required>
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
                    <select name="document_type" class="form-control">
                        <option value="">Select Type</option>
                        <option value="Report">Report</option>
                        <option value="Assignment">Assignment</option>
                        <option value="Research">Research</option>
                        <option value="Presentation">Presentation</option>
                        <option value="Certificate">Certificate</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tags (comma-separated)</label>
                    <input type="text" name="tags" class="form-control" placeholder="e.g. urgent, confidential, review">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Choose Files * (Multiple files supported)</label>
                <input type="file" name="documents[]" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png" multiple required>
                <small style="color: var(--text-light); font-size: 12px; margin-top: 5px; display: block;">Allowed: PDF, Word, Excel, PowerPoint, Images (Max: 10MB each). Select multiple files to upload at once.</small>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-upload"></i> Upload Documents
            </button>
        </form>
    </div>

    <!-- Documents List -->
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Available Documents</h3>
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
                        @if($document->category)
                            <span class="badge" style="background: {{ $document->category->color }}; color: white;">
                                {{ $document->category->category_name }}
                            </span>
                        @else
                            <span class="badge badge-info">{{ $document->document_type ?? 'General' }}</span>
                        @endif
                    </td>
                    <td>{{ $document->uploader->employee->full_name ?? $document->uploader->username }}</td>
                    <td>{{ $document->created_at->format('M d, Y') }}</td>
                    <td>
                        <button onclick="openPreview('{{ route('faculty.view-document', $document->document_id) }}', '{{ $document->document_title }}')" class="btn btn-primary" style="padding: 5px 15px; font-size: 12px; margin-right: 5px;">
                            <i class="fas fa-eye"></i> Preview
                        </button>
                        <a href="{{ route('faculty.download-document', $document->document_id) }}" class="btn btn-success" style="padding: 5px 15px; font-size: 12px;">
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
