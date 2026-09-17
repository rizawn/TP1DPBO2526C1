from Film import Film

def baca_teks(pesan):
    teks = input(pesan).strip()
    while teks == "":
        print("Input tidak boleh kosong.")
        teks = input(pesan).strip()
    return teks

def baca_durasi():
    teks = input("Durasi (1-999 menit): ").strip()
    # Cek karakter sebelum konversi agar tidak perlu try-except.
    while not (1 <= len(teks) <= 3 and teks.isascii()
               and teks.isdigit() and int(teks) > 0):
        print("Durasi harus bilangan bulat 1-999.")
        teks = input("Durasi (1-999 menit): ").strip()
    return int(teks)

def cari_index(data, id_film):
    for i in range(len(data)):
        if data[i].get_id() == id_film:
            return i
    return -1

def main():
    data = []  # List ini berisi objek Film, bukan dictionary.
    while True:
        print("\n=== BIOSKOP ===\n1. Tambah\n2. Tampilkan\n3. Update\n4. Hapus\n5. Cari\n0. Keluar")
        menu = input("Pilih: ").strip()
        if menu == "0":
            print("Program selesai.")
            break
        elif menu == "1":
            id_film = baca_teks("ID film: ")
            if cari_index(data, id_film) != -1:
                print("ID sudah digunakan.")
            else:
                judul = baca_teks("Judul: ")
                genre = baca_teks("Genre: ")
                durasi = baca_durasi()
                data.append(Film(id_film, judul, genre, durasi))
                print("Film berhasil ditambahkan.")
        elif menu == "2":
            if len(data) == 0:
                print("Data masih kosong.")
            else:
                print("ID | Judul | Genre | Durasi")
                for film in data:
                    film.tampil()
        elif menu == "3" or menu == "4" or menu == "5":
            id_film = baca_teks("ID film: ")
            posisi = cari_index(data, id_film)
            if posisi == -1:
                print("Film tidak ditemukan.")
            elif menu == "3":
                # ID tetap, tiga atribut lainnya diganti.
                judul = baca_teks("Judul baru: ")
                genre = baca_teks("Genre baru: ")
                durasi = baca_durasi()
                data[posisi].set_judul(judul)
                data[posisi].set_genre(genre)
                data[posisi].set_durasi(durasi)
                print("Film berhasil diupdate.")
            elif menu == "4":
                del data[posisi]
                print("Film berhasil dihapus.")
            else:
                data[posisi].tampil()
        else:
            print("Menu tidak tersedia.")

if __name__ == "__main__":
    main()
