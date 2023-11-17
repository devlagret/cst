<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcctDepositoProfitSharing extends Model
{
    use SoftDeletes,CreatedUpdatedID;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $table        = 'acct_deposito_profit_sharing'; 
    protected $primaryKey   = 'deposito_profit_sharing_id';
    
    protected $guarded = [
        'deposito_profit_sharing_id',
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

}
