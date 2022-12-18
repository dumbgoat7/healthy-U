<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelvaccination extends Model
{
    protected $table            = 'vaccinations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'program','idUser','tanggal','pelaksana'
    ];
}
