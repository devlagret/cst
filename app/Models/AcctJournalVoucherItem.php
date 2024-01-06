<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcctJournalVoucherItem extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $table        = 'acct_journal_voucher_item'; 
    protected $primaryKey   = 'journal_voucher_item_id';
    
    protected $guarded = [
        'journal_voucher_item_id',
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
    public function account() {
        return $this->belongsTo(AcctAccount::class,'account_id','account_id');
    }
}