<?php
session_start();
include 'db.php';

// Ambil ID dari URL
$id_surat = isset($_GET['id']) ? $_GET['id'] : "";
$sql_tampil = mysqli_query($conn, "SELECT * FROM surat WHERE id = '$id_surat'");
$row_surat = mysqli_fetch_assoc($sql_tampil);

//ambil data tujuan untuk dropdown
$sql_tujuan = mysqli_query($conn, "SELECT * FROM tujuan");
$sql_klasifikasi = mysqli_query($conn, "SELECT * FROM klasifikasi");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_tujuan = mysqli_real_escape_string($conn, $_POST['tujuan']);
    $klasifikasi = mysqli_real_escape_string($conn, $_POST['klasifikasi']);
    $tgl_berlaku = mysqli_real_escape_string($conn, $_POST['tgl_berlaku']);
    $tgl_sampai = mysqli_real_escape_string($conn, $_POST['tgl_sampai']);
    $detail = mysqli_real_escape_string($conn, $_POST['detail']);

	$update_surat = "UPDATE surat SET id_tujuan = '$id_tujuan', id_jenis = '$klasifikasi', berlaku_dari = '$tgl_berlaku', berlaku_sampai = '$tgl_sampai', detail = '$detail' WHERE id = '$id_surat'";
	 	 	 
	$insert = mysqli_query($conn, $update_surat);
	header("Location: " . $_SERVER['REQUEST_URI']);
	exit;
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
    <h4>Buat Surat:</h4>
    <form method="POST">                
        <label for="options">Tujuan:</label>
    	  <select id="options" name="tujuan">
			<?php
			while ($row_tujuan = mysqli_fetch_array($sql_tujuan)) {
    		$selected = ($row_surat['id_tujuan'] == $row_tujuan['id']) ? 'selected' : '';
			?>
    		<option value="<?php echo $row_tujuan['id']; ?>" <?php echo $selected; ?>>
        	<?php echo $row_tujuan['institusi'] . " / " . $row_tujuan['orang'] . " / " . $row_tujuan['jabatan'] . " / " . $row_tujuan['alamat']; ?>
    		</option>
			<?php
			}
			?>
			</select>
			<br><br>
		   <label for="options">Klasifikasi:</label>
		   <select id="options" name="klasifikasi">
			<?php while ($row_klasifikasi = mysqli_fetch_array($sql_klasifikasi)){ 
    		$selected = ($row_surat['id_jenis'] == $row_klasifikasi['id']) ? 'selected' : '';
			?> 
    		<option value="<?php echo $row_klasifikasi['id'] ?>" <?php echo $selected; ?>>
        <?php echo $row_klasifikasi['nama'] . "/" . $row_klasifikasi['nomor'] ?>
    		</option>
			<?php } ?>		  
			</select>
			<br><br>
		  <label for="tgl_berlaku">Tanggal Berlaku: </label>
        <input type="date" id="tgl_berlaku" name="tgl_berlaku" value="<?php echo $row_surat['berlaku_dari'] ?>" required><br><br>
        <label for="tgl_sampai">Berlaku Sampai: </label>
        <input type="date" id="tgl_sampai" name="tgl_sampai" value="<?php echo $row_surat['berlaku_sampai'] ?>" required><br><br>
        <label for="detail">Detail: </label>
        <textarea id="detail" name="detail" rows="8" cols="20"><?php echo $row_surat['detail']; ?></textarea>
		  <br><br>
    	  <label for="status">Status: </label>
    	  <input type="submit" value="Update Data"><br>
    </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
