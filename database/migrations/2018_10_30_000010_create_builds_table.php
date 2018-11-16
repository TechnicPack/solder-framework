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

class CreateBuildsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('builds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('modpack_id');
            $table->string('tag');
            $table->string('minecraft_version');
            $table->string('java_version')->nullable();
            $table->integer('java_memory')->nullable();
            $table->timestamps();

            $table->foreign('modpack_id')
                ->references('id')->on('modpacks')
                ->onDelete('cascade');
        });
    }
}
