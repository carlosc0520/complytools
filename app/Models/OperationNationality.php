<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationNationality extends Model {
  use HasFactory;

  protected $table = "tbop_nacionalidad";

  protected $primaryKey = 'nacionalidadid';

  public $timestamps = false;
}
