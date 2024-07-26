<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaProcess extends Model {
  use HasFactory;

  protected $table = "tb_area_proceso";

  protected $primaryKey = 'area_proceso_id';

  public $timestamps = false;
}
