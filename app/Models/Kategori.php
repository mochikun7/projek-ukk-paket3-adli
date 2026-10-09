<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $primaryKey = 'id_kategori';
    protected $fillable = ['ket_kategori'];
}