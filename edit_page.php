<?php
session_start();
include 'db.php';

// Ambil ID dari URL
$user_id = isset($_GET['id']) ? $_GET['id'] : "";

// Sanitasi user ID
$user_id = mysqli_real_escape_string($conn, $user_id);

// Ambil data penerbit berdasarkan user_id, untuk menampilkan data user
$sql_userdata = mysqli_query($conn, "SELECT * FROM penerbit WHERE id_user='$user_id'");
$row_user = mysqli_fetch_array($sql_userdata);
$id_penerbit = $row_user['id']; //ekstrak id sebagai id_penerbit

//ambil data surat berdasarkan id_penerbit, untuk menampilkan surat dari penerbit yang login
$sql_suratuser = mysqli_query($conn, "SELECT surat.berlaku_dari, surat.berlaku_sampai, surat.detail, surat.status, klasifikasi.nama AS jenis, klasifikasi.nomor FROM surat INNER JOIN  klasifikasi ON surat.id_jenis = klasifikasi.id WHERE surat.id_penerbit = '$id_penerbit'");
$row_suratuser = mysqli_fetch_array($sql_suratuser);

//ambil data divisi untuk dropdown
$sql_divisi = mysqli_query($conn, "SELECT * FROM divisi");
$row_divisi = mysqli_fetch_array($sql_divisi);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nip = mysqli_real_escape_string($conn, $_POST['NIP']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $id_divisi = mysqli_real_escape_string($conn, $_POST['divisi']);

    if ($row_user) {
        // If entry exists, update it
        $update_sql = "UPDATE penerbit SET, id_divisi='$id_divisi', nama='$nama', NIP='$nip', jabatan='$jabatan', status='$status' WHERE id_user='$user_id'";
        if (mysqli_query($conn, $update_sql)) {
            echo "Data updated successfully.";
        } else {
            echo "Error updating data: " . mysqli_error($conn);
        }
    } else {
        // If entry does not exist, insert a new one
        $insert_sql = "INSERT INTO penerbit (id_user, nama, NIP, jabatan, status) VALUES ('$user_id', '$id_divisi', '$nama', '$nip', '$jabatan', '$status')";
        if (mysqli_query($conn, $insert_sql)) {
            echo "Data inserted successfully.";
        } else {
            echo "Error inserting data: " . mysqli_error($conn);
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="themes/midnight-green.css">
    <title>Edit Page</title>
    <style>
        col.max-width {
            max-width: 150px; /* Set the maximum width */
        }
        td {
            word-wrap: break-word; /* Ensure content wraps within the cell */
        }
    </style>
</head>
<body>
	 <div>	 
	 <?php if ($row_user): ?> <!--Jika user sudah terdaftar sebagai penerbit-->
    <h2>Halo, <u><?= $row_user['nama']; ?></u>. Anda login sebagai Admin.</h2>
	 <?php else: ?> <!--Jika user belum terdaftar sebagai penerbit-->
    <h3>Edit/Tambah Data Anda Sebagai Admin</h3>
	 <?php endif; ?>
    <form method="POST">
        <label for="nama">Nama:</label><br>
        <input type="text" id="nama" name="nama" value="<?php echo isset($row_user['nama']) ? $row_user['nama'] : ''; ?>" required><br><br>
        <label for="NIP">NIP:</label><br>
        <input type="text" id="NIP" name="NIP" value="<?php echo isset($row_user['NIP']) ? $row_user['NIP'] : ''; ?>" required><br><br>
        <label for="jabatan">Jabatan</label><br>
        <input type="text" id="jabatan" name="jabatan" value="<?php echo isset($row_user['jabatan']) ? $row_user['jabatan'] : ''; ?>" required><br><br>
        <label for="status">Status:</label><br>
        <input type="number" id="status" name="status" value="<?php echo isset($row_user['status']) ? $row_user['status'] : ''; ?>" required><br><br>
        <label for="options">Divisi</label>
    	  <select id="options" name="divisi">
				<?php
					$no_div = 1;
					while ($row_divisi = mysqli_fetch_array($sql_divisi)) {
				?>        		
    			<option value="<?php echo $row_divisi['id']; ?>">
        			<?php echo $row_divisi['nama_divisi'] . "/" . $row_divisi['kode_divisi']; ?>
    			</option>
				<?php
    			$no_div++;
				}
				?>
    	  </select>
    	  <br><br>
    	  <input type="submit" value="Update">
    </form>
    <h2>Data Anda</h2>
    <?php if ($row_user): ?> <!--Jika user sudah terdaftar sebagai penerbit-->
        <p>Nama: <?php echo $row_user['nama']; ?></p>
        <p>NIP: <?php echo $row_user['NIP']; ?></p>
        <p>Jabatan: <?php echo $row_user['jabatan']; ?></p>
        <p>Status: <?php echo $row_user['status']; ?></p>
    <?php else: ?> <!--Jika user belum terdaftar sebagai penerbit-->
        <p>Data tidak ditemukan, silahkan isi data Anda sebagai penerbit menggunakan form di atas.</p>
    <?php endif; ?>
    </div>
    <div>    	  
    	  <table>		  		
		  		<tr>
					<th>Berlaku Dari</th>
					<th>Berlaku Sampai</th>
					<th>Detail</th>
					<th>Status</th>
					<th>Jenis</th>
					<th>Nomor</th>
					<th>Opsi</th>    	  		
    	  		</tr>
    	  <?php if ($row_surat): ?> <!--Jika ada surat yang dimiliki oleh seorang user sebagai seorang penerbit-->		  		
				<?php
				$no_sur=1;
				while ($row_suratuser = mysqli_fetch_array($sql_suratuser)) {
				?>
    	  		<tr>
					<td></td>    	  		
    	  		</tr>
		  <?php else: ?> <!--Jika tidak ada surat-->
		  		<tr>
					<th><h3>Anda Masih Belum Menerbitkan Surat.</h3></th>    	  		
    	  		</tr>
		  <?php endif; ?>    	  	
    	  		
    	  </table>    
    </div>
</body>
</html>

<?php
$conn->close();
?>