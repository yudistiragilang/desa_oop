-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Agu 2020 pada 16.25
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
(1, 'Super Admin', 'programskripsi@test.com', 'Karangasem 4/3 Sroyo Jaten Kab. Karanganyar', '085647247592', 1, 'default.jpg'),
(2, 'Yudhistira Gilang Adisetyo', 'yudhistiragilang22@gmail.com', 'Karangasem 4/3 Sroyo Jaten Kab. Karanganyar', '081226558445', 2, 'default.jpg'),
(3, 'Bambang Sutedjo', 'bambang.tedjo@gmail.com', 'Karangasem 4/3 Sroyo Jaten Kab. Karanganyar', '081226558445', 3, 'default.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pesan` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `memo` varchar(100) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `approve_by` int(11) DEFAULT NULL,
  `approve_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pemesanan`
--

INSERT INTO `pemesanan` (`id_pesan`, `id_pelanggan`, `service_id`, `harga`, `memo`, `created_date`, `created_by`, `status`, `approve_by`, `approve_date`) VALUES
(1, 2, 2, 300000, 'Tidak tampil gambar', '2020-08-26 21:00:07', 2, 1, 1, '2020-08-26 21:00:34'),
(2, 2, 1, 100000, 'Tidak dingin', '2020-08-26 21:01:34', 2, 1, 1, '2020-08-26 21:02:00'),
(3, 3, 3, 150000, 'Suara tidak jernih', '2020-08-27 21:03:39', 3, 1, 1, '2020-08-27 21:04:02'),
(4, 3, 2, 300000, 'Gambar Tidak Tampil', '2020-08-28 21:18:49', 3, 1, 1, '2020-08-28 21:19:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `service`
--

CREATE TABLE `service` (
  `id_service` int(11) NOT NULL,
  `id_pesan` int(11) DEFAULT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `memo` varchar(100) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `biaya_tambahan` int(11) NOT NULL,
  `memo_biaya_tambahan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `service`
--

INSERT INTO `service` (`id_service`, `id_pesan`, `id_pelanggan`, `service_id`, `harga`, `memo`, `created_date`, `created_by`, `status`, `biaya_tambahan`, `memo_biaya_tambahan`) VALUES
(1, 1, 2, 2, 300000, 'Tidak tampil gambar', '2020-08-26 21:00:34', 1, 1, 25000, 'Jasa Service'),
(2, 2, 2, 1, 100000, 'Tidak dingin', '2020-08-26 21:02:00', 1, 1, 0, 'tidak ada biaya tambahan'),
(3, 3, 3, 3, 150000, 'Suara tidak jernih', '2020-08-27 21:04:02', 1, 1, 50000, 'Beli Equalizer'),
(4, 4, 3, 2, 300000, 'Gambar Tidak Tampil', '2020-08-28 21:19:26', 1, 1, 50000, 'Beli PCB');

-- --------------------------------------------------------

--
-- Struktur dari tabel `service_master`
--

CREATE TABLE `service_master` (
  `service_id` int(11) NOT NULL,
  `kode_service` varchar(5) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `harga_service` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `service_master`
--

INSERT INTO `service_master` (`service_id`, `kode_service`, `description`, `harga_service`, `created_by`, `created_date`, `inactive`) VALUES
(1, 'E-01', 'Service Kulkas', 100000, 1, '2020-08-26 20:56:46', 0),
(2, 'E-02', 'Service TV LED', 300000, 1, '2020-08-26 20:57:05', 0),
(3, 'E-03', 'Service Audio', 150000, 1, '2020-08-26 20:57:28', 0),
(4, 'E-04', 'Service TV Tabung', 90000, 1, '2020-08-26 20:57:50', 0);

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
(1, 'alice', '$2y$10$M47XnmcIREaP7HtQUWIKzOaTgaDXPJclV7CdwF9z9KYs1V/4VBlXi', '2020-08-26 20:55:57', '2020-08-26 20:55:29', 0, 1, 0),
(2, 'gilang', '$2y$10$diwO.ytmNQm94jexqnvRQ.Qqk9N/nAMsJzbXLJrCBOAUQmCDbdTiC', '2020-08-26 20:59:50', '2020-08-26 20:59:42', 0, 2, 0),
(3, 'bambang', '$2y$10$QaH8jtblYHswH15Jb2LANe2QI0646P9Shnd4xG9t.0/NCyW84kjba', '2020-08-26 21:11:07', '2020-08-26 21:03:18', 0, 2, 0);

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
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pesan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `service`
--
ALTER TABLE `service`
  MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `service_master`
--
ALTER TABLE `service_master`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
