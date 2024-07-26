<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model {
  use HasFactory;

  protected $table = "tbd_empresauser";

  protected $primaryKey = "id";

  public $timestamps = false;
}
