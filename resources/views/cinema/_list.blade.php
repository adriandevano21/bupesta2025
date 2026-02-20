<div class="movie-gallery">
    @foreach ($cinema as $cinema)
    <div class="movie-card" style="position: relative;">
        <a href="/detail/{{ $cinema->id }}" class="movie-link">
            @if ($cinema->lok_flyer == null)
                <img src="{{ asset('assets-cinema/') }}/img/Flyer_belum.png" alt="{{ $cinema->judul_kegiatan }}" />
            @else
                <img src="{{ asset('storage/' . $cinema->lok_flyer) }}" alt="{{ $cinema->judul_kegiatan }}" />
            @endif
            <div class="movie-info">
                <h3>{{ $cinema->judul_kegiatan }}</h3>
                <span class="badge">{{ $cinema->tanggal_kegiatan }}</span>
                @if ($cinema->peserta == "BPS Prov")
                    <span class="badge prov">BPS Prov</span>
                @else
                    <span class="badge prov">BPS Prov</span>
                    <span class="badge kako">BPS Kab Kota</span>
                @endif
                @if ($cinema->rekaman_kegiatan)
                    <span class="badge tersedia">✅ Rekaman Tersedia</span>
                @endif
            </div>
        </a>

        <button type="button" class="btn-modern-delete delete-button" data-id="{{ $cinema->id }}" data-title="{{ $cinema->judul_kegiatan }}">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 6h18M9 6v12m6-12v12M4 6l1 16a2 2 0 002 2h10a2 2 0 002-2l1-16" />
            </svg>
        </button>
    </div>
    @endforeach
</div>