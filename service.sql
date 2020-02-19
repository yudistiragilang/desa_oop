-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Feb 2020 pada 20.24
-- Versi server: 10.1.37-MariaDB
-- Versi PHP: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `service`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `alamat` text,
  `no_telepon` varchar(15) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama`, `email`, `alamat`, `no_telepon`, `user_id`, `foto`) VALUES
(3, 'Yudistira Gilang Adisetyo', 'yudhistiragilang22@gmail.com', 'Dsn Karangasem RT 04 RW 03 Sroyo Jaten', '085647247592', 6, NULL),
(5, 'Tyas Ayu Nanda Pertiwi', 'gilacoc1122@gmail.com', 'Dsn Karangasem RT 04 RW 03 Sroyo Jaten', '081226558445', 8, NULL),
(6, 'Yayas', 'gilacoc1122@gmail.com', 'Dsn Karangasem RT 04 RW 03 Sroyo Jaten', '081226558445', 9, NULL),
(7, 'cek cek', 'gilacoc1122@gmail.com', 'Dsn Karangasem RT 04 RW 03 Sroyo Jaten', '085647247592', 11, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pesan` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `memo` varchar(100) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `approve_by` int(11) DEFAULT NULL,
  `approve_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `service`
--

CREATE TABLE `service` (
  `id_service` int(11) NOT NULL,
  `id_pesan` int(11) DEFAULT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `memo` varchar(100) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `service_master`
--

CREATE TABLE `service_master` (
  `service_id` int(11) NOT NULL,
  `kode_service` varchar(5) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(60) NOT NULL,
  `last_visit` datetime DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT NULL,
  `role` tinyint(1) DEFAULT NULL,
  `available` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `last_visit`, `created_date`, `inactive`, `role`, `available`) VALUES
(6, 'gilang.adisetyo', '$2y$10$dFrJ07tWIQoIC7y0pqxB8eVLAJUWyIrNiVybM3NXQdmDJWNDw3Mmy', '2020-02-20 01:40:22', '2020-02-19 23:43:25', 0, 1, 0),
(8, 'yayo', '$2y$10$98czJ8DFnrEOehwn0YDLYOxGjK6JrakV8svBlmLSU3HnOOnWHOnL.', NULL, '2020-02-20 01:20:42', 0, 2, 0),
(9, 'yess', '$2y$10$.gOy0wogNSPKvWjCGEbHteD30U83ubXlrymQoJhyvIepnFRNiJ1c2', NULL, '2020-02-20 01:24:27', 0, 2, 0),
(11, 'ceks', '$2y$10$HGW5aieqJLbJvgCk.BX4deSFdLXunJpL8VnG4rw4yuw7cQad9m5l2', NULL, '2020-02-20 01:40:01', 0, 2, 0);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id_pesan`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `service_id` (`service_id`);

--
-- Indeks untuk tabel `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id_service`),
  ADD KEY `id_pesan` (`id_pesan`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `service_id` (`service_id`);

--
-- Indeks untuk tabel `service_master`
--
ALTER TABLE `service_master`
  ADD PRIMARY KEY (`service_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`) USING BTREE;

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pesan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `service`
--
ALTER TABLE `service`
  MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `service_master`
--
ALTER TABLE `service_master`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD CONSTRAINT `pelanggan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Ketidakleluasaan untuk tabel `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`),
  ADD CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `service_master` (`service_id`);

--
-- Ketidakleluasaan untuk tabel `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `service_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `service_master` (`service_id`),
  ADD CONSTRAINT `service_ibfk_2` FOREIGN KEY (`id_pesan`) REFERENCES `pemesanan` (`id_pesan`),
  ADD CONSTRAINT `service_ibfk_3` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
