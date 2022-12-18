<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelNews extends Model
{
    protected $table = 'news';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'judul','deskripsi'
    ];

}