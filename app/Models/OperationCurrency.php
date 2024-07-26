<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationCurrency extends Model {
  use HasFactory;

  protected $table = "tbop_moneda";

  protected $primaryKey = 'monedaid';

  public $timestamps = false;
}
