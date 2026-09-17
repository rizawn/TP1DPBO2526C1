class Film:
    # Constructor: mengisi data saat objek Film dibuat.
    def __init__(self, id_film, judul, genre, durasi):
        self.__id_film = id_film
        self.__judul = judul
        self.__genre = genre
        self.__durasi = durasi

    # Getter: membaca atribut objek.
    def get_id(self):
        return self.__id_film

    def get_judul(self):
        return self.__judul

    def get_genre(self):
        return self.__genre

    def get_durasi(self):
        return self.__durasi

    # Setter: mengubah atribut. ID tetap setelah objek dibuat.
    def set_judul(self, judul):
        self.__judul = judul

    def set_genre(self, genre):
        self.__genre = genre

    def set_durasi(self, durasi):
        if 1 <= durasi <= 999:
            self.__durasi = durasi

    def tampil(self):
        print(f"{self.get_id()} | {self.get_judul()} | "
              f"{self.get_genre()} | {self.get_durasi()} menit")
