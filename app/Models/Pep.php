<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pep extends Model {
  use HasFactory;

  protected $table = "tbs_pep";

  protected $primaryKey = 'pepid';

  public $timestamps = false;
}
