<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CinemaController extends Controller
{
    public function cinema()
    {
        UserActivity::log("https://bupesta.web.bps.go.id/cinema");
        $data = [];
        $data["judul"] = "Cinema";
        $data["cinema"] = Cinema::orderBy('tanggal_kegiatan', 'desc')->get();
        return view('cinema.cinema', compact('data'));
    }

    public function search(Request $request)
    {
        $query = $request->q;

        $cinema = Cinema::when($query, function ($q) use ($query) {
            $q->where('judul_kegiatan', 'like', '%' . $query . '%')
                ->orWhere('tanggal_kegiatan', 'like', '%' . $query . '%');
        })->get();

        return view('cinema._list', compact('cinema'))->render();
    }

    public function detail($id)
    {
        UserActivity::log("https://bupesta.web.bps.go.id/detail/" . $id);
        $data = [];
        $data["judul"] = "Detail";
        $data["cinema"] = Cinema::findOrFail($id); // Ambil satu data sesuai ID
        return view('cinema.detail', compact('data'));
    }

    public function TambahData(Request $request)
    {
        // Simpan file jika ada
        $flyerPath = null;
        $backdropPath = null;
        $flyernamafile = null;
        $backdropnamafile = null;

        if ($request->hasFile('flyer-upload')) {
            $flyerPath = $request->file('flyer-upload')->store('uploads/flyer', 'public');
            $flyernamafile = $request->file('flyer-upload')->getClientOriginalName();
        }

        if ($request->hasFile('backdrop-upload')) {
            $backdropPath = $request->file('backdrop-upload')->store('uploads/backdrop', 'public');
            $backdropnamafile = $request->file('backdrop-upload')->getClientOriginalName();
        }

        // Simpan ke database
        $cinema = Cinema::create([
            'judul_kegiatan'     => $request->judul_kegiatan,
            'tanggal_kegiatan'   => $request->tanggal_kegiatan,
            'pukul'              => $request->pukul,
            'peserta'            => $request->peserta,
            'tempat_kegiatan'    => $request->tempat_kegiatan,
            'zoom_kegiatan'      => $request->zoom_kegiatan ?? null,
            'pemateri'           => $request->pemateri,
            'lok_flyer'          => $flyerPath,
            'name_flyer'         => $flyernamafile,
            'lok_backdrop'       => $backdropPath,
            'name_backdrop'      => $backdropnamafile,
            'created_by'         => $request->created_by ?? 'adrian.devano',
        ]);

        return redirect()->back()->with('success', 'Data kegiatan berhasil disimpan.');
    }

    public function EditData($id, Request $request)
    {
        $data["cinema"] = Cinema::findOrFail($id); // Ambil satu data sesuai ID
        // dd($request);
        // Simpan file jika ada
        $flyerPath = $data["cinema"]->lok_flyer;
        $backdropPath = $data["cinema"]->lok_backdrop;
        $flyernamafile = $data["cinema"]->name_flyer;
        $backdropnamafile = $data["cinema"]->name_backdrop;

        if ($request->hasFile('flyer-upload')) {
            $flyerPath = storage_path('app/public/' . $data["cinema"]->lok_flyer);
            if (is_file($flyerPath)) {
                unlink($flyerPath);
            }
            $flyerPath = $request->file('flyer-upload')->store('uploads/flyer', 'public');
            $flyernamafile = $request->file('flyer-upload')->getClientOriginalName();
        }

        if ($request->hasFile('backdrop-upload')) {
            $backdropPath = storage_path('app/public/' . $data["cinema"]->lok_backdrop);
            if (is_file($backdropPath)) {
                unlink($backdropPath);
            }
            $backdropPath = $request->file('backdrop-upload')->store('uploads/backdrop', 'public');
            $backdropnamafile = $request->file('backdrop-upload')->getClientOriginalName();
        }

        // Update ke database
        Cinema::where('id', $id)
            ->update([
                'judul_kegiatan'     => $request->judul_kegiatan,
                'tanggal_kegiatan'   => $request->tanggal_kegiatan,
                'pukul'              => $request->pukul,
                'peserta'            => $request->peserta,
                'tempat_kegiatan'    => $request->tempat_kegiatan,
                'zoom_kegiatan'      => $request->zoom_kegiatan ?? null,
                'presensi_kegiatan'  => $request->presensi_kegiatan ?? null,
                'rekaman_kegiatan'   => $request->rekaman_kegiatan ?? null,
                'materi_kegiatan'    => $request->materi_kegiatan ?? null,
                'sertifikat_kegiatan' => $request->sertifikat_kegiatan ?? null,
                'pemateri'           => $request->pemateri,
                'lok_flyer'          => $flyerPath,
                'name_flyer'         => $flyernamafile,
                'lok_backdrop'       => $backdropPath,
                'name_backdrop'      => $backdropnamafile,
                'created_by'         => $request->created_by ?? 'adrian.devano',
            ]);

        return redirect()->back()->with('success', 'Data kegiatan berhasil diupdate.');
    }

    public function HapusData($id)
    {
        $cinema = Cinema::findOrFail($id);

        if ($cinema->lok_flyer && file_exists(storage_path("app/public/{$cinema->lok_flyer}"))) {
            unlink(storage_path("app/public/{$cinema->lok_flyer}"));
        }

        if ($cinema->lok_backdrop && file_exists(storage_path("app/public/{$cinema->lok_backdrop}"))) {
            unlink(storage_path("app/public/{$cinema->lok_backdrop}"));
        }

        $cinema->delete();

        return redirect()->route('cinema.cinema')->with('success', 'Data berhasil dihapus.');
    }
}
