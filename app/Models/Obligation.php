<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obligation extends Model {
  use HasFactory;

  protected $table = "tbs_obligado";

  protected $primaryKey = 'obligadoid';

  public $timestamps = false;
}
