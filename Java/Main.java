import java.util.ArrayList;
import java.util.Scanner;

public class Main {
    static String bacaTeks(Scanner input, String pesan) {
        String teks;
        do {
            System.out.print(pesan);
            teks = input.nextLine().trim();
            if (teks.isEmpty()) System.out.println("Input tidak boleh kosong.");
        } while (teks.isEmpty());
        return teks;
    }

    static int bacaDurasi(Scanner input) {
        while (true) {
            String teks = bacaTeks(input, "Durasi (1-999 menit): ");
            boolean valid = teks.length() <= 3;
            int angka = 0;
            for (int i = 0; i < teks.length() && valid; i++) {
                char huruf = teks.charAt(i);
                if (huruf < '0' || huruf > '9') valid = false;
                else angka = angka * 10 + (huruf - '0');
            }
            if (valid && angka > 0) return angka;
            System.out.println("Durasi harus bilangan bulat 1-999.");
        }
    }

    static int cariIndex(ArrayList<Film> data, String idFilm) {
        for (int i = 0; i < data.size(); i++) {
            if (data.get(i).idFilm.equals(idFilm)) return i;
        }
        return -1;
    }

    public static void main(String[] args) {
        Scanner input = new Scanner(System.in);
        ArrayList<Film> data = new ArrayList<>();
        while (true) {
            System.out.println("\n=== BIOSKOP ===\n1. Tambah\n2. Tampilkan\n3. Update\n4. Hapus\n5. Cari\n0. Keluar");
            String menu = bacaTeks(input, "Pilih: ");
            if (menu.equals("0")) {
                System.out.println("Program selesai.");
                break;
            } else if (menu.equals("1")) {
                String id = bacaTeks(input, "ID film: ");
                if (cariIndex(data, id) != -1) {
                    System.out.println("ID sudah digunakan.");
                } else {
                    String judul = bacaTeks(input, "Judul: ");
                    String genre = bacaTeks(input, "Genre: ");
                    int durasi = bacaDurasi(input);
                    data.add(new Film(id, judul, genre, durasi));
                    System.out.println("Film berhasil ditambahkan.");
                }
            } else if (menu.equals("2")) {
                if (data.isEmpty()) System.out.println("Data masih kosong.");
                else {
                    System.out.println("ID | Judul | Genre | Durasi");
                    for (int i = 0; i < data.size(); i++) data.get(i).tampil();
                }
            } else if (menu.equals("3") || menu.equals("4") || menu.equals("5")) {
                String id = bacaTeks(input, "ID film: ");
                int posisi = cariIndex(data, id);
                if (posisi == -1) System.out.println("Film tidak ditemukan.");
                else if (menu.equals("3")) {
                    String judul = bacaTeks(input, "Judul baru: ");
                    String genre = bacaTeks(input, "Genre baru: ");
                    int durasi = bacaDurasi(input);
                    data.get(posisi).judul = judul;
                    data.get(posisi).genre = genre;
                    data.get(posisi).durasi = durasi;
                    System.out.println("Film berhasil diupdate.");
                } else if (menu.equals("4")) {
                    data.remove(posisi);
                    System.out.println("Film berhasil dihapus.");
                } else data.get(posisi).tampil();
            } else System.out.println("Menu tidak tersedia.");
        }
        input.close();
    }
}