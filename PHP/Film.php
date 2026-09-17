<?php
class Film {
    private $idFilm;
    private $judul;
    private $genre;
    private $durasi;
    private $gambar;

    public function __construct($idFilm, $judul, $genre, $durasi, $gambar) {
        $this->idFilm = $idFilm;
        $this->judul = $judul;
        $this->genre = $genre;
        $this->durasi = $durasi;
        $this->gambar = $gambar;
    }

    public function getId() { return $this->idFilm; }
    public function getJudul() { return $this->judul; }
    public function getGenre() { return $this->genre; }
    public function getDurasi() { return $this->durasi; }
    public function getGambar() { return $this->gambar; }

    // ID tetap setelah objek dibuat, sama seperti versi CLI.
    public function setJudul($judul) { $this->judul = $judul; }
    public function setGenre($genre) { $this->genre = $genre; }
    public function setDurasi($durasi) {
        if ($durasi >= 1 && $durasi <= 999) {
            $this->durasi = $durasi;
        }
    }
    public function setGambar($gambar) { $this->gambar = $gambar; }
}
