<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model {
  use HasFactory;

  protected $table = "tbs_oficina";

  protected $primaryKey = 'oficinaid';

  public $timestamps = false;
}
