<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserArea extends Model {
  use HasFactory;

  protected $table = "tb_usuario_area";

  protected $primaryKey = "usuario_area_id";

  public $timestamps = false;
}
