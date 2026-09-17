#include <iostream>
#include <string>
#include <vector>
#include "Film.cpp"
using namespace std;

string bacaTeks(string pesan) {
    string teks;
    do {
        cout << pesan;
        getline(cin, teks);
        // Rapikan spasi di awal dan akhir input.
        size_t awal = teks.find_first_not_of(" \t\r");
        if (awal == string::npos) teks = "";
        else teks = teks.substr(awal, teks.find_last_not_of(" \t\r") - awal + 1);
        if (teks.empty()) cout << "Input tidak boleh kosong.\n";
    } while (teks.empty());
    return teks;
}

int bacaDurasi() {
    while (true) {
        string teks = bacaTeks("Durasi (1-999 menit): ");
        bool valid = teks.length() <= 3;
        int angka = 0;
        for (int i = 0; i < (int)teks.length() && valid; i++) {
            if (teks[i] < '0' || teks[i] > '9') valid = false;
            else angka = angka * 10 + (teks[i] - '0');
        }
        if (valid && angka > 0) return angka;
        cout << "Durasi harus bilangan bulat 1-999.\n";
    }
}

int cariIndex(vector<Film>& data, string idFilm) {
    for (int i = 0; i < (int)data.size(); i++) {
        if (data[i].idFilm == idFilm) return i;
    }
    return -1;
}

int main() {
    vector<Film> data;
    while (true) {
        cout << "\n=== BIOSKOP ===\n1. Tambah\n2. Tampilkan\n3. Update\n4. Hapus\n5. Cari\n0. Keluar\n";
        string menu = bacaTeks("Pilih: ");
        if (menu == "0") {
            cout << "Program selesai.\n";
            break;
        } else if (menu == "1") {
            string id = bacaTeks("ID film: ");
            if (cariIndex(data, id) != -1) {
                cout << "ID sudah digunakan.\n";
            } else {
                string judul = bacaTeks("Judul: ");
                string genre = bacaTeks("Genre: ");
                int durasi = bacaDurasi();
                data.push_back(Film(id, judul, genre, durasi));
                cout << "Film berhasil ditambahkan.\n";
            }
        } else if (menu == "2") {
            if (data.empty()) cout << "Data masih kosong.\n";
            else {
                cout << "ID | Judul | Genre | Durasi\n";
                for (int i = 0; i < (int)data.size(); i++) data[i].tampil();
            }
        } else if (menu == "3" || menu == "4" || menu == "5") {
            string id = bacaTeks("ID film: ");
            int posisi = cariIndex(data, id);
            if (posisi == -1) cout << "Film tidak ditemukan.\n";
            else if (menu == "3") {
                string judul = bacaTeks("Judul baru: ");
                string genre = bacaTeks("Genre baru: ");
                int durasi = bacaDurasi();
                data[posisi].judul = judul;
                data[posisi].genre = genre;
                data[posisi].durasi = durasi;
                cout << "Film berhasil diupdate.\n";
            } else if (menu == "4") {
                data.erase(data.begin() + posisi);
                cout << "Film berhasil dihapus.\n";
            } else data[posisi].tampil();
        } else cout << "Menu tidak tersedia.\n";
    }
    return 0;
}
