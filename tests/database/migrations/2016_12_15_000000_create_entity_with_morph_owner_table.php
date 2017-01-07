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
 * Class CreateEntityWithMorphOwnerTable.
 */
class CreateEntityWithMorphOwnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_with_morph_owner', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('owned_by_id')->unsigned()->nullable();
            $table->string('owned_by_type')->nullable();
            $table->timestamps();

            $table->index([
                'owned_by_id',
                'owned_by_type',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entity_with_morph_owner');
    }
}
