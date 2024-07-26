<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationPeople extends Model {
  use HasFactory;

  protected $table = "tbop_personaoperacion";

  protected $primaryKey = 'peropid';

  public $timestamps = false;
}
