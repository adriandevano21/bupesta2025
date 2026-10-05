import streamlit as st
import pandas as pd
import os
import psutil
import gc
from datetime import datetime
import socket

# Paksa pembersihan memori sisa dari proses sebelumnya setiap kali halaman direfresh/dijalankan
gc.collect()

st.set_page_config(page_title="Pencarian DTSEN 2026 (Lite)", layout="wide")

folder_path = r"D:\ADRIAN\Python\PROJECT\DTSEN\Parquet"
file_v3_a = os.path.join(folder_path, "2026_v3_anggota_keluarga.parquet")
file_v3_k = os.path.join(folder_path, "2026_v3_keluarga.parquet")
file_v4_a = os.path.join(folder_path, "2026_v4_anggota_keluarga.parquet")
file_v4_k = os.path.join(folder_path, "2026_v4_keluarga.parquet")

def get_memory_usage():
    process = psutil.Process(os.getpid())
    mem_mb = process.memory_info().rss / (1024 ** 2)
    sys_mem = psutil.virtual_memory()
    return mem_mb, sys_mem.percent, sys_mem.available / (1024 ** 3)

# Fungsi untuk mencatat aktivitas ke file txt
def catat_log_aktivitas(nikk, status):
    log_file = os.path.join(folder_path, "log_aktivitas.txt")
    waktu_sekarang = datetime.now().strftime("%Y-%m-%d %H:%M:%S")

    # Mencoba mendeteksi IP Pengakses
    try:
        # Pendekatan standar mendapatkan IP Host/Klien di jaringan
        hostname = socket.gethostname()
        ip_address = socket.gethostbyname(hostname)
    except:
        ip_address = "IP_Tidak_Terdeteksi"

    teks_log = f"[{waktu_sekarang}] IP Akses: {ip_address} | NIK/NKK Dicari: {nikk} | Status: {status}\n"

    # Append log ke file txt
    with open(log_file, "a", encoding="utf-8") as file_log:
        file_log.write(teks_log)

mem_mb, sys_percent, sys_avail = get_memory_usage()
st.sidebar.title("💻 Status Sistem")
st.sidebar.info(f"**RAM Aplikasi:** {mem_mb:.2f} MB")
st.sidebar.info(f"**Sisa RAM Komputer:** {sys_avail:.2f} GB ({100 - sys_percent:.1f}% Free)")
st.sidebar.markdown("---")

def compare_row(df_v4, df_v3, nama_kolom="Variabel"):
    s_v4 = df_v4.iloc[0].to_frame(name="Versi 4 (2026)") if not df_v4.empty else pd.DataFrame(columns=["Versi 4 (2026)"])
    s_v3 = df_v3.iloc[0].to_frame(name="Versi 3 (2026)") if not df_v3.empty else pd.DataFrame(columns=["Versi 3 (2026)"])

    comp = pd.merge(s_v4, s_v3, left_index=True, right_index=True, how='outer')

    cols_v4 = list(df_v4.columns) if not df_v4.empty else []
    cols_v3 = list(df_v3.columns) if not df_v3.empty else []
    ordered_cols = cols_v4 + [c for c in cols_v3 if c not in cols_v4]

    comp = comp.reindex(ordered_cols)
    comp = comp.fillna("-")

    def cek_status(row):
        if row["Versi 4 (2026)"] == "-" or row["Versi 3 (2026)"] == "-":
            return "Kolom Baru/Hilang"
        elif str(row["Versi 4 (2026)"]) == str(row["Versi 3 (2026)"]):
            return "Sama"
        else:
            return "Berbeda"

    comp["Status Perubahan"] = comp.apply(cek_status, axis=1)
    comp.index.name = nama_kolom
    return comp

st.title("🔍 Pencarian Cepat Data DTSEN 2026")
st.markdown("Cari berdasarkan **NIK** atau **Nomor KK**.")

search_query = st.text_input("Masukkan NIK atau Nomor Kartu Keluarga (NKK)")
btn_cari = st.button("Cari Data", type="primary")

