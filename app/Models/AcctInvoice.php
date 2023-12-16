<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcctInvoice extends Model
{
    use HasFactory,SoftDeletes,CreatedUpdatedID;
    /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */

   protected $table        = 'acct_invoice';
   protected $primaryKey   = 'invoice_id';

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
   public function items() {
      return $this->hasMany(AcctInvoiceItem::class,'invoice_id','invoice_id');
   }
   public function product() {
    return $this->belongsTo(CoreProduct::class,'product_id','product_id');
   }
   public function client() {
    return $this->belongsTo(CoreClient::class,'client_id','client_id');
   }
}
