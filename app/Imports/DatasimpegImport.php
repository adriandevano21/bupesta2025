<?php

namespace App\Imports;

use App\Models\Bupesta_Datasimpeg;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Mengambil header di baris 1

class DatasimpegImport implements ToModel, WithHeadingRow
{
    protected $tanggal_versidata;

    // Menangkap inputan tanggal_versidata dari Controller
    public function __construct($tanggal_versidata)
    {
        $this->tanggal_versidata = $tanggal_versidata;
    }

    public function model(array $row)
    {
        /* Package Maatwebsite otomatis akan "membersihkan" judul di header Excel.
         Contoh: Header "NIP BPS" terbaca sebagai "nip_bps", "Kode.Org" sebagai "kode_org"
         */
        return new Bupesta_Datasimpeg([
            'nip_bps'           => $row['nip_bps'] ?? null,
            'nip'               => $row['nip'] ?? null,
            'nama'              => $row['nama'] ?? null,
            'kode_org'          => $row['kodeorg'] ?? null,
            'jabatan'           => $row['jabatan'] ?? null,
            'wilayah'           => $row['wilayah'] ?? null,
            'tmt_jab'           => $row['tmt_jab'] ?? null,
            'gol_akhir'         => $row['golakhir'] ?? null,
            'tmt_gol'           => $row['tmt_gol'] ?? null,
            'status'            => $row['status'] ?? null,
            'pend_sk'           => $row['pendsk'] ?? null,
            'mks_thn'           => $row['mks_thn'] ?? null,
            'mks_bln'           => $row['mks_bln'] ?? null,
            'tempat_lahir'      => $row['tempat_lahir'] ?? null,

            // Masukkan variabel tanggal dari form secara paksa ke setiap baris
            'tanggal_versidata' => $this->tanggal_versidata,
        ]);
    }
}
