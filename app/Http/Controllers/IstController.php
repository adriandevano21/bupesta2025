<?php

namespace App\Http\Controllers;

use App\Models\Bupesta_User;
use App\Models\Ist_PenilaianTahap1;
use App\Models\Ist_Periode;
use App\Models\Ist_Pertanyaan;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IstController extends Controller
{
    // =========================================================================
    // HELPER: Ambil user aktif
    // Lokal  : gunakan NIP hardcode untuk development
    // Produksi: gunakan auth()->user()->nip_pegawai setelah deploy
    // =========================================================================
    private function getUserActive()
    {
        // --- MODE LOKAL (development) ---
        return Bupesta_User::where('nip_pegawai', '199906212022011001')->first();

        // --- MODE PRODUKSI (aktifkan baris ini & nonaktifkan baris di atas setelah deploy) ---
        // return Bupesta_User::where('nip_pegawai', auth()->user()->nip_pegawai)->first();
    }

    public function index(Request $request)
    {
        // 1. DEFAULT AWAL & LOGGING (Tahap Testing)
        UserActivity::log("https://bupesta.web.bps.go.id/ist");

        // Ambil tahun dari request, default ke tahun saat ini
        $tahun = $request->input('tahun', date('Y'));

        $data = [];
        // Menggunakan helper getUserActive untuk mode testing / production
        $data["user_active"] = $this->getUserActive();

        $data["id_judul"] = "4"; // Agar menu navbar BuPeSta tetap menyala
        $data["judul"] = "Pemilihan IST";

        // Menentukan role pengguna aktif
        $userRole = $data["user_active"]->ist ?? '';
        $userSatker = $data["user_active"]->kode_satker ?? '';
        $userNip = $data["user_active"]->nip_pegawai ?? '';
        $isAdmin = $userRole === 'admin';

        $now = Carbon::now();
        $today = $now->copy()->startOfDay();

        // 2. LOGIKA PENENTUAN STEP AKTIF UNTUK TIMELINE (7 TAHAPAN)
        $periode = Ist_Periode::where('tahun', $tahun)->first();

        // NILAI DEFAULT AWAL ADALAH 0 (TIDAK ADA PERIODE AKTIF)
        $activeStep = 0;

        // Ambil data pertanyaan Kuesioner
        $pertanyaanKuesioner = [];
        if ($isAdmin || $userRole === 'panitia') {
            $pertanyaanKuesioner = Ist_Pertanyaan::all();
        } else {
            $pertanyaanKuesioner = Ist_Pertanyaan::where('is_active', true)->get();
        }

        if ($periode && $periode->is_active) {

            // Jika masuk ke sini (ada periode aktif), barulah nilai terendahnya adalah 1 (Persiapan)
            $activeStep = 1;

            // 2: Tahap 1.1 (Pemilihan Pegawai)
            if (!empty($periode->mulai_tahap1_1) && $today->gte(Carbon::parse($periode->mulai_tahap1_1)->startOfDay())) {
                $activeStep = 2;
            }

            // 3: Tahap 1.2 (Penetapan Kepala Satker)
            if (!empty($periode->mulai_tahap1_2) && $today->gte(Carbon::parse($periode->mulai_tahap1_2)->startOfDay())) {
                $activeStep = 3;
            }

            // 4: Persiapan Tahap 2 (Masa Tunggu / Jeda setelah Tahap 1.2 selesai)
            if (!empty($periode->akhir_tahap1_2) && $today->gt(Carbon::parse($periode->akhir_tahap1_2)->startOfDay())) {
                $activeStep = 4;
            }

            // 5: Tahap 2.1 (Seleksi Provinsi & Berkas)
            if (!empty($periode->mulai_tahap2_1) && $today->gte(Carbon::parse($periode->mulai_tahap2_1)->startOfDay())) {
                $activeStep = 5;
            }

            // 6: Tahap 2.2 (Penilaian Provinsi)
            if (!empty($periode->mulai_tahap2_2) && $today->gte(Carbon::parse($periode->mulai_tahap2_2)->startOfDay())) {
                $activeStep = 6;
            }

            // 7: Pengumuman Akhir
            if (!empty($periode->tanggal_pengumuman) && $today->gte(Carbon::parse($periode->tanggal_pengumuman)->startOfDay())) {
                $activeStep = 7;
            }
        }

        // ====================================================================
        // LOGIKA TAHAP 1_1: PEMILIHAN DAN PENILAIAN PEGAWAI
        // ====================================================================
        $kandidatSatker = [];
        $pertanyaanKuesioner = [];
        $sudahMenilai = false;
        $pilihanUser = [];

        // Ambil data pertanyaan Kuesioner (Sesuai update kita sebelumnya)
        if ($isAdmin || $userRole === 'panitia') {
            $pertanyaanKuesioner = Ist_Pertanyaan::all(); // Admin lihat semua untuk manajemen
        } else {
            $pertanyaanKuesioner = Ist_Pertanyaan::where('is_active', true)->get(); // Pegawai lihat yang aktif
        }

        if ($activeStep >= 2 && $periode) {
            // 1. Ambil daftar pegawai 1 satker menggunakan View ist_listtahap1_2026
            $kandidatSatker = DB::table('ist_listtahap1_' . $tahun)
                ->where('kode_wilayah', $data['user_active']->kode_satker)
                // Pengecualian: Pegawai tidak boleh memilih dirinya sendiri
                ->where('nip', '!=', $data['user_active']->nip_pegawai)
                ->get();

            // 2. Cek apakah user aktif sudah mengisi kuesioner di periode ini
            $cekPenilaian = Ist_PenilaianTahap1::where('periode_id', $periode->id)
                ->where('nip_pemilih', $data['user_active']->nip_pegawai)
                ->get();

            if ($cekPenilaian->count() > 0) {
                $sudahMenilai = true;
                $pilihanUser = DB::table('ist_penilaian_tahap1 as p')
                    ->join('ist_datapegawai as d', 'p.nip_kandidat', '=', 'd.nip')
                    ->where('p.periode_id', $periode->id)
                    ->where('p.nip_pemilih', $data['user_active']->nip_pegawai)
                    // Tambahkan d.pend_sk di baris bawah ini:
                    ->select('d.nip', 'd.nama', 'd.jabatan', 'd.gol_akhir', 'd.pend_sk', 'd.url_foto', 'p.skor_kuesioner', 'p.detail_jawaban')
                    ->orderByDesc('p.skor_kuesioner')
                    ->get();
            }
        }

        // ====================================================================
        // LOGIKA MONITORING PENILAIAN (KHUSUS PIMPINAN & PANITIA)
        // ====================================================================
        $monitoringData = [];
        $isMonitoringAuthorized = in_array($userRole, ['admin', 'panitia', 'kepala', 'kepala-kako']);

        if ($isMonitoringAuthorized && $periode) {
            // Ambil semua pegawai dan cocokkan dengan data voting
            $queryPegawai = DB::table('ist_datapegawai as d')
                ->leftJoin('ist_penilaian_tahap1 as p', function ($join) use ($periode) {
                    $join->on('d.nip', '=', 'p.nip_pemilih')
                        ->where('p.periode_id', '=', $periode->id);
                })
                ->select(
                    'd.nip',
                    'd.nama',
                    'd.kode_wilayah',
                    'd.wilayah',
                    DB::raw('COUNT(p.id) as jml_vote') // Jika > 0 berarti sudah voting
                )
                ->groupBy('d.nip', 'd.nama', 'd.kode_wilayah', 'd.wilayah');

            // Aturan Visibilitas Data
            if (in_array($userRole, ['admin', 'panitia']) || ($userRole === 'kepala' && $userSatker == '1100')) {
                // Bisa melihat semua satker se-Provinsi Aceh
                $queryPegawai->whereNotNull('d.kode_wilayah');
            } else {
                // Kepala-Kako hanya melihat satkernya sendiri
                $queryPegawai->where('d.kode_wilayah', $userSatker);
            }

            $rawMonitoring = $queryPegawai->get();

            // Format dan Kelompokkan Data
            $rekapMonitoring = [];
            $detailBelum = [];

            foreach ($rawMonitoring as $peg) {
                // Buat kunci array format: "1100 - Provinsi Aceh"
                $namaSatker = $peg->kode_wilayah . ' - ' . $peg->wilayah;

                if (!isset($rekapMonitoring[$namaSatker])) {
                    $rekapMonitoring[$namaSatker] = [
                        'kode_wilayah' => $peg->kode_wilayah,
                        'wilayah' => $peg->wilayah,
                        'total' => 0,
                        'sudah' => 0,
                        'belum' => 0
                    ];
                }

                $rekapMonitoring[$namaSatker]['total']++;

                if ($peg->jml_vote > 0) {
                    $rekapMonitoring[$namaSatker]['sudah']++;
                } else {
                    $rekapMonitoring[$namaSatker]['belum']++;
                    $detailBelum[$namaSatker][] = $peg;
                }
            }

            // Urutkan berdasarkan Kode Satker (1100, 1101, dst)
            ksort($rekapMonitoring);
            ksort($detailBelum);

            // =========================================================
            // TAMBAHAN: LOGIKA MONITORING TAHAP 1.2 (PENETAPAN KANDIDAT)
            // =========================================================
            $monitoringTahap1_2 = [];

            // 1. Ambil daftar Satker sesuai hak akses (seperti filter di atas)
            $querySatker = DB::table('ist_datapegawai')
                ->select('kode_wilayah', 'wilayah')
                ->whereNotNull('kode_wilayah')
                ->distinct();

            if (!in_array($userRole, ['admin', 'panitia']) && !($userRole === 'kepala' && $userSatker == '1100')) {
                $querySatker->where('kode_wilayah', $userSatker);
            }
            $listSatker = $querySatker->get();

            // 2. Ambil data kandidat yang sudah ditetapkan (Tahap 1.2) di periode aktif
            $penetapan = DB::table('ist_kandidat_tahap2 as k')
                ->join('ist_datapegawai as d', 'k.nip_kandidat', '=', 'd.nip')
                ->where('k.periode_id', $periode->id)
                ->select('d.kode_wilayah', 'd.nama', 'k.nip_kandidat', 'k.link_sk', 'k.validasi_sk')
                ->get()
                ->keyBy('kode_wilayah'); // Jadikan kode wilayah sebagai Key array

            // 3. Gabungkan dan cocokkan data
            foreach ($listSatker as $s) {
                $namaSatker = $s->kode_wilayah . ' - ' . $s->wilayah;
                $p = $penetapan[$s->kode_wilayah] ?? null;
                $monitoringTahap1_2[$namaSatker] = [
                    'kode_wilayah'  => $s->kode_wilayah,
                    'wilayah'       => $s->wilayah,
                    'status'        => $p ? 'Sudah' : 'Belum',
                    'nama_kandidat' => $p ? $p->nama : '-',
                    'nip_kandidat'  => $p ? $p->nip_kandidat : '',
                    'link_sk'       => $p->link_sk ?? null,
                    // null=belum/menunggu | 'tervalidasi' | 'ditolak'
                    'validasi_sk'   => $p->validasi_sk ?? null,
                ];
            }

            // Urutkan berdasarkan Kode Satker
            ksort($monitoringTahap1_2);

            // =========================================================
            // UPDATE ARRAY FINAL $monitoringData
            // =========================================================
            $monitoringData = [
                'rekap' => $rekapMonitoring,
                'detail_belum' => $detailBelum,
                'tahap1_2' => $monitoringTahap1_2 // <-- Masukkan data Tahap 1.2 ke sini
            ];
        }

        // ====================================================================
        // LOGIKA TAHAP 1.2: PENETAPAN KANDIDAT OLEH PEJABAT
        // ====================================================================
        $rekapVoting = [];
        $kandidatTerpilih = null;
        $isPejabat = in_array($userRole, ['admin', 'kepala', 'kepala-kako', 'kabag', 'kasubbag', 'panitia']);

        if ($activeStep >= 3 && $periode) {
            // Rekap Akumulasi Skor per Kandidat di Satker aktif
            $rekapVoting = DB::table('ist_penilaian_tahap1 as p')
                ->join('ist_datapegawai as d', 'p.nip_kandidat', '=', 'd.nip')
                ->where('p.periode_id', $periode->id)
                ->where('d.kode_wilayah', $data['user_active']->kode_satker)
                ->select(
                    'd.nip',
                    'd.nama',
                    'd.jabatan',
                    'd.gol_akhir',
                    'd.url_foto',
                    'd.pend_sk',
                    'd.wilayah',
                    DB::raw('SUM(p.skor_kuesioner) as total_skor'),
                    DB::raw('COUNT(p.id) as total_pemilih')
                )
                ->groupBy('d.nip', 'd.nama', 'd.jabatan', 'd.gol_akhir', 'd.url_foto', 'd.pend_sk', 'd.wilayah')
                ->orderByDesc('total_skor') // Urutkan dari skor tertinggi
                ->get();

            // Cek apakah Pejabat sudah menekan tombol "Tetapkan" untuk Satker ini
            $kandidatData = DB::table('ist_kandidat_tahap2 as k')
                ->join('ist_datapegawai as d', 'k.nip_kandidat', '=', 'd.nip')
                ->where('k.periode_id', $periode->id)
                ->where('d.kode_wilayah', $data['user_active']->kode_satker)
                ->select('k.*', 'd.nip', 'd.nama', 'd.jabatan', 'd.gol_akhir', 'd.url_foto', 'd.pend_sk', 'd.wilayah', 'd.kode_wilayah')
                ->first();

            if ($kandidatData) {
                $kandidatTerpilih = $kandidatData;
                // Menyisipkan nilai skor ke object terpilih dari data rekap
                $skorData = collect($rekapVoting)->firstWhere('nip', $kandidatData->nip_kandidat);
                $kandidatTerpilih->total_skor = $skorData?->total_skor ?? 0;
            }
        }

        // ====================================================================
        // LOGIKA TAHAP 2 (STEP 4): PENGUMUMAN TAHAP 1 & PERSIAPAN TAHAP 2
        // ====================================================================
        $semuaKandidatTahap2 = [];

        // Asumsi $activeStep == 4 adalah Tahap Pengumuman/Berkas
        if ($activeStep >= 4 && $periode) {
            $semuaKandidatTahap2 = DB::table('ist_kandidat_tahap2 as k')
                ->join('ist_datapegawai as d', 'k.nip_kandidat', '=', 'd.nip')
                ->where('k.periode_id', $periode->id)
                ->select(
                    'd.nip',
                    'd.nama',
                    'd.jabatan',
                    'd.gol_akhir',
                    'd.url_foto',
                    'd.pend_sk',
                    'd.wilayah',
                    'd.kode_wilayah'
                )
                ->orderBy('d.kode_wilayah', 'asc') // Urutkan kode wilayah dari 1100, 1101, dst.
                ->get();
        }

        // ==========================================
        // TAHAP 2: PENGUMPULAN BERKAS PROVINSI
        // ==========================================
        $daftarBerkas = collect();
        $isKandidatTahap2 = false;
        $berkasTerkumpul = collect();
        $pesertaTahap2 = collect();
        $semuaPengumpulan = collect();

        if ($activeStep >= 5 && $periode && $periode->is_active) {
            // 1. Ambil Master Daftar Dokumen dari Admin
            $daftarBerkas = \App\Models\Ist_BerkasProvinsi::where('periode_id', $periode->id)
                            ->orderBy('urutan', 'asc')->get();

            // 2. Jika user adalah Admin atau Panitia, ambil Basic List Kandidat Tahap 2
            if ($isAdmin || $userRole == 'panitia') {
                // Ambil daftar kandidat yang sudah ditetapkan di Tahap 1_2 (Join ke view ist_datapegawai)
                $pesertaTahap2 = DB::table('ist_kandidat_tahap2 as k2')
                    ->join('ist_datapegawai as p', 'k2.nip_kandidat', '=', 'p.nip')
                    ->where('k2.periode_id', $periode->id)
                    ->select('k2.nip_kandidat', 'p.nama', 'p.jabatan', 'p.wilayah', 'p.url_foto')
                    ->get();

                // Ambil data pengumpulan, jadikan collection, lalu kelompokkan per NIP
                $semuaPengumpulan = DB::table('ist_pengumpulan_berkas')
                    ->where('periode_id', $periode->id)
                    ->get()
                    ->groupBy('nip_kandidat');
            }

            // 3. Cek apakah user aktif adalah Kandidat Tahap 2
            $kandidatAktif = DB::table('ist_kandidat_tahap2')
                            ->where('periode_id', $periode->id)
                            ->where('nip_kandidat', $userNip)->first();

            if ($kandidatAktif) {
                $isKandidatTahap2 = true;
                $berkasTerkumpul = DB::table('ist_pengumpulan_berkas')
                                    ->where('periode_id', $periode->id)
                                    ->where('nip_kandidat', $userNip)
                                    ->get()->keyBy('berkas_id');
            }
        }

        // Return ke view khusus timeline (nanti kita tambahkan data tahapannya satu per satu)
        return view('ist.ist', compact(
            'data',
            'tahun',
            'periode',
            'activeStep',
            'isAdmin',
            'kandidatSatker',
            'pertanyaanKuesioner',
            'sudahMenilai',
            'pilihanUser',
            'monitoringData',
            'rekapVoting',
            'kandidatTerpilih',
            'isPejabat',
            'semuaKandidatTahap2',
            'isKandidatTahap2',
            'pesertaTahap2',
            'semuaPengumpulan',
            'daftarBerkas'
        ));
    }

    // --- FUNGSI ADMIN UPDATE PENGATURAN PERIODE ---
    public function updatePeriode(Request $request)
    {
        $tahun = $request->input('tahun');

        // Menggunakan updateOrCreate agar otomatis membuat data baru jika belum ada periode di tahun tersebut
        Ist_Periode::updateOrCreate(
            ['tahun' => $tahun],
            [
                'is_active' => $request->input('is_active', false),
                'informasi_persiapan' => $request->input('informasi_persiapan'),
                'mulai_tahap1_1' => $request->input('mulai_tahap1_1'),
                'akhir_tahap1_1' => $request->input('akhir_tahap1_1'),
                'mulai_tahap1_2' => $request->input('mulai_tahap1_2'),
                'akhir_tahap1_2' => $request->input('akhir_tahap1_2'),
                'mulai_tahap2_1' => $request->input('mulai_tahap2_1'),
                'akhir_tahap2_1' => $request->input('akhir_tahap2_1'),
                'mulai_tahap2_2' => $request->input('mulai_tahap2_2'),
                'akhir_tahap2_2' => $request->input('akhir_tahap2_2'),
                'tanggal_pengumuman' => $request->input('tanggal_pengumuman')
            ]
        );

        return redirect()->back()->with('success', 'Pengaturan Periode berhasil diperbarui!');
    }

    // --- FUNGSI ADMIN MANAJEMEN PERTANYAAN KUESIONER ---
    public function storePertanyaan(Request $request)
    {
        $ids = $request->input('id_pertanyaan', []);
        $pertanyaans = $request->input('pertanyaan', []);
        $isActives = $request->input('is_active', []);

        // 1. Cek jika ada baris pertanyaan lama di Database yang dihapus oleh admin dari antarmuka
        $existingIds = Ist_Pertanyaan::pluck('id')->toArray();
        $idsToDelete = array_diff($existingIds, array_filter($ids));
        if (!empty($idsToDelete)) {
            Ist_Pertanyaan::whereIn('id', $idsToDelete)->delete();
        }

        // 2. Loop untuk Update pertanyaan lama / Insert pertanyaan baru
        if (!empty($pertanyaans)) {
            foreach ($pertanyaans as $index => $teks) {
                if (!empty($teks)) {
                    $id = $ids[$index] ?? null;
                    $isActive = $isActives[$index] ?? 1;

                    if ($id) {
                        Ist_Pertanyaan::where('id', $id)->update([
                            'pertanyaan' => $teks,
                            'is_active' => $isActive
                        ]);
                    } else {
                        Ist_Pertanyaan::create([
                            'pertanyaan' => $teks,
                            'is_active' => $isActive
                        ]);
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Manajemen Pertanyaan Kuesioner berhasil diperbarui!');
    }

    // --- FUNGSI SUBMIT KUESIONER PENILAIAN PEGAWAI (TAHAP 1_1) ---
    public function storePenilaian(Request $request)
    {
        $periodeId = $request->input('periode_id');
        
        // Menggunakan NIP dari helper getUserActive
        $nipPemilih = $request->input('nip_pemilih') ?? $this->getUserActive()->nip_pegawai;

        // Menangkap array multi-dimensi dari kuesioner.
        // Bentuknya: ['nip_A' => ['id_tanya1'=>5, 'id_tanya2'=>4], 'nip_B' => [...], ...]
        $dataPenilaian = $request->input('penilaian', []);

        // 1. Validasi Keamanan: Pastikan user benar-benar mengirim 3 orang kandidat
        if (count($dataPenilaian) !== 3) {
            return redirect()->back()->with('error', 'Gagal! Anda wajib memilih dan menilai tepat 3 orang kandidat.');
        }

        // 2. Bersihkan data lama jika user menekan tombol "Ubah Pilihan Saya"
        Ist_PenilaianTahap1::where('periode_id', $periodeId)
            ->where('nip_pemilih', $nipPemilih)
            ->delete();

        // 3. Looping untuk menjumlahkan skor dan menyimpan ke database
        foreach ($dataPenilaian as $nipKandidat => $jawaban) {

            // Hitung total nilai (Misal 20 pertanyaan x nilai 5 = 100 skor total)
            $skorTotal = array_sum($jawaban);

            Ist_PenilaianTahap1::create([
                'periode_id' => $periodeId,
                'nip_pemilih' => $nipPemilih,
                'nip_kandidat' => $nipKandidat,
                'skor_kuesioner' => $skorTotal,
                // detail_jawaban akan otomatis diubah menjadi JSON string karena di Model
                // Ist_PenilaianTahap1 kita sudah menambahkan protected $casts = ['detail_jawaban' => 'array'];
                'detail_jawaban' => $jawaban
            ]);
        }

        return redirect()->back()->with('success', 'Luar biasa! Kuesioner dan pilihan Anda berhasil dikirim.');
    }

    // ====================================================================
    // FUNGSI PEJABAT TETAPKAN KANDIDAT (TAHAP 1.2)
    // ====================================================================
    public function tetapkanKandidat(Request $request)
    {
        $periodeId = $request->input('periode_id');
        $nipKandidat = $request->input('nip_kandidat');

        // Simpan NIP kandidat terpilih ke dalam tabel persiapan Tahap 2
        \App\Models\Ist_KandidatTahap2::updateOrCreate(
            [
                'periode_id' => $periodeId,
                'nip_kandidat' => (string) $nipKandidat
            ]
        );

        return redirect()->back()->with('success', 'Luar biasa! Kandidat berhasil ditetapkan sebagai perwakilan Satker.');
    }

    // ====================================================================
    // FUNGSI PEJABAT BATALKAN KANDIDAT (TAHAP 1.2)
    // ====================================================================
    public function batalkanKandidat(Request $request)
    {
        $periodeId = $request->input('periode_id');
        $nipKandidat = $request->input('nip_kandidat');

        // Hapus penetapan kandidat jika pejabat berubah pikiran
        \App\Models\Ist_KandidatTahap2::where('periode_id', $periodeId)
            ->where('nip_kandidat', (string) $nipKandidat)
            ->delete();

        return redirect()->back()->with('success', 'Penetapan dibatalkan. Tabel klasemen kembali dibuka.');
    }

    // ====================================================================
    // FUNGSI UPLOAD LINK SK PENETAPAN (TAHAP 1.2)
    // ====================================================================
    public function uploadSkKandidat(Request $request)
    {
        $request->validate([
            'periode_id'   => 'required|integer',
            'nip_kandidat' => 'required|string',
            'link_sk'      => 'required|url|max:2000',
        ]);

        \App\Models\Ist_KandidatTahap2::where('periode_id', $request->input('periode_id'))
            ->where('nip_kandidat', $request->input('nip_kandidat'))
            ->update([
                'link_sk'     => $request->input('link_sk'),
                'validasi_sk' => null, // Reset ke null (menunggu) setiap upload/upload ulang
            ]);

        return redirect()->back()->with('success', 'Link SK Penetapan berhasil disimpan. Menunggu validasi panitia.');
    }

    // ====================================================================
    // FUNGSI VALIDASI / REJECT SK PENETAPAN (TAHAP 1.2) - Panitia & Admin
    // ====================================================================
    public function validasiSkKandidat(Request $request)
    {
        $request->validate([
            'periode_id'   => 'required|integer',
            'nip_kandidat' => 'required|string',
            'aksi'         => 'required|in:validasi,tolak,cabut',
        ]);

        $userRole = $this->getUserActive()->ist ?? '';

        if (!in_array($userRole, ['admin', 'panitia'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk melakukan validasi.');
        }

        $aksi = $request->input('aksi');

        // validasi_sk: null = menunggu, 'tervalidasi' = disetujui, 'ditolak' = ditolak
        $nilaiValidasi = match($aksi) {
            'validasi' => 'tervalidasi',
            'tolak'    => 'ditolak',
            'cabut'    => null,      // Cabut validasi → kembali menunggu
            default    => null,
        };

        \App\Models\Ist_KandidatTahap2::where('periode_id', $request->input('periode_id'))
            ->where('nip_kandidat', $request->input('nip_kandidat'))
            ->update(['validasi_sk' => $nilaiValidasi]);

        $pesan = match($aksi) {
            'validasi' => 'SK Penetapan berhasil divalidasi!',
            'tolak'    => 'SK Penetapan ditolak. Kepala BPS satker terkait perlu mengupload ulang.',
            'cabut'    => 'Validasi SK dicabut, status kembali ke menunggu.',
            default    => 'Aksi berhasil.',
        };

        return redirect()->back()->with('success', $pesan);
    }

    // --- METHOD TAHAP 2: MANAJEMEN BERKAS OLEH ADMIN ---
    public function storeBerkasProvinsi(Request $request)
    {
        $request->validate([
            'periode_id' => 'required',
            'nama_dokumen' => 'required',
            'urutan' => 'required|numeric'
        ]);

        \App\Models\Ist_BerkasProvinsi::updateOrCreate(
            ['id' => $request->berkas_id], // Jika berkas_id ada, maka update. Jika null, create baru.
            [
                'periode_id' => $request->periode_id,
                'urutan' => $request->urutan,
                'nama_dokumen' => $request->nama_dokumen,
                'keterangan' => $request->keterangan,
                'link_template' => $request->link_template,
            ]
        );

        return redirect()->back()->with('success', 'Master dokumen berhasil disimpan!');
    }

    public function hapusBerkasProvinsi($id)
    {
        \App\Models\Ist_BerkasProvinsi::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Dokumen berhasil dihapus!');
    }

    // --- METHOD TAHAP 2: SUBMIT URL OLEH KANDIDAT ---
    public function submitBerkasKandidat(Request $request)
    {
        $request->validate([
            'periode_id' => 'required',
            'berkas_id' => 'required',
            'nip_kandidat' => 'required',
            'link_berkas' => 'required|url', // Pastikan format URL
        ]);

        \App\Models\Ist_PengumpulanBerkas::updateOrCreate(
            [
                'periode_id' => $request->periode_id,
                'berkas_id' => $request->berkas_id,
                'nip_kandidat' => $request->nip_kandidat,
            ],
            [
                'link_berkas' => $request->link_berkas,
                'catatan' => $request->catatan,
            ]
        );

        return redirect()->back()->with('success', 'Link Berkas berhasil dikumpulkan!');
    }
}
