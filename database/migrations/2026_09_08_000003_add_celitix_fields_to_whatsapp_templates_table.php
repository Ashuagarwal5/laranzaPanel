<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCelitixFieldsToWhatsappTemplatesTable extends Migration
{
    public function up()
    {
        Schema::table('whatsapp_templates', function (Blueprint $table) {
            // Meta's own template id, and the raw component array as returned by
            // getTemplateList - we flatten it into the header/body/footer columns
            // for display, but the original is what we replay when sending.
            $table->string('template_uid', 64)->nullable()->after('id');
            $table->string('parameter_format', 20)->default('POSITIONAL')->after('category');
            $table->longText('components')->nullable()->after('buttons');
        });
    }

    public function down()
    {
        Schema::table('whatsapp_templates', function (Blueprint $table) {
            $table->dropColumn(['template_uid', 'parameter_format', 'components']);
        });
    }
}
