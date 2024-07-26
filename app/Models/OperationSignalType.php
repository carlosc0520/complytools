<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationSignalType extends Model {
  use HasFactory;

  protected $table = "tbop_tsenal";

  protected $primaryKey = 'senalid';

  public $timestamps = false;
}
