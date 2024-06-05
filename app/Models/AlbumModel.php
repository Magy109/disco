<?php

namespace App\Models;

use CodeIgniter\Model;

class AlbumModel extends Model
{
    protected $table         = 'albums';    
    protected $allowedFields = ['name', 'author', 'genre_id']; // Agrega los campos necesarios
    protected $returnType    = \App\Entities\Album::class; // Utiliza la entidad Album
    protected $useTimestamps = false;
} 
