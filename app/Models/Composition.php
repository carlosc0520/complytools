<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Composition extends Model {
  use HasFactory;

  protected $table = "tbs_composicion";

  protected $primaryKey = 'composicionid';

  public $timestamps = false;
}
