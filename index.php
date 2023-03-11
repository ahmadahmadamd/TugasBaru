<?php

// koneksi databse
$server = "localhost";
$user = "root";
$password = "";
$database = "dbcrud2022";

// buat koneksi 
 $koneksi = mysqli_connect($server, $user, $password, $database) or die(mysqli_error($koneksi));

// kode otomatis
$q = mysqli_query($koneksi, "SELECT kode FROM tbarng order by kode desc limit 1");
$datax = mysqli_fetch_array($q);

if ($datax) {
    $no_terakhir = substr($datax['kode'], -3);
    $no = $no_terakhir + 1;

    if ($no > 0 and $no < 10) {
        $kode = "00" . $no;

    } else if ($no > 10 and $no < 100) {
        $kode = "0" . $no;

    } else if ($no > 100) {
        $kode = $no;
    }
}else {
    $kode = "001";
}
$tahun = date('Y');
$vkode = "IVN-" . $tahun . '-' . $kode;
//INV-2022-001


// jika tombol simpan diklik
if(isset($_POST['bsimpan'])){

    // pengujian apakah data akan diedit atau disimpan baru
    if (isset($_GET['hal']) == "edit") {
        // data akan diedit 
        $edit = mysqli_query($koneksi, "UPDATE tbarng SET 
                                            nama = '$_POST[tnama]',
                                            asal = '$_POST[tasal]',
                                            jumlah= '$_POST[tjumlah]',
                                            satuan= '$_POST[tsatuan]',
                                            tanggal_diterima = '$_POST[ttanggal_diterima]'
                                        WHERE id_barang = '$_GET[id]'
                                        ");
        // uji jika sedit data sukses
   if($edit) {
    echo "<script>
        alert('Edit data Sukses!');
        document.location='index.php';
         </script>";

   } else {
    echo "<script>
        alert('Edit data Gagal');
        document.location='index.php';
        </script>";

   }

}else {
   
    // data akan disimpan baru
    $simpan = mysqli_query($koneksi, " INSERT INTO tbarng (kode, nama, asal, jumlah, satuan, tanggal_diterima)
                                       VALUE ( '$_POST[tkode]',
                                               '$_POST[tnama]',
                                               '$_POST[tasal]',
                                               '$_POST[tjumlah]',
                                               '$_POST[tsatuan]',
                                               '$_POST[ttanggal_diterima]' )
                                                ");
   // uji jika simpan data sukses
   if($simpan) {
    echo "<script>
        alert('Simpan data Sukses!');
        document.location='index.php';
         </script>";

   } else {
    echo "<script>
        alert('Simpan data Gagal');
        document.location='index.php';
        </script>";
   }
}

     
}

// dekelasris varibel untuk menampung data yang akan diedit 
$vnama = "";
$vasal = "";
$vjumlah = "";
$vsatuan = "";
$vtanggal_diterima = "";


// pengujian jika tombol aktif edit / hapus diklik
if (isset($_GET['hal'])) {

    // pengujian jika edit data 
    if($_GET['hal'] == "edit") {

        // tampilkan data yang akan diedit
        $tampil = mysqli_query($koneksi, "SELECT * FROM tbarng WHERE id_barang = '$_GET[id]' ");
        $data = mysqli_fetch_array($tampil);
        if($data) {
            // jika data diteima, maka di tampung ke dalam variabel
            $vkode = $data['kode'];
            $vnama = $data['nama'];
            $vasal = $data['asal'];
            $vjumlah = $data['jumlah'];
            $vsatuan = $data['satuan'];
            $vtanggal_diterima = $data['tanggal_diterima'];
        }
    }else if ($_GET['hal'] == "hapus") {

        // persiapan hapus data
        $hapus = mysqli_query($koneksi, "DELETE FROM tbarng WHERE id_barang = '$_GET[id]' ");
       
        // uji jika hapus data sukses
         if($hapus) {
         echo "<script>
            alert('Hapus data Sukses!');
            document.location='index.php';
            </script>";

          } else {
          echo "<script>
             alert('Hapus data Gagal');
             document.location='index.php';
             </script>";
      }
    }
}


 ?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>CRUD PHP & MySQL</title>
  </head>

  <body>

    <!-- awal kontainer -->
    <div class="container">
    <h1 class="text-center">Data Investaris</h1>
    <h1 class="text-center"></h1>

    <!-- akhir row -->
    <div class="row">
        <div class="col-md-8 mx-auto">
            <!-- awal card -->
            <div class="card">
                <div class="card-header bg-info text-light">
                    Form Input Data Barang 
                </div>
                <div class="card-body">

                <!-- awal fom -->
                <form method="POST">
                        <div class="mb-3">
                         <label class="form-label">Kode Barang</label>
                         <input type="text" name="tkode" value="<?= $vkode ?>" class="form-control" 
                         placeholder="Masukan Kode Barang">
                    </div>

                    <div class="mb-3">
                         <label class="form-label">Nama Barang</label>
                         <input type="text" name="tnama" value="<?= $vnama ?>" class="form-control" 
                         placeholder="Masukan Nama Barang">
                    </div>

                    <div class="mb-3">
                         <label class="form-label">Asal Barang</label>
                         <select class="form-select" name="tasal">
                            <Option value="<?= $asal ?>"><?= $vasal ?></Option>
                            <option value="Pembelian">Pembelian</option>
                            <option value="Hibah">Hibah</option>
                            <option value="Bantuan">Bantuan</option>
                            <option value="Sumbangan">Sumbangan</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col">
                        <div class="mb-3">
                         <label class="form-label">Jumlah</label>
                         <input type="number" name="tjumlah" value="<?= $jumlah ?>" class="form-control" 
                         placeholder="Masukan Jumlah Barang">
                         </div>
                        </div>

                        <div class="col">
                        <div class="mb-3">
                         <label class="form-label">Satuan</label>
                         <select class="form-select" name="tsatuan">
                            <Option value="<?= $satuan ?>"><?= $vsatuan ?></Option>
                            <option value="Unit">Unit</option>
                            <option value="Kotak">Kotak</option>
                            <option value="Pcs">Pcs</option>
                            <option value="Pak">Pak</option>
                         </select>
                      </div>
                     </div>

                     <div class="col">
                        <div class="mb-3">
                         <label class="form-label">Tanggal diterima</label>
                         <input type="date" name="ttanggal_diterima" value="<?= $tanggal_diterima ?>" class="form-control" 
                         placeholder="Masukan Jumlah Barang">
                         </div>
                        </div>

                        <!-- awal menu button -->
                        <div class="text-center">
                            <hr>
                            <button class="btn btn-primary" name="bsimpan" type="submit">Simpan</button>
                            <button class="btn btn-danger" nama="bkosongkan" type="riset">Kosangkan</button>
                        </div>
                        <!-- akhir menu button -->

                    </div>
                    
                </form>
                    <!-- akhir fom -->

                </div>
                <div class="card-footer bg-info">

                </div>
            </div>
            <!-- akhir card -->
        </div>
 </div>
 <!-- akhir row -->

         <div class="card mt-4">
                <div class="card-header bg-info text-light">
                 Data Barang 
                </div>
                
                <div class="card-body">
                    <div class="col-md-6 mx-auto">
                        <form method="POST">
                            <div class="input-group mb-3">
                                <input type="text" name="tcari" value="<?= @$_POST['tcari'] ?>" class="form-control" placeholder="Masukan Kata Kunci">
                                <button class="btn btn-primary" name="bcari" type="submit">Cari</button>
                                <button class="btn btn-danger" name="breset" type="submit">Reset</button>

                            </div>
                        </form>
                    </div>

                    <!-- membut table -->
                    <table class="table table-striped  table-hover table-bordered">
                    
                        <tr>
                            <th>No.</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Asal Barang</th>
                            <th>Jumlah</th>
                            <th>Tanggal diterima</th>
                            <th>Aksi</th>
                        </tr>
                       <?php
                    // persiapan menampilkan data
                    $no = 1;

                    // untuk pencarian data 
                    // jika tombol cari di klik
                    if(isset($_POST['bcari'])) {

                        // tampilkan data yang di cari 
                        $keyword = $_POST['tcari'];
                        $q = "SELECT * FROM tbarng WHERE kode like '%$keyword' or asal like '%$keyword' or nama like '%$keyword' order by id_barang desc ";

                    }else{
                        $q = "SELECT * FROM tbarng order by id_barang desc";
                    }

                    $tampil = mysqli_query($koneksi, $q);
                    while ($data = mysqli_fetch_array($tampil)) :

                    ?>
                
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $data['kode'] ?></td>
                            <td><?= $data['nama'] ?></td>
                            <td><?= $data['asal'] ?></td>
                            <td><?= $data['jumlah'] ?> <?= $data['satuan'] ?></td>
                            <td><?= $data['tanggal_diterima'] ?></td>
                            <td>
                                <a href="index.php?hal=edit&id=<?= $data['id_barang'] ?>" class="btn 
                                btn-warning">Edit</a>

                                <a href="index.php?hal=hapus&id=<?= $data['id_barang'] ?>" class="btn
                                btn-danger" onclick="return confirm('Apakah anda Yakin akan Hapus Data ini?')">Hapus</a>
                            </td>     
                        </tr>

                    <?php endwhile; ?>

                    </table>
                    <!-- akhir table -->

                </div>
                <div class="card-footer bg-info">

                </div>
            </div>
    <!-- akhir kontainer -->


    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  </body>
</html>