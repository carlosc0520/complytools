<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Casts\Attribute;

class ComplaintRelation extends Model {
  use HasFactory;

  protected $table = "tbd_persona";

  protected $primaryKey = 'id';

  public $timestamps = false;

  public function setType($number) {
    $type = '';
    switch ($number) {
      case 0:
        $type = 'Persona Natural';
        break;
      case 1:
        $type = 'Persona Jurídica';
        break;
      default:
        break;
    }
    return $type;
  }

  protected function type(): Attribute {
    return Attribute::get(
      fn($value, $attributes) => $this->setType($attributes['type']),
    );
  }
}
