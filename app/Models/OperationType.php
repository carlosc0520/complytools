<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationType extends Model {
  use HasFactory;

  protected $table = "tbop_tipo";

  protected $primaryKey = 'tipoid';

  public $timestamps = false;
}
