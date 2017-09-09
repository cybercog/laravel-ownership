<?php

/*
 * This file is part of Laravel Ownership.
 *
 * (c) Anton Komarev <a.komarev@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateGroupTable.
 */
class CreateGroupTable extends Migration
{
    public function up()
    {
        Schema::create('group', function (Blueprint $table) {
            $table->increments('gid');
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('group');
    }
}
