<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedID;
use Symfony\Component\Routing\Loader\Configurator\Traits\AddTrait;

class AcctDepositoAccount extends Model
{
    use SoftDeletes,CreatedUpdatedID;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $table        = 'acct_deposito_account';
    protected $primaryKey   = 'deposito_account_id';

    protected $guarded = [
        'deposito_account_id',
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
