<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessMeta extends Model {
  use HasFactory;

  protected $table = "wp_usermeta";
}
