<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationOcupation extends Model {
  use HasFactory;

  protected $table = "tbop_prof";

  protected $primaryKey = 'profid';

  public $timestamps = false;
}
