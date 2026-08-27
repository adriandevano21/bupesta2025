<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ist_BerkasKepala extends Model
{
    protected $table = 'ist_berkas_kepala';
    protected $fillable = ['periode_id', 'urutan', 'nama_dokumen', 'keterangan', 'link_template'];
}
