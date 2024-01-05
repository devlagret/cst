<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoreProductTermin extends Model
{
    use HasFactory,CreatedUpdatedID,SoftDeletes;
    /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */

   protected $table        = 'core_product_termin';
   protected $primaryKey   = 'termin_id';

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
