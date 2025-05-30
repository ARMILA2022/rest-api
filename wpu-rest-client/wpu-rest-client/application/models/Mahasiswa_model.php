<?php 
use GuzzleHttp\Client;
#[\AllowDynamicProperties]

class Mahasiswa_model extends CI_model {
    public function getAllMahasiswa()
    {
        $client = new Client();

        $response = $client->request('GET', 'http://localhost/rest-api/wpu-rest-server/api/mahasiswa', [
            'auth' => ['naura', '271003'],
            'query' => [
                'X-API-KEY' => 'Fanivia0',
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return $result['data'];

    }

        public function getMahasiswaById($id)
    {
        $client = new Client();

        $response = $client->request('GET', 'http://localhost/rest-api/wpu-rest-server/api/mahasiswa', [
            'auth' => ['naura', '271003'],
            'query' => [
                'X-API-KEY' => 'Fanivia0',
                'id' => $id
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return $result['data'][0];
    }

    public function tambahDataMahasiswa()
    {
        $data = [
            "nama" => $this->input->post('nama', true),
            "nrp" => $this->input->post('nrp', true),
            "email" => $this->input->post('email', true),
            "jurusan" => $this->input->post('jurusan', true)
        ];

        $this->db->insert('mahasiswa', $data);
    }

    public function hapusDataMahasiswa($id)
    {
        // $this->db->where('id', $id);
        $this->db->delete('mahasiswa', ['id' => $id]);
    }

    public function ubahDataMahasiswa()
    {
        $data = [
            "nama" => $this->input->post('nama', true),
            "nrp" => $this->input->post('nrp', true),
            "email" => $this->input->post('email', true),
            "jurusan" => $this->input->post('jurusan', true)
        ];

        $this->db->where('id', $this->input->post('id'));
        $this->db->update('mahasiswa', $data);
    }

    public function cariDataMahasiswa()
    {
        $keyword = $this->input->post('keyword', true);
        $this->db->like('nama', $keyword);
        $this->db->or_like('jurusan', $keyword);
        $this->db->or_like('nrp', $keyword);
        $this->db->or_like('email', $keyword);
        return $this->db->get('mahasiswa')->result_array();
    }
}