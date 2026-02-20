<?php

namespace App\Http\Controllers;

use App\Models\Jazirah_Indikator;
use App\Models\NarahubungJazirah;
use App\Models\UserActivity;
use Illuminate\Http\Request;

class JazirahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data["judul"] = "Jazirah";
        $data["indikator"] = Jazirah_Indikator::all();
        return view('jazirah.jazirah', compact('data'));
    }

    public function dashboard()
    {
        UserActivity::log("https://bupesta.web.bps.go.id/dashboard-jazirah");
        $data["judul"] = "Dashboard Jazirah";
        return view('jazirah.dashboard-jazirah', compact('data'));
    }

    public function input()
    {
        UserActivity::log("https://bupesta.web.bps.go.id/form-jazirah");
        $data["judul"] = "Form Jazirah";
        return view('jazirah.input-jazirah', compact('data'));
    }

    public function qna()
    {
        UserActivity::log("https://bupesta.web.bps.go.id/qna-jazirah");
        $data["judul"] = "QNA Jazirah";
        return view('jazirah.qna-jazirah', compact('data'));
    }

    public function narahubung()
    {
        $data["judul"] = "Jazirah";
        $data["narahubung"] = NarahubungJazirah::all();
        // dd($data);
        return view('jazirah.narahubung-jazirah', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
