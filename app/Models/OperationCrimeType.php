<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationCrimeType extends Model {
  use HasFactory;

  protected $table = "tbop_tipodelito";

  protected $primaryKey = 'tipodelitoid';

  public $timestamps = false;
}
