<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branch';
    
    protected $primaryKey = 'branch_id';
    protected $fillable = [
        'branch_name',
        'branch_address',
        'branch_added_by',
    ];


}
