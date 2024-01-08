<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcctJournalVoucher extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $table        = 'acct_journal_voucher'; 
    protected $primaryKey   = 'journal_voucher_id';
    
    protected $guarded = [
        'journal_voucher_id',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function items()
    {
        return $this->hasMany(AcctJournalVoucherItem::class,'journal_voucher_id','journal_voucher_id');
    }

}