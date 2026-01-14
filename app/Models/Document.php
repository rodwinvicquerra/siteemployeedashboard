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
}
