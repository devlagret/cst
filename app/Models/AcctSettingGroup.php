<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcctSettingGroup extends Model
{
    use HasFactory,SoftDeletes,CreatedUpdatedID;
    /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */

   protected $table        = 'acct_account_setting_group';
   protected $primaryKey   = 'account_setting_group_id';

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
   public function settings() {
      return $this->hasMany(AcctAccountSetting::class,'account_setting_group_id','account_setting_group_id');
   }
}
