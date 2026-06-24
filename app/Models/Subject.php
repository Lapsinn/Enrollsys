<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Subject extends Model
{
    public function up()
{
    Schema::create('subjects', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique(); // Subject Code (e.g., CS101)
        $table->string('name');           // Subject Name
        $table->integer('units');         // Units
        $table->timestamps();
    });
}
}
