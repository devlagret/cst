<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory, CreatedUpdatedID;
    
    protected $table        = 'company_setting'; 
    protected $primaryKey   = 'setting_id';
    
    protected $guarded = [
        'created_at',
        'updated_at',
    ];
}
