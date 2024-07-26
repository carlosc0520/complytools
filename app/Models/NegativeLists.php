<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegativeLists extends Model {
  use HasFactory;

  protected $table = "tbl_info";

  public $timestamps = false;

  public function decode($str) {
    return mb_convert_encoding($str, "Windows-1252", "UTF-8");
  }
}
