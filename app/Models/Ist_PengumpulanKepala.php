<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ist_PengumpulanKepala extends Model
{
    protected $table = 'ist_pengumpulan_kepala';
    protected $fillable = ['periode_id', 'berkas_id', 'nip_kepala', 'link_berkas', 'catatan'];
}