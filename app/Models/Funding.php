<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funding extends Model {
  use HasFactory;

  protected $table = "tbs_fondo";

  protected $primaryKey = 'fondoid';

  public $timestamps = false;
}
