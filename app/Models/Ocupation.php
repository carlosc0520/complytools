<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ocupation extends Model {
  use HasFactory;

  protected $table = "tbs_ocupacion";

  protected $primaryKey = 'ocupacionid';

  public $timestamps = false;
}
