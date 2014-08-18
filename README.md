lkpp-lpse-grab
==============

Mengambil data pemenang lelang dari aplikasi LPSE dari LKPP.

## Kebutuhan
* Web server (aplikasi sudah dites menggunakan Apache).
* PHP dengan ekstensi curl aktif.

## Instalasi
* Extrak lkpp-lpse-grab-master.zip ke Direktori root, misalkan C:\www\lpse.
* Periksa file init.php dan ubah konfigurasi seperlunya.
* Buat Virtual Host

Khusus untuk pengguna XAMPP/Windows
* Buka file `c:\WINDOWS\system32\drivers\etc\hosts` dan tambahkan kode berikut di baris paling bawah.
```127.0.0.127 lpse```

* Buka file `c:\xamp\apache\conf\extra\httpd-vhosts.conf` dan tambahkan kode berikut di baris paling bawah.
```
<VirtualHost *>
    DocumentRoot "C:/xampp/htdocs/"
    ServerName localhost
</VirtualHost>

<VirtualHost *:80>
    DocumentRoot C:/xampp/htdocs/lpse/
    ServerName lpse
</VirtualHost>
```
* Buka Control Panel XAMPP dan restart Apache (Stop lalu Start)

## Menjalankan

Menggunakan browser, browse ke http://lpse/

## Catatan
Aplikasi ini memiliki lisensi bebas yang berarti:
* Bebas digunakan, diubah, dijual, dihapus, dll. Dengan atau tanpa izin pembuat aplikasi.
* Bebas juga berarti, pembuat aplikasi bebas dari segala resiko jika penggunaan aplikasi ini menimbulkan masalah dengan pihak lain (resiko ditanggung pengguna).
* Untuk menghindari masalah, sebaiknya sebelum mulai mengambil konten dari situs target, terlebih dahulu meminta izin kepada pemilik situs ybs.
