<?php
include 'koneksi.php';

$query = "SELECT * FROM siswa";
$result = $koneksi->query($query);

if ($result->num_rows > 0){
    echo "<table border='1'>
           <tr>
               <th>ID</th>
               <th>Nama</th>
               <th>Email</th>
               <th>Alamat</th>
               <th>Aksi</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                 <td>" . $row['id'] . "</td>
                 <td>" . $row['nama'] . "</td>
                 <td>" . $row['email'] . "</td>
                 <td>" . $row['alamat'] . "</td>
                <td>
                    <a href='edit_data.php?id=" . $row['id'] . "'>Edit</a> |
                    <a href='edit_data.php?id=" . $row['id'] . "' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>
                    </td>
                </tr>";
    }
    echo "</table>";
} else {
    echo "Tidak ada data siswa.";
}
?>