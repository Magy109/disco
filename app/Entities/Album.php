<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Album extends Entity
{
    protected $id;
    protected $name;
    protected $author;
    protected $genre_id;
}
