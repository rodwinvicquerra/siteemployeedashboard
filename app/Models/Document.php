<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $primaryKey = 'document_id';
    
    protected $fillable = [
        'uploaded_by',
        'document_title',
        'file_path',
        'document_type',
        'category_id',
        'tags',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, 'category_id', 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(DocumentComment::class, 'document_id', 'document_id');
    }

    /**
     * Get filtered documents based on user role
     * Faculty uploads are visible to: owner, coordinator, and dean
     * Coordinator uploads are visible to: owner and dean
     */
    public static function getFilteredDocuments($user)
    {
        $query = self::with(['uploader.employee', 'category']);

        if ($user->isDean()) {
            // Dean sees all documents
            return $query->latest();
        } elseif ($user->role_id === 2) { // Program Coordinator
            // Coordinator sees:
            // 1. Their own documents
            // 2. All faculty documents (role_id = 3)
            return $query->where(function($q) use ($user) {
                $q->where('uploaded_by', $user->id)
                  ->orWhereHas('uploader', function($subQ) {
                      $subQ->where('role_id', 3); // Faculty uploads
                  });
            })->latest();
        } else { // Faculty
            // Faculty sees only their own documents
            return $query->where('uploaded_by', $user->id)->latest();
        }
    }
}
