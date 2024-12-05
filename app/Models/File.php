<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $primaryKey = 'file_id';
    protected $fillable = [
        'file_name',
        'file_original_name',
        'file_path',
        'file_url',
        'file_type',
        'file_size',
        'is_delete',
    ];


}
