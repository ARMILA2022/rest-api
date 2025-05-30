<?php
use GuzzleHttp\Client;

#[\AllowDynamicProperties]
class Mahasiswa extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    public function index()
    {
        $data['judul'] = 'Daftar Mahasiswa';

        try {
            $client = new Client();
            $response = $client->request('GET', 'http://localhost/.rest-api/wpu-rest-server/api/mahasiswa', [
                'auth' => ['armila', '110804'],
                'query' => ['X-API-KEY' => 'wpu123']
            ]);
            $result = json_decode($response->getBody()->getContents(), true);
            $data['mahasiswa'] = $result['data'] ?? [];
        } catch (Exception $e) {
            $data['mahasiswa'] = [];
        }

        $this->load->view('templates/header', $data);
        $this->load->view('mahasiswa/index', $data);
        $this->load->view('templates/footer');
    }

    public function tambah()
    {
        $data['judul'] = 'Form Tambah Data Mahasiswa';

        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('nrp', 'NRP', 'required|numeric');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('mahasiswa/tambah');
            $this->load->view('templates/footer');
        } else {
            try {
                $client = new Client();
                $client->request('POST', 'http://localhost/.rest-api/wpu-rest-server/api/mahasiswa', [
                    'auth' => ['armila', '110804'],
                    'form_params' => [
                        'nama' => $this->input->post('nama', true),
                        'nrp' => $this->input->post('nrp', true),
                        'email' => $this->input->post('email', true),
                        'jurusan' => $this->input->post('jurusan', true),
                        'X-API-KEY' => 'wpu123'
                    ]
                ]);
                $this->session->set_flashdata('flash', 'ditambahkan');
            } catch (Exception $e) {
                $this->session->set_flashdata('flash', 'gagal ditambahkan');
            }
            redirect('mahasiswa');
        }
    }

    public function hapus($id)
    {
        try {
            $client = new Client();
            $client->request('DELETE', 'http://localhost/.rest-api/wpu-rest-server/api/mahasiswa', [
                'auth' => ['armila', '110804'],
                'form_params' => [
                    'id' => $id,
                    'X-API-KEY' => 'wpu-123'
                ]
            ]);
            $this->session->set_flashdata('flash', 'dihapus');
        } catch (Exception $e) {
            $this->session->set_flashdata('flash', 'gagal dihapus');
        }
        redirect('mahasiswa');
    }

    public function detail($id)
    {
        $data['judul'] = 'Detail Data Mahasiswa';

        try {
            $client = new Client();
            $response = $client->request('GET', 'http://localhost/.rest-api/wpu-rest-server/api/mahasiswa', [
                'auth' => ['armila', '110804'],
                'query' => [
                    'X-API-KEY' => 'wpu123',
                    'id' => $id
                ]
            ]);
            $result = json_decode($response->getBody()->getContents(), true);
            $data['mahasiswa'] = $result['data'][0] ?? [];
        } catch (Exception $e) {
            $this->session->set_flashdata('flash', 'gagal mengambil detail data');
            redirect('mahasiswa');
            return;
        }

        $this->load->view('templates/header', $data);
        $this->load->view('mahasiswa/detail', $data);
        $this->load->view('templates/footer');
    }

    public function ubah($id)
    {
        $data['judul'] = 'Form Ubah Data Mahasiswa';
        $data['jurusan'] = [
            'Teknik Informatika',
            'Teknik Mesin',
            'Teknik Planologi',
            'Teknik Pangan',
            'Teknik Lingkungan'
        ];

        try {
            $client = new Client();
            $response = $client->request('GET', 'http://localhost/.rest-api/wpu-rest-server/api/mahasiswa', [
                'auth' => ['armila', '110804'],
                'query' => [
                    'X-API-KEY' => 'wpu123',
                    'id' => $id
                ]
            ]);
            $result = json_decode($response->getBody()->getContents(), true);
            $data['mahasiswa'] = $result['data'][0] ?? [];
        } catch (Exception $e) {
            $this->session->set_flashdata('flash', 'gagal mengambil data untuk diubah');
            redirect('mahasiswa');
            return;
        }

        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('nrp', 'NRP', 'required|numeric');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('mahasiswa/ubah', $data);
            $this->load->view('templates/footer');
        } else {
            try {
                $client->request('PUT', 'http://localhost/.rest-api/wpu-rest-server/api/mahasiswa', [
                    'auth' => ['armila', '110804'],
                    'form_params' => [
                        'id' => $this->input->post('id'),
                        'nama' => $this->input->post('nama', true),
                        'nrp' => $this->input->post('nrp', true),
                        'email' => $this->input->post('email', true),
                        'jurusan' => $this->input->post('jurusan', true),
                        'X-API-KEY' => 'wpu123'
                    ]
                ]);
                $this->session->set_flashdata('flash', 'diubah');
            } catch (Exception $e) {
                $this->session->set_flashdata('flash', 'gagal diubah');
            }
            redirect('mahasiswa');
        }
    }
}
