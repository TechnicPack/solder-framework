<?php

/*
 * This file is part of the TechnicPack Solder Framework.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModpackAuthorizedClientsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('modpack_authorized_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('modpack_id');
            $table->unsignedInteger('launcher_client_id');
        });
    }
}
