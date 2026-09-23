<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatsappTemplatesTable extends Migration
{
    public function up()
    {
        Schema::create('whatsapp_templates', function (Blueprint $table) {
            $table->increments('id');
            // Local mirror of the templates created and Meta-approved in the
            // Celitix panel, so the configuration dropdown does not have to hit
            // their API on every page load.
            $table->string('template_name', 255);
            $table->string('language', 20)->default('en');
            $table->string('category', 50)->nullable();
            $table->string('header_type', 20)->nullable();
            $table->text('header_text')->nullable();
            $table->longText('body_text')->nullable();
            $table->text('footer_text')->nullable();
            $table->longText('buttons')->nullable();
            $table->unsignedInteger('variable_count')->default(0);
            $table->string('approval_status', 30)->default('APPROVED');
            $table->timestamp('synced_at')->nullable();
            $table->string('ip', 255)->nullable();
            $table->unsignedInteger('site_id')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['template_name', 'language'], 'whatsapp_templates_name_lang_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('whatsapp_templates');
    }
}
