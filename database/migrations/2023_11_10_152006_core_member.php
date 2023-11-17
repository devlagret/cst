<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(!Schema::hasTable('core_member')) {
            Schema::create('core_member', function (Blueprint $table) {
                $table->id('member_id');
                $table->unsignedBigInteger('branch_id')->nullable();
                $table->foreign('branch_id')->references('branch_id')->on('core_branch')->onUpdate('cascade')->onDelete('set null');
                $table->unsignedBigInteger('province_id')->nullable();
                $table->foreign('province_id')->references('province_id')->on('core_province')->onUpdate('cascade')->onDelete('set null');
                $table->string('member_no',50)->nullable();
                $table->string('member_name')->nullable();
                $table->string('member_nick_name')->nullable();
                $table->boolean('member_gender')->nullable();
                $table->string('member_place_of_birth',100)->nullable();
                $table->date('member_date_of_birth')->nullable();
                $table->string('email')->nullable();
                $table->text('member_address')->nullable();
                $table->string('member_debet_preference')->nullable();
                $table->unsignedBigInteger('member_debet_savings_account_id')->nullable();
                $table->text('member_address_now')->nullable();
                $table->string('member_postal_code',10)->nullable();
                $table->string('member_home_phone',20)->nullable();
                $table->string('member_phone',20)->nullable();
                $table->tinyInteger('member_marital_status')->nullable();
                $table->tinyInteger('member_dependent')->nullable();
                $table->tinyInteger('member_home_status')->nullable();
                $table->string('member_long_stay',20)->nullable();
                $table->tinyInteger('member_vehicle')->nullable();
                $table->tinyInteger('member_last_education')->nullable();
                $table->tinyInteger('member_unit_user')->nullable();
                $table->string('member_partner_name')->nullable();
                $table->string('member_partner_place_of_birth',100)->nullable();
                $table->date('member_partner_date_of_birth')->nullable();
                $table->string('member_email',100)->nullable();
                $table->string('member_job',100)->nullable();
                $table->integer('identity_id')->nullable();
                $table->tinyInteger('member_identity')->nullable();
                $table->string('member_identity_no',50)->nullable();
                $table->string('member_partner_identity_no',50)->nullable();
                $table->tinyInteger('member_character')->nullable();
                $table->string('member_mother')->nullable();
                $table->string('member_heir')->nullable();
                $table->tinyInteger('member_heir_relationship')->nullable();
                $table->string('member_heir_mobile_phone',50)->nullable();
                $table->text('member_heir_address')->nullable();
                $table->string('member_family_relationship',50)->nullable();
                $table->tinyInteger('member_status')->nullable()->default(0);
                $table->tinyInteger('member_active_status')->nullable()->default(0);
                $table->date('member_register_date')->nullable();
                $table->decimal('member_principal_savings',20)->nullable()->default(0);
                $table->decimal('member_special_savings',20)->nullable()->default(0);
                $table->decimal('member_mandatory_savings',20)->nullable()->default(0);
                $table->decimal('member_principal_savings_last_balance',20)->nullable()->default(0);
                $table->decimal('member_special_savings_last_balance',20)->nullable()->default(0);
                $table->decimal('member_mandatory_savings_last_balance',20)->nullable()->default(0);
                $table->unsignedBigInteger('member_class_id')->nullable();
                $table->unsignedBigInteger('member_class_mandatory_savings')->nullable();
                $table->unsignedBigInteger('member_company_mandatory_savings')->nullable();
                $table->string('member_last_number',20)->nullable();
                $table->string('member_password_default')->nullable();
                $table->string('member_password')->nullable();
                $table->unsignedBigInteger('savings_account_id')->nullable();
                $table->string('member_no_old',50)->nullable();
                $table->tinyInteger('member_no_status')->nullable()->default(0);
                $table->tinyInteger('ppob_status')->nullable()->default(0);
                $table->tinyInteger('block_state')->nullable()->default(0);
                $table->uuid('auto_debet_member_account_token')->nullable();
                
                $table->uuid('member_token')->nullable();
                $table->uuid('member_token_edit')->nullable();
                $table->unsignedBigInteger('created_id')->nullable();
                $table->unsignedBigInteger('updated_id')->nullable();
                $table->unsignedBigInteger('deleted_id')->nullable();
                $table->timestamps();
                $table->softDeletesTz();
            });
            }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('core_member');
    }
};
