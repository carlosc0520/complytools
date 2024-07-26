<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegativeListsMeta extends Model{
  use HasFactory;

  protected $table = "tbl_busqueda";

  protected $primaryKey = 'busquedaid';

  public $timestamps = false;
}
