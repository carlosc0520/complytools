<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationDocumentType extends Model {
  use HasFactory;

  protected $table = "tbop_tipodoc";

  protected $primaryKey = 'tipodocid';

  public $timestamps = false;
}
