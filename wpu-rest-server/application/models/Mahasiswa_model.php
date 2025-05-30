<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mahasiswa_model extends CI_Model
{
    public function getMahasiswa()
    {
        return $this->db->get('mahasiswa')->result_array(); // Pastikan tabel mahasiswa ada
    }
}
