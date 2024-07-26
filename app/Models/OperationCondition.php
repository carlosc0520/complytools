<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationCondition extends Model {
  use HasFactory;

  protected $table = "tbop_cond";

  protected $primaryKey = 'condid';

  public $timestamps = false;
}
