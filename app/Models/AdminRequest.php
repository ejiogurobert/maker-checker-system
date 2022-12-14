<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class AdminRequest extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'admin_requests';
    protected $guarded = [
// put in the id column
    ];
}
