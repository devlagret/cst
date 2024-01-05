<?php

namespace App\Models;

use App\Helpers\AppHelper;
use Illuminate\Support\Str;
use App\Traits\CreatedUpdatedID;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
   public static function booted() {
    $userid = Auth::id();
    static::creating(function (AcctInvoice $model) use($userid) {
        $model->created_id = $userid;
        $year = Carbon::now()->format('y');
        $month = AppHelper::toRome(Carbon::now()->format('n'));
        $lastno = AcctInvoice::where('invoice_no','like',"%{$month}/{$year}")->latest()->first()->invoice_no??null;
        $no = ((empty($lastno)?0000:Str::substr($lastno, 7, 3))+1);
        $product = CoreProduct::with('type')->find($model->product_id);
        $no = Str::of($no)->padLeft(3,'0');
        $model->invoice_no = "INV/{$product->type->code}/{$no}/{$month}/{$year}";
   });
 }
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