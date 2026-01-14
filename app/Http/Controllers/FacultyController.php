<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Notification;
use App\Models\Document;
use App\Models\PerformanceReport;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function dashboard()
    {
        $totalTasks = Task::where('assigned_to', auth()->id())->count();
        $pendingTasks = Task::where('assigned_to', auth()->id())
            ->where('status', 'Pending')
            ->count();
        $inProgressTasks = Task::where('assigned_to', auth()->id())
            ->where('status', 'In Progress')
            ->count();
        $completedTasks = Task::where('assigned_to', auth()->id())
            ->where('status', 'Completed')
            ->count();

        $recentTasks = Task::with('assignedBy')
            ->where('assigned_to', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        $unreadNotifications = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        $recentNotifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        $employee = auth()->user()->employee;
        $performanceReports = PerformanceReport::with('evaluator')
            ->where('employee_id', $employee->employee_id)
            ->latest('report_date')
            ->take(5)
            ->get();

        return view('faculty.dashboard', compact(
            'totalTasks',
            'pendingTasks',
            'inProgressTasks',
            'completedTasks',
            'recentTasks',
            'unreadNotifications',
            'recentNotifications',
            'performanceReports'
        ));
    }

    public function tasks()
    {
        $tasks = Task::with('assignedBy')
            ->where('assigned_to', auth()->id())
            ->paginate(15);
        return view('faculty.tasks', compact('tasks'));
    }

    public function updateTaskStatus(Request $request, $id)
    {
        $task = Task::where('task_id', $id)
            ->where('assigned_to', auth()->id())
            ->firstOrFail();

        $validated = $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed',
        ]);

        $task->update($validated);

        Notification::create([
            'user_id' => $task->assigned_by,
            'message' => 'Task "' . $task->task_title . '" status updated to: ' . $validated['status'],
        ]);

        return redirect()->back()->with('success', 'Task status updated successfully');
    }

    public function notifications()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);
        return view('faculty.notifications', compact('notifications'));
    }

    public function markNotificationRead($id)
    {
        $notification = Notification::where('notification_id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $notification->update(['is_read' => true]);

        return redirect()->back();
    }

    public function documents()
    {
        $documents = Document::with('uploader')
            ->latest()
            ->paginate(15);
        return view('faculty.documents', compact('documents'));
    }

    public function uploadDocument(Request $request)
    {
        $validated = $request->validate([
            'document_title' => 'required|string|max:150',
            'documents' => 'required|array',
            'documents.*' => 'file|max:10240',
            'document_type' => 'nullable|string|max:50',
            'category_id' => 'nullable|exists:document_categories,category_id',
            'tags' => 'nullable|string',
        ]);

        $uploadedCount = 0;
        foreach ($request->file('documents') as $index => $file) {
            $filename = time() . '_' . $index . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/documents'), $filename);

            Document::create([
                'uploaded_by' => auth()->id(),
                'document_title' => $validated['document_title'] . ($uploadedCount > 0 ? ' (' . ($uploadedCount + 1) . ')' : ''),
                'file_path' => 'uploads/documents/' . $filename,
                'document_type' => $validated['document_type'] ?? null,
                'category_id' => $validated['category_id'] ?? null,
                'tags' => $validated['tags'] ?? null,
            ]);
            $uploadedCount++;
        }

        return redirect()->back()->with('success', "$uploadedCount document(s) uploaded successfully");
    }

    public function viewDocument($id)
    {
        $document = Document::findOrFail($id);
        $filePath = public_path($document->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        // For Office documents (Word, Excel, PowerPoint), use Google Docs Viewer
        if (in_array($extension, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'])) {
            $fileUrl = url($document->file_path);
            return redirect("https://docs.google.com/viewer?url=" . urlencode($fileUrl) . "&embedded=true");
        }

        // For PDFs and images, display directly
        return response()->file($filePath);
    }

    public function downloadDocument($id)
    {
        $document = Document::findOrFail($id);
        $filePath = public_path($document->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath, basename($document->file_path));
    }

    public function profile()
    {
        $employee = auth()->user()->employee;
        $performanceReports = PerformanceReport::with('evaluator')
            ->where('employee_id', $employee->employee_id)
            ->latest('report_date')
            ->get();

        return view('faculty.profile', compact('employee', 'performanceReports'));
    }
}
