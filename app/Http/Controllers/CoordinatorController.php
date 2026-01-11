<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Task;
use App\Models\Notification;
use App\Models\Document;
use App\Models\DashboardLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CoordinatorController extends Controller
{
    public function dashboard()
    {
        $totalFaculty = User::where('role_id', 3)->count();
        $myTasks = Task::where('assigned_by', auth()->id())->count();
        $pendingTasks = Task::where('assigned_by', auth()->id())
            ->where('status', 'Pending')
            ->count();
        $completedTasks = Task::where('assigned_by', auth()->id())
            ->where('status', 'Completed')
            ->count();

        $recentTasks = Task::with(['assignedTo.employee'])
            ->where('assigned_by', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        $facultyList = User::with('employee')
            ->where('role_id', 3)
            ->take(10)
            ->get();

        return view('coordinator.dashboard', compact(
            'totalFaculty',
            'myTasks',
            'pendingTasks',
            'completedTasks',
            'recentTasks',
            'facultyList'
        ));
    }

    public function tasks()
    {
        $tasks = Task::with(['assignedTo.employee'])
            ->where('assigned_by', auth()->id())
            ->paginate(15);
        return view('coordinator.tasks', compact('tasks'));
    }

    public function createTask()
    {
        $facultyMembers = User::with('employee')
            ->where('role_id', 3)
            ->where('status', 'Active')
            ->get();
        return view('coordinator.create-task', compact('facultyMembers'));
    }

    public function storeTask(Request $request)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'task_title' => 'required|string|max:150',
            'task_description' => 'nullable|string',
            'due_date' => 'required|date',
        ]);

        $task = Task::create([
            'assigned_by' => auth()->id(),
            'assigned_to' => $validated['assigned_to'],
            'task_title' => $validated['task_title'],
            'task_description' => $validated['task_description'],
            'due_date' => $validated['due_date'],
            'status' => 'Pending',
        ]);

        Notification::create([
            'user_id' => $validated['assigned_to'],
            'message' => 'New task assigned: ' . $validated['task_title'],
        ]);

        DashboardLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Created task: ' . $validated['task_title'],
        ]);

        return redirect()->route('coordinator.tasks')
            ->with('success', 'Task created successfully');
    }

    public function updateTask(Request $request, $id)
    {
        $task = Task::where('task_id', $id)
            ->where('assigned_by', auth()->id())
            ->firstOrFail();

        $validated = $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed',
        ]);

        $task->update($validated);

        return redirect()->back()->with('success', 'Task updated successfully');
    }

    public function faculty()
    {
        $facultyMembers = User::with('employee')
            ->where('role_id', 3)
            ->paginate(15);
        return view('coordinator.faculty', compact('facultyMembers'));
    }

    public function createFaculty()
    {
        return view('coordinator.create-faculty');
    }

    public function storeFaculty(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username|max:50',
            'email' => 'required|email|unique:users,email|max:100',
            'password' => 'required|string|min:8',
            'full_name' => 'required|string|max:100',
            'employee_no' => 'nullable|string|unique:employees,employee_no|max:30',
            'department' => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'role_id' => 3,
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'status' => 'Active',
            ]);

            Employee::create([
                'user_id' => $user->id,
                'employee_no' => $validated['employee_no'],
                'full_name' => $validated['full_name'],
                'department' => $validated['department'],
                'position' => 'Faculty Employee',
                'hire_date' => now(),
            ]);

            DashboardLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Created faculty account: ' . $validated['full_name'],
            ]);

            DB::commit();
            return redirect()->route('coordinator.faculty')
                ->with('success', 'Faculty account created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create faculty account']);
        }
    }

    public function documents()
    {
        $documents = Document::with('uploader')
            ->latest()
            ->paginate(15);
        return view('coordinator.documents', compact('documents'));
    }

    public function uploadDocument(Request $request)
    {
        $validated = $request->validate([
            'document_title' => 'required|string|max:150',
            'document' => 'required|file|max:10240',
            'document_type' => 'nullable|string|max:50',
        ]);

        $file = $request->file('document');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/documents'), $filename);

        Document::create([
            'uploaded_by' => auth()->id(),
            'document_title' => $validated['document_title'],
            'file_path' => 'uploads/documents/' . $filename,
            'document_type' => $validated['document_type'],
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully');
    }

    public function viewEmployeeProfile($id)
    {
        $employee = Employee::with(['user.role'])
            ->where('employee_id', $id)
            ->firstOrFail();

        // Only allow viewing faculty employees
        if ($employee->user->role_id !== 3) {
            abort(403, 'Unauthorized access');
        }

        $performanceReports = collect(); // Coordinator cannot see performance reports

        $tasks = Task::with('assignedBy.employee')
            ->where('assigned_to', $employee->user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $taskStats = [
            'total' => $tasks->count(),
            'completed' => $tasks->where('status', 'Completed')->count(),
            'pending' => $tasks->where('status', 'Pending')->count(),
        ];

        $documents = Document::where('uploaded_by', $employee->user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $documentStats = [
            'total' => $documents->count(),
            'byType' => $documents->groupBy('document_type')->map->count(),
        ];

        return view('employees.profile', compact('employee', 'performanceReports', 'tasks', 'taskStats', 'documents', 'documentStats'));
    }
}
