import pandas as pd
import os
import time

print("⏳ [Tahap 1/4] Membaca data CSV mentah...")
start_time = time.time()

# --- 1. KONFIGURASI PATH (Semua menjadi v3_2026) ---
path_anggota_v3_2026 = (
    r"D:\Adrian\2026\jupyter\DTSEN\2026_v3_DTSEN\anggota_keluarga_dtsen_v3_2026_ALL.csv"
)
path_keluarga_v3_2026 = (
    r"D:\Adrian\2026\jupyter\DTSEN\2026_v3_DTSEN\keluarga_dtsen_v3_2026_ALL.csv"
)

path_excel_keluarga_v3_2026 = (
    r"D:\Adrian\2026\jupyter\DTSEN\Kode_DTSEN_keluarga_v3_2026.xlsx"
)
path_excel_anggota_v3_2026 = (
    r"D:\Adrian\2026\jupyter\DTSEN\Kode_DTSEN_anggota_v3_2026.xlsx"
)

path_simpan_anggota_v3_2026 = (
    r"D:\Adrian\2026\jupyter\DTSEN\2026_v3_DTSEN\anggota_v3_clean.parquet"
)
path_simpan_keluarga_v3_2026 = (
    r"D:\Adrian\2026\jupyter\DTSEN\2026_v3_DTSEN\keluarga_v3_clean.parquet"
)


# --- FUNGSI BACA PINTAR ---
def read_csv_smart(file_path):
    try:
        # Coba baca dengan pemisah koma (default)
        df = pd.read_csv(file_path, low_memory=False, engine="pyarrow")
    except Exception:
        df = pd.read_csv(file_path, low_memory=False)

    # JURUS KUNCI: Bersihkan semua nama kolom dari spasi dan huruf besar
    df.columns = df.columns.str.strip().str.lower()

    # Jika ternyata kolom masih menyatu (jumlah kolom hanya 1), baru pakai pemisah '|'
    if len(df.columns) == 1:
        df = pd.read_csv(file_path, sep="|", low_memory=False)
        df.columns = df.columns.str.strip().str.lower()

    return df


# Membaca ke dalam variabel v3_2026
df_anggota_v3_2026 = read_csv_smart(path_anggota_v3_2026)
df_keluarga_v3_2026 = read_csv_smart(path_keluarga_v3_2026)

print("✅ Tahap 1 Selesai!")
print(
    "Cek 5 kolom pertama df_keluarga_v3_2026:", df_keluarga_v3_2026.columns.tolist()[:5]
)

# --- 3. FUNGSI MAPPING SPESIFIK ---
print("\n⏳ [Tahap 2/4] Melakukan Mapping Keterangan Excel...")


def map_dataframe_smart(df, excel_path):
    if df.empty or not os.path.exists(excel_path):
        return df

    # File excel di-load murni sebagai string agar kode aman
    df_map = pd.read_excel(excel_path, dtype=str)

    if {"kolom", "kode", "keterangan"}.issubset(df_map.columns):
        for nama_kolom, group in df_map.groupby("kolom"):
            if nama_kolom in df.columns:
                group["kode"] = group["kode"].str.replace(r"\.0$", "", regex=True)
                mapping_dict = dict(zip(group["kode"], group["keterangan"]))

                # HANYA kolom yang di-mapping saja yang disesuaikan
                kolom_asli_str = (
                    df[nama_kolom].astype(str).str.replace(r"\.0$", "", regex=True)
                )
                df[nama_kolom] = kolom_asli_str.map(mapping_dict).fillna(df[nama_kolom])
    return df


df_keluarga_v3_2026 = map_dataframe_smart(
    df_keluarga_v3_2026, path_excel_keluarga_v3_2026
)
df_anggota_v3_2026 = map_dataframe_smart(df_anggota_v3_2026, path_excel_anggota_v3_2026)

# --- 4. CLEANING IDENTITAS & SET INDEX ---
print("⏳ [Tahap 3/4] Membersihkan NIK dan Set Index...")

# Membersihkan format .0 dan spasi pada NIK/KK Anggota
for col in ["nomor_induk_kependudukan", "nomor_kartu_keluarga"]:
    if col in df_anggota_v3_2026.columns:
        df_anggota_v3_2026[col] = (
            df_anggota_v3_2026[col]
            .astype(str)
            .str.replace(r"\.0$", "", regex=True)
            .str.strip()
        )

# Membersihkan format .0 dan spasi pada KK Keluarga (jika ada)
if "nomor_kartu_keluarga" in df_keluarga_v3_2026.columns:
    df_keluarga_v3_2026["nomor_kartu_keluarga"] = (
        df_keluarga_v3_2026["nomor_kartu_keluarga"]
        .astype(str)
        .str.replace(r"\.0$", "", regex=True)
        .str.strip()
    )

# Perbaikan Index: Gunakan pengecekan yang lebih ketat sebelum set_index
if "nomor_kartu_keluarga" in df_keluarga_v3_2026.columns:
    df_keluarga_v3_2026.set_index("nomor_kartu_keluarga", drop=False, inplace=True)
elif df_keluarga_v3_2026.index.name != "nomor_kartu_keluarga":
    print(
        "⚠️ Peringatan: Kolom 'nomor_kartu_keluarga' tidak ditemukan di df_keluarga_v3_2026."
    )

