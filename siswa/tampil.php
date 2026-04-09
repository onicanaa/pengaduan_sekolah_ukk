<?php
include '../config/koneksi.php';

$data = mysqli_query($conn,"SELECT * FROM laporan");

while($row = mysqli_fetch_assoc($data)){
?>

    <p><?php echo $row['judul_laporan']; ?></p>

    <img src="../admin/uploads/<?php echo $row['foto']; ?>" width="150">

<?php
}
?>
