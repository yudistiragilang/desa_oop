-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Mar 2020 pada 07.52
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
(1, 'Yui kato', 'programsekripsi@db.com', 'Solo, Jawa tengah', '085647247592', 1, 'Logo Fpj 10cm X 10cm.png'),
(2, 'Yuki', 'programsekripsi@db.com', 'Solo, Jawa tengah', '085647247592', 2, 'default.jpg');

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
(1, 2, 2, 100000, 'Pintu tidak rapet', '2020-03-11 13:09:16', 1, 1, 1, '2020-03-11 13:26:45');

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
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `service`
--

INSERT INTO `service` (`id_service`, `id_pesan`, `id_pelanggan`, `service_id`, `harga`, `memo`, `created_date`, `created_by`, `status`) VALUES
(1, 1, 2, 2, 100000, 'Pintu tidak rapet', '2020-03-11 13:26:45', 1, 0);

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
(1, '10600', 'Service AC', 60000, 1, '2020-03-11 12:07:09', 0),
(2, '10600', 'Service Kulkas', 100000, 1, '2020-03-11 13:22:51', 0);

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
(1, 'yui', '$2y$10$c86R8HUK.CbqKKzvTWcZKOozcxmDAxl9QcLyxNQnJh7c32T2lWJem', '2020-03-11 13:02:33', '2020-03-11 11:54:29', 0, 1, 0),
(2, 'yuki', '$2y$10$d9sAyWxs3n7p7Pw0QT0VgOlXH74oyELm60r70QxEZstFYzhvFgoEq', '2020-03-11 13:01:14', '2020-03-11 13:01:06', 0, 2, 0);

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
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pesan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `service`
--
ALTER TABLE `service`
  MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `service_master`
--
ALTER TABLE `service_master`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
