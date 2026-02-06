<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeanController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileController;

// Authentication Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Global Search (All authenticated users)
Route::get('/search', [SearchController::class, 'search'])->middleware('auth');

// Profile Management (All authenticated users)
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::post('/update', [ProfileController::class, 'update'])->name('update');
    Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
});

// Dean Routes
Route::middleware(['auth', 'role:Dean'])->prefix('dean')->name('dean.')->group(function () {
    Route::get('/dashboard', [DeanController::class, 'dashboard'])->name('dashboard');
    Route::get('/employees', [DeanController::class, 'employees'])->name('employees');
    Route::get('/employees/{id}/profile', [DeanController::class, 'viewEmployeeProfile'])->name('employee-profile');
    Route::get('/reports', [DeanController::class, 'reports'])->name('reports');
    Route::get('/analytics', [DeanController::class, 'analytics'])->name('analytics');
    Route::get('/documents', [DeanController::class, 'documents'])->name('documents');
    Route::get('/documents/{id}/view', [DeanController::class, 'viewDocument'])->name('view-document');
    Route::get('/documents/{id}/download', [DeanController::class, 'downloadDocument'])->name('download-document');
});

// Program Coordinator Routes
Route::middleware(['auth', 'role:Program Coordinator'])->prefix('coordinator')->name('coordinator.')->group(function () {
    Route::get('/dashboard', [CoordinatorController::class, 'dashboard'])->name('dashboard');
    
    // Tasks
    Route::get('/tasks', [CoordinatorController::class, 'tasks'])->name('tasks');
    Route::get('/tasks/create', [CoordinatorController::class, 'createTask'])->name('create-task');
    Route::post('/tasks', [CoordinatorController::class, 'storeTask'])->name('store-task');
    Route::patch('/tasks/{id}', [CoordinatorController::class, 'updateTask'])->name('update-task');
    
    // Faculty Management
    Route::get('/faculty', [CoordinatorController::class, 'faculty'])->name('faculty');
    Route::get('/faculty/create', [CoordinatorController::class, 'createFaculty'])->name('create-faculty');
    Route::post('/faculty', [CoordinatorController::class, 'storeFaculty'])->name('store-faculty');
    Route::get('/faculty/{id}/profile', [CoordinatorController::class, 'viewEmployeeProfile'])->name('faculty-profile');
    
    // Documents
    Route::get('/documents', [CoordinatorController::class, 'documents'])->name('documents');
    Route::post('/documents', [CoordinatorController::class, 'uploadDocument'])->name('upload-document');
    Route::get('/documents/{id}/view', [CoordinatorController::class, 'viewDocument'])->name('view-document');
    Route::get('/documents/{id}/download', [CoordinatorController::class, 'downloadDocument'])->name('download-document');
});

// Faculty Employee Routes
Route::middleware(['auth', 'role:Faculty Employee'])->prefix('faculty')->name('faculty.')->group(function () {
    Route::get('/dashboard', [FacultyController::class, 'dashboard'])->name('dashboard');
    Route::get('/tasks', [FacultyController::class, 'tasks'])->name('tasks');
    Route::patch('/tasks/{id}/status', [FacultyController::class, 'updateTaskStatus'])->name('update-task-status');
    Route::get('/notifications', [FacultyController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/{id}/read', [FacultyController::class, 'markNotificationRead'])->name('mark-notification-read');
    Route::get('/documents', [FacultyController::class, 'documents'])->name('documents');
    Route::post('/documents', [FacultyController::class, 'uploadDocument'])->name('upload-document');
    Route::get('/documents/{id}/view', [FacultyController::class, 'viewDocument'])->name('view-document');
    Route::get('/documents/{id}/download', [FacultyController::class, 'downloadDocument'])->name('download-document');
    Route::get('/profile', [FacultyController::class, 'profile'])->name('profile');
});
