<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationBeneficiary extends Model {
  use HasFactory;

  protected $table = "tbop_beneficiariooperacion";

  protected $primaryKey = 'beneopid';

  public $timestamps = false;
}
