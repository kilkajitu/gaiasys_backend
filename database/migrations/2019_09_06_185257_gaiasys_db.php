<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GaiasysDb extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('username', 50);
            $table->string('password', 60);
            $table->tinyInteger('role');
            $table->timestamps();
        });
        Schema::create('userrole', function (Blueprint $table) {
            $table->string('name', 100);
            $table->tinyInteger('role');
        });
        Schema::create('toolgroups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->timestamps();
        });
        Schema::create('tools', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->date('purchasedate');
            $table->double('costprice', 10, 4);
            $table->unsignedInteger('toolgroup_id');
            $table->timestamps();
        });
        Schema::create('usermapping', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('toolgroup_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
        });
        //inserting roles
        DB::table('userrole')->insert([
            ['name' => 'admin','role' => 1],
            ['name' => 'user','role' => 0]
        ]);
        //creating admin
        DB::table('users')->insert([
            ['name' => 'admin','username' => 'admin@gaiasys.in','password'=>password_hash('admin', PASSWORD_BCRYPT),'role'=>1,'created_at'=>date("Y-m-d h:i:s"),'updated_at'=>date("Y-m-d h:i:s")]
        ]);
        Schema::table(
            'tools',
            function ($table) {
                $table->foreign('toolgroup_id')
                    ->references('id')->on('toolgroups')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            }

        );
        Schema::table(
            'usermapping',
            function ($table) {
                $table->foreign('toolgroup_id')
                    ->references('id')->on('toolgroups')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
                $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            }

        );
    }
    public function down()
    {
        Schema::dropIfExists('tools');
        Schema::dropIfExists('usermapping');
        Schema::dropIfExists('toolgroups');
        Schema::dropIfExists('users');
    }
}
