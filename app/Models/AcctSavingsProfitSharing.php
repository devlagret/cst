<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcctSavingsProfitSharing extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $table        = 'acct_savings_profit_sharing'; 
    protected $primaryKey   = 'savings_profit_sharing_id';

    protected $guarded = [
        'savings_profit_sharing_id',
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
