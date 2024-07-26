<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model {
  use HasFactory;

  protected $table = "tb_division_area";

  protected $primaryKey = 'division_area_id';

  public $timestamps = false;
}
