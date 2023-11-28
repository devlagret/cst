<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoreClientMember extends Model
{
    use HasFactory,SoftDeletes,CreatedUpdatedID;
      /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

     protected $table        = 'core_client_member'; 
     protected $primaryKey   = 'client_member_id';
     
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
     public function client() {
        return $this->belongsTo(CoreClient::class,'client_id','client_id');
     }
}
