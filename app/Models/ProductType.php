<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductType extends Model
{
    use HasFactory,SoftDeletes,CreatedUpdatedID;
    /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */

   protected $table        = 'product_type';
   protected $primaryKey   = 'product_type_id';

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
   public function account() {
      return $this->belongsTo(AcctAccount::class,'account_id','account_id');
   }
}
