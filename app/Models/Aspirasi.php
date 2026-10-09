<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Aspirasi extends Model
{
    protected $primaryKey = 'id_aspirasi';
    
    protected $fillable = ['id_pelaporan', 'id_kategori', 'status', 'feedback'];
}