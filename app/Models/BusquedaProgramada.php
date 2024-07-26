<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusquedaProgramada extends Model {
  use HasFactory;

  protected $table = "tb_busqueda_programada";

  protected $primaryKey = 'ID';

  public $timestamps = false;
}
