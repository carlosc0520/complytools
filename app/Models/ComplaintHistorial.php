<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintHistorial extends Model {
  use HasFactory;

  protected $table = "tbd_historial";

  protected $primaryKey = 'id';

  public $timestamps = false;
}
