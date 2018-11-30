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

class CreateVersionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('versions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('mod_id');
            $table->string('tag');
            $table->string('package')->nullable();
            $table->string('package_name')->nullable();
            $table->unsignedInteger('package_size')->nullable();
            $table->string('package_hash')->nullable();
            $table->timestamps();
        });
    }
}
