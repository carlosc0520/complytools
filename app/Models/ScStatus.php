<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScStatus extends Model {
  use HasFactory;

  protected $table = 'tbs_estado';

  protected $primaryKey = 'estadoid';

  public $timestamps = false;
}
