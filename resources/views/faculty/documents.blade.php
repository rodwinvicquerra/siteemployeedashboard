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
        <form action="{{ route('faculty.upload-document') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Document Title *</label>
                    <input type="text" name="document_title" class="form-control" placeholder="Enter document title" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Document Type *</label>
                    <select name="document_type" id="documentType" class="form-control" required>
                        <option value="">Select Document Type</option>
                        <option value="pdf">PDF Document</option>
                        <option value="image">Image File</option>
                    </select>
                    <small style="color: var(--text-light); font-size: 12px; margin-top: 5px; display: block;">
                        <i class="fas fa-info-circle"></i> Select file type first before uploading
                    </small>
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
                    <label class="form-label">Tags (comma-separated)</label>
                    <input type="text" name="tags" class="form-control" placeholder="e.g. urgent, confidential, review">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Choose Files * (Multiple files supported)</label>
                <input type="file" name="documents[]" id="fileInput" class="form-control" multiple required disabled>
                <small id="fileHelp" style="color: var(--text-light); font-size: 12px; margin-top: 5px; display: block;">
                    <i class="fas fa-lock"></i> Please select a Document Type first to enable file upload
                </small>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-upload"></i> Upload Documents
            </button>
        </form>

        <script>
            document.getElementById('documentType').addEventListener('change', function() {
                const fileInput = document.getElementById('fileInput');
                const fileHelp = document.getElementById('fileHelp');
                const selectedType = this.value;

                if (selectedType === '') {
                    // Disable file input if no type selected
                    fileInput.disabled = true;
                    fileInput.value = '';
                    fileInput.removeAttribute('accept');
                    fileHelp.innerHTML = '<i class="fas fa-lock"></i> Please select a Document Type first to enable file upload';
                    fileHelp.style.color = 'var(--text-light)';
                } else if (selectedType === 'pdf') {
                    // Enable for PDF only
                    fileInput.disabled = false;
                    fileInput.setAttribute('accept', '.pdf');
                    fileHelp.innerHTML = '<i class="fas fa-file-pdf"></i> Allowed: PDF files only (Max: 10MB each)';
                    fileHelp.style.color = '#d32f2f';
                } else if (selectedType === 'image') {
                    // Enable for Images only
                    fileInput.disabled = false;
                    fileInput.setAttribute('accept', '.jpg,.jpeg,.png');
                    fileHelp.innerHTML = '<i class="fas fa-file-image"></i> Allowed: JPG, JPEG, PNG only (Max: 10MB each)';
                    fileHelp.style.color = '#1976d2';
                }
            });

            // Form validation before submit
            document.getElementById('uploadForm').addEventListener('submit', function(e) {
                const documentType = document.getElementById('documentType').value;
                const fileInput = document.getElementById('fileInput');
                
                if (!documentType) {
                    e.preventDefault();
                    alert('Please select a Document Type first!');
                    return false;
                }

                if (fileInput.files.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one file to upload!');
                    return false;
                }

                // Validate file extensions match document type
                const files = fileInput.files;
                for (let i = 0; i < files.length; i++) {
                    const fileName = files[i].name.toLowerCase();
                    const fileExtension = fileName.split('.').pop();
                    
                    if (documentType === 'pdf' && fileExtension !== 'pdf') {
                        e.preventDefault();
                        alert('Error: You selected "PDF Document" but uploaded a non-PDF file (' + files[i].name + ')');
                        return false;
                    }
                    
                    if (documentType === 'image' && !['jpg', 'jpeg', 'png'].includes(fileExtension)) {
                        e.preventDefault();
                        alert('Error: You selected "Image File" but uploaded an invalid file type (' + files[i].name + ')');
                        return false;
                    }
                }
            });
        </script>
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
                    <td style="text-align: center; font-size: 20px;">
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
                            <span class="badge" style="background: {{ $document->category->color }}; color: white;">
                                {{ $document->category->category_name }}
                            </span>
                        @elseif($document->document_type === 'pdf')
                            <span class="badge" style="background: #d32f2f; color: white;">PDF Document</span>
                        @elseif($document->document_type === 'image')
                            <span class="badge" style="background: #1976d2; color: white;">Image File</span>
                        @else
                            <span class="badge badge-info">{{ $document->document_type ?? 'General' }}</span>
                        @endif
                    </td>
                    <td>{{ $document->uploader->employee->full_name ?? $document->uploader->username }}</td>
                    <td>{{ $document->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('faculty.view-document', $document->document_id) }}" target="_blank" class="btn btn-primary" style="padding: 5px 15px; font-size: 12px; margin-right: 5px;">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('faculty.download-document', $document->document_id) }}" class="btn btn-success" style="padding: 5px 15px; font-size: 12px;">
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
