<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model {
  use HasFactory;

  protected $table = "tbs_paises";

  protected $primaryKey = 'paisid';

  public $timestamps = false;
}
