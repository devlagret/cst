<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcctInvoiceItem extends Model
{
    use HasFactory,SoftDeletes,CreatedUpdatedID;
    /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */

   protected $table        = 'acct_invoice_item';
   protected $primaryKey   = 'invoice_item_id';

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
   public function invoice() {
    return $this->belongsTo(AcctInvoiceItem::class,'invoice_id','invoice_id');
 }
}
