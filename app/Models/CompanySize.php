<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySize extends Model {
  use HasFactory;

  protected $table = "tbs_tamano";

  protected $primaryKey = 'tamanoid';

  public $timestamps = false;
}
