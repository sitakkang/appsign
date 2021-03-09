## Project Core Verison 4

Core versi ke empat dengan base Codeigniter versi 3.1.10 dengan melakukan perombakan pada core standar Codeigniter yang di bangun dengan tujuan mempermudah proses development, maintenance serta colaboration programmer dalam menangani project aplikasi berbasis web.

### Struktur folder Core Level 1 :

1.	**app**         : Tempat penyimpanan source code project
2.	**img**         : Tempat penyimpanan file asset berupa gambar (.jpg .png .gif dll)
3.	**lib**         : Tempat penyimpanan library css, js dll yang berasal dari luar / plugin standalone
4.	**src**         : Tempat penyimpanan library css, js dll yang dibuat sendiri oleh pemakai Core
5.	**sys**         : System core standar Codeigniter
6.  **upload**      : Tempat penyimpanan file yang di import dari aplikasi
7.  **.htaccess**   : File pengaturan yang secara default digunakan untuk mengatur settingan URL pada aplikasi
8. **index.php**    : File utama yang dieksekusi ketika dilakukan proses pemanggilan aplikasi pada browser

### Struktur folder Core Level 2 (app) :

1.	**config**      : Tempat penyimpanan pengaturan library CI dll
2.	**controllers** : Tempat penyimpanan file semua Controllers pada struktur MVC
3.	**libraries**   : Tempat penyimpanan file library php
4.	**logs**        : Tempat penyimpanan file log (default false/0)
5.	**models**      : Tempat penyimpanan file semua Models pada struktur MVC
6.  **views**       : Tempat penyimpanan file semua Views pada struktur MVC

### Aturan penulisan Core v 4 :
Aturan ini dibuat untuk mempermudah dalam proses development, maintenance serta upgrade source code yang dilakukan oleh seluruh anggota tim yang terlibat dalam proses development aplikasi yang memakai Core ini.

1.  Setiap membuat 1 modul, membuat folder didalam *Controllers*, *Models*, *Views*, dan *Libraries* disarankan dengan nama yang sama persis serta penamaan folder harus huruf kecil semua dan menggunakan underscore jika terdiri dari 2 kata. Contoh : **backend**, **frontend**, **mod_random**, **gen_modules**
2.  Setiap membuat 1 file di dalam *Controllers*, *Models*, *Views*, dan *Libraries* disarankan memakai huruf besar pada huruf pertama dan huruf besar pada kata berikutnya. Contoh : **SignUp.php**, **SignIn.php**, **Dashboard.php** dll
3.  Setiap pembuatan file CSS disimpan didalam **src/css/**, file JavaScript disimpan didalam **src/js/**
4.  Semua penamaan variabel ditulis dengan huruf kecil semua dan menggunakan *underscore* untuk kata selanjutnya. contoh **$directory_name**, **$user_name**, **$user_session** dll
5.  Untuk mempermudah pembuatan modul, disarankan menggunakan modul generator yang sudah saya buat khusus untuk core ini di menu *Sidebar -> Configuration -> Generate Modul*

    Ada 2 form di menu Generate Modul

    1.  Generate Main Modul :
        1. app/controllers/**Nama_Folder**/**Nama_File**.php
        2. app/models/**Nama_Folder**/M_**Nama_Folder**.php
        3. app/libraries/**Nama_Folder**/L_**Nama_Folder**.php
        4. app/views/**Nama_Folder**/**Nama_File**/view.php
        5. src/js/**Nama_Folder**/**Nama_File**.js

    2.  Generate Sub Modul :
        1. app/controllers/**Nama_Folder**/**Nama_File**.php
        2. app/models/**Nama_Folder**/M_**Nama_Folder**.php
        3. app/libraries/**Nama_Folder**/L_**Nama_Folder**.php
        4. app/views/**Nama_Folder**/**Nama_File**/view.php
        5. src/js/**Nama_Folder**/**Nama_File**.js


### INSTALLATION NOTES
1.	**Clone Repository**
2.	**Export db_core_v4**



*Author : Ardi Arcadia*
