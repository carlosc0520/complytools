<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationDistrict extends Model {
  use HasFactory;

  protected $table = "tbop_distrito";

  protected $primaryKey = 'id';

  protected $keyType = 'string';

  public $timestamps = false;
}
