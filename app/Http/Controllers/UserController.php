<?php

namespace App\Http\Controllers;

use App\Models\Bupesta_User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(Request $request)
    {
        // dd($request->nip_pegawai);
        Bupesta_User::where('nip_pegawai', $request->nip_pegawai)->update([
            'no_hp'       => $request->no_hp
        ]);

        // 4. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Terima kasih, profil Anda telah lengkap!');
    }
}
