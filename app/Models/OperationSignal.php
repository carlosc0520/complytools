<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationSignal extends Model {
  use HasFactory;

  protected $table = "tbop_opsenales";

  protected $primaryKey = 'opsenalesid';

  public $timestamps = false;
}
