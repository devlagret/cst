<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoreProduct extends Model
{
    use HasFactory,SoftDeletes,CreatedUpdatedID;
    /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */

   protected $table        = 'core_product';
   protected $primaryKey   = 'product_id';

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
   public function addons() {
      return $this->hasMany(CoreProductAddon::class,'product_id','product_id');
   }
   public function client() {
      return $this->belongsTo(CoreClient::class,'client_id','client_id');
   }
   public function type() {
      return $this->belongsTo(ProductType::class,'product_type_id','product_type_id');
   }
   public function termin() {
      return $this->hasMany(CoreProductTermin::class,'product_id','product_id');
   }
   public function invoice() {
      return $this->hasMany(AcctInvoice::class,'product_id','product_id');
   }
}
