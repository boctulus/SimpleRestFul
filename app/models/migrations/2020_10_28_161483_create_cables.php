<?php

use simplerest\core\Schema;

class CreateCables /* implements IMigration */
{
    /**
	* Run migration.
    *
    * @return void
    */
    public function up()
    {
        $s = new Schema('cables');

        $s
        ->int('id')->unsigned()->auto()->pri()
        ->varchar('nombre', 40)
        ->float('calibre')

        ->create();
    }

    public function down()
    {
        Schema::drop('cables');
    }
}

