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
            background: var(--primary-color);
            color: var(--white);
            text-decoration: none;
        }
        .modern-btn:hover {
            background: #388e3c;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
        }
        .modern-file-help {
            display: block;
            color: var(--text-light);
            font-size: 0.75rem;
            margin-top: 0.375rem;
        }
    </style>

    <div class="modern-content-card">
        <div class="modern-card-header">
            <h3 class="modern-card-title">Upload New Document</h3>
        </div>
        
        <form action="{{ route('coordinator.upload-document') }}" method="POST" enctype="multipart/form-data" id="uploadFormCoord">
            @csrf
            
            <div class="modern-form-grid">
                <div class="modern-form-group">
                    <label class="modern-form-label">Document Title *</label>
                    <input type="text" name="document_title" class="modern-form-control" 
                           placeholder="Enter document title" required maxlength="150">
                </div>

                <div class="modern-form-group">
                    <label class="modern-form-label">Document Type *</label>
                    <select name="document_type" id="documentTypeCoord" class="modern-form-control" required>
                        <option value="">Select Document Type</option>
                        <option value="pdf">PDF Document</option>
                        <option value="image">Image File</option>
                    </select>
                    <small class="modern-file-help">
                        <i class="fas fa-info-circle"></i> Select file type first before uploading
                    </small>
                </div>

                <div class="modern-form-group">
                    <label class="modern-form-label">Category</label>
                    <select name="category_id" class="modern-form-control">
                        <option value="">Select Category</option>
                        @foreach(\App\Models\DocumentCategory::all() as $cat)
                            <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="modern-form-group">
                    <label class="modern-form-label">Tags (comma-separated)</label>
                    <input type="text" name="tags" class="modern-form-control" placeholder="e.g. urgent, confidential">
                </div>
            </div>

            <div class="modern-form-group">
                <label class="modern-form-label">Select Files * (Multiple supported)</label>
                <input type="file" name="documents[]" id="fileInputCoord" class="modern-form-control" multiple required disabled>
                <small id="fileHelpCoord" class="modern-file-help">
                    <i class="fas fa-lock"></i> Please select a Document Type first to enable file upload
                </small>
            </div>

            <button type="submit" class="modern-btn">
                <i class="fas fa-upload"></i> Upload Documents
            </button>
        </form>

        <script>
            document.getElementById('documentTypeCoord').addEventListener('change', function() {
                const fileInput = document.getElementById('fileInputCoord');
                const fileHelp = document.getElementById('fileHelpCoord');
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
            document.getElementById('uploadFormCoord').addEventListener('submit', function(e) {
                const documentType = document.getElementById('documentTypeCoord').value;
                const fileInput = document.getElementById('fileInputCoord');
                
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

    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">All Documents</h3>
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
                        <a href="{{ route('coordinator.view-document', $document->document_id) }}" target="_blank" class="btn btn-primary" style="padding: 5px 15px; font-size: 12px; margin-right: 5px;">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('coordinator.download-document', $document->document_id) }}" class="btn btn-success" style="padding: 5px 15px; font-size: 12px;">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-light);">
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
