 <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="header-title">Buat Laporan Baru</div>
            <div class="header-actions">
                <a href="dashboard_siswa.php" class="btn-header">
                    <i class=""></i>
                    Kembali
                </a>
            </div>
        </div>
    </header>

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Formulir Laporan</h1>
        <p class="page-subtitle">Isi formulir di bawah untuk mengajukan pengaduan</p>
    </div>

    <!-- Form Container -->
    <section class="form-section">
        <div class="form-container">
            <form action="proses.php" method="POST" enctype="multipart/form-data">
                
                <!-- Nama Pelapor -->
                <div class="form-group">
                    <label class="form-label">
                        <i class=""></i>Nama Pelapor
                    </label>
                    <input type="text" class="form-control" name="nama" 
                           value="<?= htmlspecialchars($nama_user) ?>" readonly>
                </div>

                <!-- Kategori -->
                <div class="form-group">
                    <label class="form-label">
                        <i class=""></i>Kategori Laporan
                    </label>
                    <div class="category-grid">
                        <div class="category-option">
                            <input type="radio" name="kategori" value="Sarana" id="cat1" required>
                            <label class="category-label" for="cat1">
                                <i class=""></i>
                                Sarana
                            </label>
                        </div>
                        <div class="category-option">
                            <input type="radio" name="kategori" value="Kebersihan" id="cat2">
                            <label class="category-label" for="cat2">
                                <i class=""></i>
                                Kebersihan
                            </label>
                        </div>
                        <div class="category-option">
                            <input type="radio" name="kategori" value="Keamanan" id="cat3">
                            <label class="category-label" for="cat3">
                                <i class=""></i>
                                Keamanan
                            </label>
                        </div>
                        <div class="category-option">
                            <input type="radio" name="kategori" value="Lainnya" id="cat4">
                            <label class="category-label" for="cat4">
                                <i class=""></i>
                                Lainnya
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Judul -->
                <div class="form-group">
                    <label class="form-label">
                        <i class=""></i>Judul Laporan
                    </label>
                    <input type="text" class="form-control" name="judul" 
                           placeholder="Judul laporan" required>
                </div>

                <!-- Deskripsi -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-align-left me-2"></i>Deskripsi
                    </label>
                    <textarea class="form-control" name="isi" 
                              placeholder="Deskripsi" required></textarea>
                </div>

                <!-- Upload Foto -->
                <div class="form-group">
                    <label class="form-label">
                        <i class=""></i>Upload Foto (Opsional)
                    </label>
                    <div class="file-input-wrapper">
                        <input type="file" name="foto" id="fotoInput" accept="image/*">
                        <label for="fotoInput" class="file-input-label">
                            <div class="">
                                <i class=""></i>
                            </div>
                            <div class="file-text">
                                <div class="file-text-main">Pilih foto atau drag & drop</div>
                                <div class="file-text-sub">PNG, JPG, JPEG (Max. 5MB)</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="submit" class="btn-submit">
                    <i class=""></i>Kirim Laporan
                </button>

                <a href="dashboard_siswa.php" class="btn-cancel">Batal</a>
            </form>
        </div>
    </section>
