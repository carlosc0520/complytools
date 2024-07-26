<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensible extends Model {
  use HasFactory;

  protected $table = "tbs_sensible";

  protected $primaryKey = 'sensibleid';

  public $timestamps = false;
}
