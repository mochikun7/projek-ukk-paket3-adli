<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class InputAspirasi extends Model
{
    protected $primaryKey = 'id_pelaporan';
    
    protected $fillable = ['nis', 'id_kategori', 'lokasi', 'ket', 'foto'];
}