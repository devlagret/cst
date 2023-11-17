<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoreClient extends Model
{
    use HasFactory,SoftDeletes,CreatedUpdatedID;
      /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

     protected $table        = 'core_client'; 
     protected $primaryKey   = 'client_id';
     
     protected $guarded = [
         'created_at',
         'updated_at',
         'deleted_at',
     ];
 
     /**
      * The attributes that should be hidden for serialization.
      *
      * @var array
      */
     protected $hidden = [
     ];
}
