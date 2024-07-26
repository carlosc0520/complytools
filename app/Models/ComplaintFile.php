<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintFile extends Model {
  use HasFactory;

  protected $table = "tbd_documento";

  protected $primaryKey = 'id';

  public $timestamps = false;
}
