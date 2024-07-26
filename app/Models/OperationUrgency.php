<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationUrgency extends Model {
  use HasFactory;

  protected $table = "tbop_urgencia";

  protected $primaryKey = 'urgenciaid';

  public $timestamps = false;
}
