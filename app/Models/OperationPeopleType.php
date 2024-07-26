<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationPeopleType extends Model {
  use HasFactory;

  protected $table = "tbop_tipopersona";

  protected $primaryKey = 'tipopersonaid';

  public $timestamps = false;
}