if btn_cari and search_query:
    search_query = str(search_query).strip()

    with st.spinner("Mencari data dan mengelola memori..."):
        try:
            match_v4 = pd.read_parquet(file_v4_a, columns=['nomor_induk_kependudukan', 'nomor_kartu_keluarga'], filters=[('nomor_induk_kependudukan', '==', search_query)])
            if match_v4.empty:
                match_v4 = pd.read_parquet(file_v4_a, columns=['nomor_induk_kependudukan', 'nomor_kartu_keluarga'], filters=[('nomor_kartu_keluarga', '==', search_query)])
        except Exception as e:
            st.error(f"Terjadi kesalahan saat membaca Parquet: {e}")
            match_v4 = pd.DataFrame()

        if match_v4.empty:
            catat_log_aktivitas(search_query, "TIDAK DITEMUKAN")
            st.error(f"Data dengan NIK/NKK {search_query} tidak ditemukan.")
        else:
            nkk_target = str(match_v4['nomor_kartu_keluarga'].iloc[0])
            catat_log_aktivitas(search_query, f"DITEMUKAN (Tarik NKK: {nkk_target})")
            st.success(f"Ditemukan! Menarik data spesifik untuk Nomor KK: **{nkk_target}**")

            target_k_v4 = pd.read_parquet(file_v4_k, filters=[('nomor_kartu_keluarga', '==', nkk_target)])
            target_k_v3 = pd.read_parquet(file_v3_k, filters=[('nomor_kartu_keluarga', '==', nkk_target)])
            target_a_v4 = pd.read_parquet(file_v4_a, filters=[('nomor_kartu_keluarga', '==', nkk_target)])
            target_a_v3 = pd.read_parquet(file_v3_a, filters=[('nomor_kartu_keluarga', '==', nkk_target)])

            for df in [target_k_v4, target_k_v3, target_a_v4, target_a_v3]:
                for col in ['nomor_induk_kependudukan', 'nomor_kartu_keluarga']:
                    if col in df.columns:
                        df[col] = df[col].astype(str).str.replace(".0", "", regex=False)

            # --- 1. TAMPILAN KELUARGA ---
            st.header("1. Data Keluarga")
            df_comp_keluarga = compare_row(target_k_v4, target_k_v3, nama_kolom="Variabel Keluarga")
            st.dataframe(
                df_comp_keluarga.style.map(
                    lambda x: 'background-color: #ffcccc; color: black' if x == 'Berbeda' else ('background-color: #e6f7ff; color: black' if x == 'Kolom Baru/Hilang' else ''), 
                    subset=['Status Perubahan']
                ),
                use_container_width=True, height=400
            )

            # --- 2. TAMPILAN ANGGOTA ---
            st.header("2. Data Anggota Keluarga")
            semua_nik = set(target_a_v4['nomor_induk_kependudukan'].tolist() + target_a_v3['nomor_induk_kependudukan'].tolist())
            semua_nik.discard("")

            for i, nik in enumerate(semua_nik, 1):
                nama_v4 = target_a_v4[target_a_v4['nomor_induk_kependudukan'] == nik]['nama']
                nama_v3 = target_a_v3[target_a_v3['nomor_induk_kependudukan'] == nik]['nama']

                nama_tampil = nama_v4.iloc[0] if not nama_v4.empty else (nama_v3.iloc[0] if not nama_v3.empty else "Nama Tidak Diketahui")

                with st.expander(f"Anggota {i}: {nama_tampil} (NIK: {nik})", expanded=(search_query==nik)):
                    a_v4 = target_a_v4[target_a_v4['nomor_induk_kependudukan'] == nik]
                    a_v3 = target_a_v3[target_a_v3['nomor_induk_kependudukan'] == nik]

                    df_comp_anggota = compare_row(a_v4, a_v3, nama_kolom="Variabel Anggota")
                    st.dataframe(
                        df_comp_anggota.style.map(
                            lambda x: 'background-color: #ffcccc; color: black' if x == 'Berbeda' else ('background-color: #e6f7ff; color: black' if x == 'Kolom Baru/Hilang' else ''), 
                            subset=['Status Perubahan']
                        ),
                        use_container_width=True, height=400
                    )

            # --- 3. EKSEKUSI PEMBERSIHAN MEMORI (RESET RAM) ---
            try:
                del target_k_v4, target_k_v3, target_a_v4, target_a_v3
                del df_comp_keluarga, match_v4, a_v4, a_v3, df_comp_anggota
            except NameError:
                pass

            gc.collect()