if "nomor_kartu_keluarga" in df_anggota_v3_2026.columns:
    df_anggota_v3_2026.set_index("nomor_kartu_keluarga", drop=False, inplace=True)
elif df_anggota_v3_2026.index.name != "nomor_kartu_keluarga":
    print(
        "⚠️ Peringatan: Kolom 'nomor_kartu_keluarga' tidak ditemukan di df_anggota_v3_2026."
    )

# --- 5. PERBAIKAN PYARROW TANPA MERUSAK ANGKA ---
print("⏳ [Tahap 3.5/4] Mengamankan kolom teks agar PyArrow tidak Error...")

# Kita HANYA mengubah tipe data "object" (campuran/teks) menjadi string.
for col in df_anggota_v3_2026.select_dtypes(include=["object"]).columns:
    df_anggota_v3_2026[col] = df_anggota_v3_2026[col].astype(str)

for col in df_keluarga_v3_2026.select_dtypes(include=["object"]).columns:
    df_keluarga_v3_2026[col] = df_keluarga_v3_2026[col].astype(str)

# --- 6. SIMPAN PARQUET ---
print("⏳ [Tahap 4/4] Menyimpan menjadi format PARQUET Super Cepat...")

df_anggota_v3_2026.to_parquet(path_simpan_anggota_v3_2026, engine="pyarrow")
df_keluarga_v3_2026.to_parquet(path_simpan_keluarga_v3_2026, engine="pyarrow")

durasi = (time.time() - start_time) / 60
print(f"✅ SELESAI! Data berhasil diconvert dalam {durasi:.2f} menit.\n")

# =====================================================================
# --- 7. MEMBUAT & MENYIMPAN DATA GABUNGAN (MERGE) ---
# =====================================================================
print("\n⏳ [Tahap Tambahan] Membuat & Menyimpan df_gabungan_v3_2026...")
start_gabung = time.time()

# 1. Tentukan list kolom spesifik yang ingin diambil
kolom_anggota = [
    "nomor_induk_kependudukan",
    "nomor_kartu_keluarga",
    "nama",
    "tanggal_lahir",
    "jenis_kelamin",
    "status_hubungan_keluarga",
    "status_kawin",
    "partisipasi_sekolah",
    "jenjang_tertinggi_yang_diduduki",
    "kelas_tertinggi_yang_diduduki",
    "ijazah_tertinggi_yang_dimiliki",
    "status_bekerja",
    "lapangan_usaha_dari_pekerjaan_utama",
    "status_dalam_pekerjaan_utama",
    "kode_provinsi_ktp",
    "provinsi_ktp",
    "kode_kabupaten_kota_ktp",
    "kabupaten_kota_ktp",
    "kode_kecamatan_ktp",
    "kecamatan_ktp",
    "kode_kelurahan_desa_ktp",
    "kelurahan_desa_ktp",
    "rt_ktp",
    "rw_ktp",
    "dusun_ktp",
    "alamat_ktp",
    "pekerjaan_ktp",
    "pendidikan_akhir_ktp",
]

kolom_keluarga = [
    "kode_provinsi",
    "provinsi",
    "kode_kabupaten_kota",
    "kabupaten_kota",
    "kode_kecamatan",
    "kecamatan",
    "kode_kelurahan_desa",
    "kelurahan_desa",
    "alamat",
    "nomor_kartu_keluarga",
    "jumlah_anggota_keluarga",
    "nama_kepala_keluarga",
    "desil_nasional",
    "desil_provinsi",
    "desil_kabupaten_kota",
]

# 2. Filter kolom agar terhindar dari error jika ada typo nama kolom
kol_ang_valid = [kol for kol in kolom_anggota if kol in df_anggota_v3_2026.columns]
kol_kel_valid = [kol for kol in kolom_keluarga if kol in df_keluarga_v3_2026.columns]

# 3. Lakukan proses Penggabungan (Merge) berdasarkan Nomor KK
# PERBAIKAN: Gunakan .reset_index(drop=True) untuk mengatasi Ambiguous Index Error
df_gabungan_v3_2026 = pd.merge(
    df_anggota_v3_2026[kol_ang_valid].reset_index(drop=True),
    df_keluarga_v3_2026[kol_kel_valid].reset_index(drop=True),
    on="nomor_kartu_keluarga",
    how="left",
)

# 4. Simpan ke dalam format Parquet
path_simpan_gabungan_v3_2026 = (
    r"D:\Adrian\2026\jupyter\DTSEN\2026_v3_DTSEN\gabungan_v3_clean.parquet"
)

df_gabungan_v3_2026.to_parquet(path_simpan_gabungan_v3_2026, engine="pyarrow")

durasi_gabung = (time.time() - start_gabung) / 60
print(
    f"✅ SELESAI! Data gabungan berhasil dibuat dan disimpan dalam {durasi_gabung:.2f} menit."
)
print(f"   ➤ Total Baris Gabungan: {len(df_gabungan_v3_2026):,} baris")
print(f"   ➤ Total Kolom Gabungan: {len(df_gabungan_v3_2026.columns)} kolom")
