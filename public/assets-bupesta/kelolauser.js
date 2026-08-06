// FUNGSI MEMBUKA MODAL EDIT
function openEditModal(btn) {
    try {
        // Ambil data-user (Base64 Encode)
        const encodedData = btn.getAttribute("data-user");

        // Decode Base64 ke String JSON, lalu Parsing menjadi Object
        const user = JSON.parse(atob(encodedData));

        // 1. Data Utama
        document.getElementById("edit_nip").value = user.nip_pegawai || "";
        document.getElementById("edit_nip_tampil").value = user.nip_pegawai || "";
        document.getElementById("edit_name").value = user.name || user.nama_simpeg || "";

        // 2. Data Profil Editable (Isian di Bupesta User)
        document.getElementById("edit_tahun").value = user.tahun || "";
        document.getElementById("edit_no_hp").value = user.no_hp || "";
        document.getElementById("edit_satker").value = user.kode_satker || "";
        document.getElementById("edit_golongan").value = user.golongan || "";
        document.getElementById("edit_jabatan").value = user.jabatan || "";
        document.getElementById("edit_urlfoto").value = user.urlfoto || "";

        // 3. Sandingan SIMPEG (Dikirim ke teks hint di bawah input)
        const wilayahAsli = user.satker_simpeg || "Tdk ada data";
        const kodeMap = user.kode_wilayah ? `[Kode: ${user.kode_wilayah}]` : "[Kode: -]";
        document.getElementById("hint_satker").innerText = `${wilayahAsli} ${kodeMap}`;

        document.getElementById("hint_golongan").innerText = user.golongan_simpeg || "Kosong";
        document.getElementById("hint_jabatan").innerText = user.jabatan_simpeg || "Kosong";

        // 4. Data Role (Semuanya berupa text input sekarang)
        document.getElementById("edit_bupesta").value = user.bupesta || "";
        document.getElementById("edit_jazirah").value = user.jazirah || "";
        document.getElementById("edit_cinema").value = user.cinema || "";
        document.getElementById("edit_kinerja").value = user.kinerja || "";
        document.getElementById("edit_ist").value = user.ist || "";

        // Tampilkan Modal
        document.getElementById("modalEditUser").style.display = "flex";
    } catch (e) {
        console.error("Error memparsing data user:", e);
        alert("Gagal membuka modal. Periksa console untuk detail error.");
    }
}

// FUNGSI MENUTUP MODAL EDIT
function closeEditModal() {
    document.getElementById("modalEditUser").style.display = "none";
}

// TUTUP MODAL SAAT KLIK AREA LUAR
window.addEventListener("click", function (e) {
    const modal = document.getElementById("modalEditUser");
    if (e.target === modal) {
        closeEditModal();
    }
});
