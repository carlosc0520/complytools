<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
  use HasFactory;

  protected $table = "tbs_producto";

  protected $primaryKey = 'productoid';

  public $timestamps = false;
}
