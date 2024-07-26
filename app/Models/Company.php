<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model {
  use HasFactory;

  protected $table = "tbd_empresa";

  protected $primaryKey = 'id';

  public $timestamps = false;
}
