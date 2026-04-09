<?php
include '../config/koneksi.php';

if (isset($_POST['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['id']);
    $query = mysqli_query($koneksi, "SELECT * FROM laporan WHERE id = '$id'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        $fotoPath = !empty($data['foto']) ? "uploads/" . $data['foto'] : null;
        ?>
        <div style="background:#1a1a1a; padding:20px; border-radius:15px; color:#fff; max-height: 85vh; overflow-y: auto;">
            
            <div style="display: grid; grid-template-columns: <?= $fotoPath ? '1fr 1fr' : '1fr' ?>; gap: 20px; align-items: start;">
                
                <div>
                    <div style="margin-bottom: 12px; border-left: 3px solid #6366f1; padding-left: 12px;">
                        <small style="color: #6366f1; font-weight: bold; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 1px;">Nama Pelapor</small>
                        <p style="font-size: 1.05rem; margin: 0; font-weight: 600; color: #fff;"><?= htmlspecialchars($data['nama_pelapor']) ?></p>
                    </div>
                    
                    <div style="margin-bottom: 12px; border-left: 3px solid #6366f1; padding-left: 12px;">
                        <small style="color: #6366f1; font-weight: bold; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 1px;">Judul Pengaduan</small>
                        <p style="font-size: 0.95rem; margin: 0; color: #eee;"><?= htmlspecialchars($data['judul_laporan']) ?></p>
                    </div>

                    <small style="color: #6366f1; font-weight: bold; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 1px; display: block; margin-bottom: 5px;">Deskripsi / Isi</small>
                    <div style="background: #252525; padding: 12px; border-radius: 10px; border: 1px solid #333;">
                        <div style="max-height: 150px; overflow-y: auto; font-size: 0.9rem; line-height: 1.6; color: #ccc; padding-right: 5px;">
                            <?= htmlspecialchars($data['isi_laporan']) ?>
                        </div>
                    </div>
                </div>

                <?php if ($fotoPath): ?>
                <div style="position: sticky; top: 0;">
                    <small style="color: #6366f1; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 8px; font-size: 0.65rem; letter-spacing: 1px;">Lampiran Foto</small>
                    <div style="border-radius: 12px; overflow: hidden; border: 1px solid #444; background: #000; display: flex; align-items: center; justify-content: center;">
                        <img src="<?= $fotoPath ?>" style="width: 100%; max-height: 300px; object-fit: contain; cursor: pointer;" onclick="window.open('<?= $fotoPath ?>', '_blank')">
                    </div>
                    <p style="font-size: 0.65rem; color: #666; margin-top: 5px; text-align: center;">Klik gambar untuk memperbesar</p>
                </div>
                <?php endif; ?>

            </div>

            <?php if (!$fotoPath): ?>
                <div style="margin-top: 15px; border-top: 1px solid #333; padding-top: 10px;">
                    <p style="opacity:0.3; font-size:0.75rem; font-style: italic; margin: 0;">* Tidak ada lampiran foto yang disertakan.</p>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
?>