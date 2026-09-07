-- Database structure for db_sekolah
CREATE DATABASE IF NOT EXISTS db_sekolah;
USE db_sekolah;

-- 1. Tabel User (Hak Akses)
CREATE TABLE IF NOT EXISTS user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'siswa') NOT NULL DEFAULT 'siswa'
);

-- 2. Tabel Siswa (Data Pendukung)
CREATE TABLE IF NOT EXISTS siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nisn VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    kelas VARCHAR(20) NOT NULL
);

-- 3. Tabel Buku (Data Utama)
CREATE TABLE IF NOT EXISTS buku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    penerbit VARCHAR(100) NOT NULL,
    stok INT NOT NULL DEFAULT 0
);

-- 4. Tabel Transaksi Peminjaman
CREATE TABLE IF NOT EXISTS peminjaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_siswa INT NOT NULL,
    id_buku INT NOT NULL,
    tgl_pinjam DATE NOT NULL,
    tgl_kembali DATE NULL,
    status ENUM('Dipinjam', 'Dikembalikan') DEFAULT 'Dipinjam',
    FOREIGN KEY (id_siswa) REFERENCES siswa(id) ON DELETE CASCADE,
    FOREIGN KEY (id_buku) REFERENCES buku(id) ON DELETE CASCADE
);

-- Sample Data
INSERT INTO user (username, password, role) VALUES 
('admin', '$2y$10$wT0vD9C8xX3eE9Yg8J/u3.eY0e6R6JzN1yV3a8K1bC1dE2f3g4h5i', 'admin'),
('siswa', '$2y$10$wT0vD9C8xX3eE9Yg8J/u3.eY0e6R6JzN1yV3a8K1bC1dE2f3g4h5i', 'siswa')
ON DUPLICATE KEY UPDATE id=id;

INSERT INTO siswa (nisn, nama, kelas) VALUES 
('0012345678', 'Sutan Rafa', 'XI PPLG 2'),
('0087654321', 'Budi Santoso', 'XI PPLG 1')
ON DUPLICATE KEY UPDATE id=id;

INSERT INTO buku (judul, penerbit, stok) VALUES 
('Pemrograman Web Native PHP', 'Informatika', 5),
('Basis Data Relasional MySQL', 'Erlangga', 3)
ON DUPLICATE KEY UPDATE id=id;
