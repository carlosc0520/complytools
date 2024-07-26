<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Casts\Attribute;

class Risk extends Model {
  use HasFactory;

  protected $table = "tb_riesgo";

  protected $primaryKey = 'riesgo_id';

  public $timestamps = false;

  public function decode($str) {
    $isWindows1252 = preg_match('/Ã/u', $str);
    // $isWindows1252 = str_contains($str, "Ã");
    if ($isWindows1252) {
      return iconv('UTF-8', 'Windows-1252', $str);
    }
    return $str;
  }

  protected function title(): Attribute {
    /* PHP 8
    return new Attribute(
      get: fn($value, $attributes) => strtolower($attributes['display_name']),
    );*/

    // PHP 7
    /*return new Attribute(
      fn($value, $attributes) => strtolower($attributes['display_name']),
    );*/
    return Attribute::get(
      fn($value, $attributes) => $this->decode($attributes['titulo']),
    );
  }

  protected function ctrlDocument(): Attribute {
    return Attribute::get(
      fn($value, $attributes) => $this->decode($attributes['documento_fuente']),
    );
  }

  protected function planDescr(): Attribute {
    return Attribute::get(
      fn($value, $attributes) => $this->decode($attributes['plan_accion'], "Windows-1252", "UTF-8"),
    );
  }
}
