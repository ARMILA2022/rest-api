<?php

// $mahasiswa =[
//     [
//         "nama" => "Armila",
//         "nrp" => "2217020091",
//         "email" => "armilaaini@gmail.com"
//     ],
//     [
//         "nama" => "Rindu",
//         "nrp" => "2217020100",
//         "email" => "Rindurahill@gmail.com"
//     ]
// ];

$dbh = new PDO('mysql:host=localhost;dbname=phpdasar', 'root');
$db = $dbh->prepare('SELECT * FROM mahasiswa');
$db->execute();
$mahasiswa = $db->fetchAll(PDO::FETCH_ASSOC);


header('Content-Type: application/json');
$data = json_encode($mahasiswa);
echo $data;

?>