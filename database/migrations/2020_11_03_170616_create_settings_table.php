<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AppModels\Setting;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('sms_service_api_key')->nullable();
            $table->text('about_us_text')->nullable();
            $table->text('advertise_default_image')->nullable();
            $table->text('logo_image')->nullable();
            $table->string('telegram_image')->nullable();
            $table->string('instagram_image')->nullable();
            $table->string('telegram_address')->nullable();
            $table->string('instagram_address')->nullable();
            $table->timestamps();
        });

        Setting::create([
            'sms_service_api_key'=>'',
            'about_us_text'=>'',
            'advertise_default_image'=>'',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
