<div id="loginTableContainer">
    @include('aktivitasuser.partials.tabel_login', ['data' => $data])
</div>

<div id="rekapTableContainer">
    @include('aktivitasuser.partials.rekap_aktivitas', ['data' => $data])
</div>