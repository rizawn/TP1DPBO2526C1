#include <iostream>
#include <string>
using namespace std;

// Atribut public supaya akses objek tetap sederhana.
class Film {
public:
    string idFilm, judul, genre;
    int durasi;

    Film(string idFilm, string judul, string genre, int durasi) {
        this->idFilm = idFilm;
        this->judul = judul;
        this->genre = genre;
        this->durasi = durasi;
    }

    void tampil() {
        cout << idFilm << " | " << judul << " | " << genre
             << " | " << durasi << " menit\n";
    }
};
