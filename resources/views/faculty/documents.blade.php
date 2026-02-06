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
    <style>
        /* Modern Documents Page Styles */
        .modern-doc-card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
            animation: fadeIn 0.5s ease;
        }

        .modern-doc-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
        }

        .modern-doc-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }

        .modern-form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .modern-form-group {
            display: flex;
            flex-direction: column;
        }

        .modern-form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .modern-form-input,
        .modern-form-select {
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.9rem;
            background: var(--white);
            color: var(--text-dark);
            transition: all 0.2s ease;
        }

        .modern-form-input:focus,
        .modern-form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(2, 138, 15, 0.1);
        }

        .modern-form-input:disabled {
            background: var(--bg-light);
            cursor: not-allowed;
            opacity: 0.6;
        }

        .modern-help-text {
            font-size: 0.75rem;
            color: var(--text-light);
            margin-top: 0.375rem;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .modern-doc-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .modern-doc-table thead th {
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

        .modern-doc-table tbody td {
            padding: 1rem 0.875rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-dark);
            font-size: 0.875rem;
        }

        .modern-doc-table tbody tr {
            transition: all 0.2s ease;
        }

        .modern-doc-table tbody tr:hover {
            background: var(--bg-light);
        }

        .modern-doc-table tbody tr:last-child td {
            border-bottom: none;
        }

        .modern-file-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
            border-radius: 8px;
            background: var(--bg-light);
        }

        .modern-doc-badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 6px;
            text-transform: capitalize;
        }

        .modern-badge-count {
            background: rgba(2, 138, 15, 0.1);
            color: var(--primary-color);
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .modern-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .modern-btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .modern-btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(2, 138, 15, 0.2);
        }

        .modern-btn-success {
            background: #4CAF50;
            color: white;
        }

        .modern-btn-success:hover {
            background: #45a049;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(76, 175, 80, 0.2);
        }

        .modern-btn-sm {
            padding: 0.375rem 0.875rem;
            font-size: 0.8125rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .modern-form-grid {
                grid-template-columns: 1fr;
            }

            .modern-doc-table {
                font-size: 0.8125rem;
            }

            .modern-doc-table thead th,
            .modern-doc-table tbody td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>

    <!-- Upload Document Section -->
    <div class="modern-doc-card">
        <div class="modern-doc-header">
            <h3 class="modern-doc-title">Upload Document</h3>
        </div>
        <form action="{{ route('faculty.upload-document') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf
            <div class="modern-form-grid">
                <div class="modern-form-group">
                    <label class="modern-form-label">Document Title *</label>
                    <input type="text" name="document_title" class="modern-form-input" placeholder="Enter document title" required>
                </div>

                <div class="modern-form-group">
                    <label class="modern-form-label">Document Type *</label>
                    <select name="document_type" id="documentType" class="modern-form-select" required>
                        <option value="">Select Document Type</option>
                        <option value="pdf">PDF Document</option>
                        <option value="image">Image File</option>
                    </select>
                    <small class="modern-help-text">
                        <i class="fas fa-info-circle"></i> Select file type first before uploading
                    </small>
                </div>

                <div class="modern-form-group">
                    <label class="modern-form-label">Category</label>
                    <select name="category_id" class="modern-form-select">
                        <option value="">Select Category</option>
                        @foreach(\App\Models\DocumentCategory::all() as $cat)
                            <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="modern-form-group">
                    <label class="modern-form-label">Tags (comma-separated)</label>
                    <input type="text" name="tags" class="modern-form-input" placeholder="e.g. urgent, confidential, review">
                </div>
            </div>

            <div class="modern-form-group" style="margin-bottom: 1.25rem;">
                <label class="modern-form-label">Choose Files * (Multiple files supported)</label>
                <input type="file" name="documents[]" id="fileInput" class="modern-form-input" multiple required disabled>
                <small id="fileHelp" class="modern-help-text">
                    <i class="fas fa-lock"></i> Please select a Document Type first to enable file upload
                </small>
            </div>

            <button type="submit" class="modern-btn modern-btn-primary">
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
    <div class="modern-doc-card">
        <div class="modern-doc-header">
            <h3 class="modern-doc-title">Available Documents</h3>
            <span class="modern-badge-count">{{ $documents->total() }} Files</span>
        </div>
        <table class="modern-doc-table">
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
                    <td>
                        <div class="modern-file-icon">
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
                        </div>
                    </td>
                    <td><strong>{{ $document->document_title }}</strong></td>
                    <td>
                        @if($document->category)
                            <span class="modern-doc-badge" style="background: {{ $document->category->color }}; color: white;">
                                {{ $document->category->category_name }}
                            </span>
                        @elseif($document->document_type === 'pdf')
                            <span class="modern-doc-badge" style="background: rgba(211, 47, 47, 0.1); color: #d32f2f;">PDF Document</span>
                        @elseif($document->document_type === 'image')
                            <span class="modern-doc-badge" style="background: rgba(25, 118, 210, 0.1); color: #1976d2;">Image File</span>
                        @else
                            <span class="modern-doc-badge" style="background: rgba(2, 138, 15, 0.1); color: var(--primary-color);">General</span>
                        @endif
                    </td>
                    <td>{{ $document->uploader->employee->full_name ?? $document->uploader->username }}</td>
                    <td>{{ $document->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('faculty.view-document', $document->document_id) }}" target="_blank" class="modern-btn modern-btn-primary modern-btn-sm">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('faculty.download-document', $document->document_id) }}" class="modern-btn modern-btn-success modern-btn-sm">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-light); padding: 2rem;">
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
