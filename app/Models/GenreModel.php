<?php

namespace App\Models;

use CodeIgniter\Model;

class GenreModel extends Model
{
    protected $table         = 'genres';    
    protected $allowedFields = ['name'];
    protected $returnType    = \App\Entities\Genre::class;
    protected $useTimestamps = false;
}