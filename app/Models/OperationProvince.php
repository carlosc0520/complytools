<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationProvince extends Model {
  use HasFactory;

  protected $table = "tbop_provincia";

  protected $primaryKey = 'id';

  protected $keyType = 'string';

  public $timestamps = false;
}
