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
