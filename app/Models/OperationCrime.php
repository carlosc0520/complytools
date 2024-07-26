<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationCrime extends Model {
  use HasFactory;

  protected $table = "tbop_delito";

  protected $primaryKey = 'delitoid';

  public $timestamps = false;
}
