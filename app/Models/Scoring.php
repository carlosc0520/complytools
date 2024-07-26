<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scoring extends Model {
  use HasFactory;

  protected $table = "tbs_score";

  protected $primaryKey = 'scoreid';

  public $timestamps = false;
}
