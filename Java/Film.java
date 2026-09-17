public class Film {
    public String idFilm, judul, genre;
    public int durasi;

    public Film(String idFilm, String judul, String genre, int durasi) {
        this.idFilm = idFilm;
        this.judul = judul;
        this.genre = genre;
        this.durasi = durasi;
    }

    public void tampil() {
        System.out.println(idFilm + " | " + judul + " | " + genre + " | " + durasi + " menit");
    }
}
