<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationActivity extends Model {
  use HasFactory;

  protected $table = "tbop_actividad";

  protected $primaryKey = 'actividadid';

  public $timestamps = false;
}
