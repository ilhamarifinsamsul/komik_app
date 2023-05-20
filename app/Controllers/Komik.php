<?php

namespace App\Controllers;

use App\Models\KomikModel;


class Komik extends BaseController
{
    protected $komikmodel;
    public function __construct()
    {
        $this->komikmodel = new KomikModel();
    }

    public function index()
    {
        // $komik = $this->komikmodel->findAll();

        $data = [
            'title' => 'Daftar Komik',
            'komik' => $this->komikmodel->getKomik()
        ];


        return view('komik/index', $data);
    }

    public function detail($slug)
    {

        $data = [
            'title' => 'Detail Komik',
            'komik' => $this->komikmodel->getKomik($slug)
        ];

        // jika komik tidak ada ditable


        return view('komik/detail', $data);
    }

    public function create()
    {
        // session();

        $data = [
            'title' => 'Form Tambah Data Komik',
            'validation' => \Config\Services::validation()
        ];

        return view('komik/create', $data);
    }

    public function save()
    {
        // validasi input
        if (!$this->validate([
            'judul' => [
                'rules' => 'required|is_unique[komik.judul]',
                'errors' => [
                    'required' => '{field} komik harus diisi.',
                    'is_unique' => '{field} judul komik sudah terdaftar'
                ]
            ],
            'sampul' => [
                'rules' => 'max_size[sampul,1024]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    // 'uploaded' => 'Pilih gambar sampul terlebih dahulu',
                    'max_size' => 'Ukuran gambar terlalu besar',
                    'is_image' => 'Yang anda pilih bukan gambar',
                    'mime_in' => 'Yang anda pilih bukan gambar'
                ]
            ]

        ])) {
            // $validation = \Config\Services::validation();
            // return redirect()->to('/Komik/create')->withInput()->with('validation', $validation);
            return redirect()->to('/Komik/create')->withInput();
        }

        // ambil gambar
        $fileSampul = $this->request->getFile('sampul');

        // apakah tidak ada gambar yang di upload
        if ($fileSampul->getError() == 4) {
            $namaSampul = 'sampul.png';
        } else {
            // generate nama sampul random
            $namaSampul = $fileSampul->getRandomName();
            // pindah file ke folder img
            $fileSampul->move('img', $namaSampul);
        }




        $slug = url_title($this->request->getVar('judul'), '-', true);

        $this->komikmodel->save([
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $namaSampul
        ]);

        session()->setFlashdata('pesan', 'Data Berhasil ditambahkan');

        return redirect()->to('/Komik/index');
    }

    public function delete($id)
    {
        // Cari gambar berdasarkan id
        $komik = $this->komikmodel->find($id);

        // cek jika file gambarnya Sampul
        if ($komik['sampul'] != 'sampul.png') {
            // Hapus gambar
            unlink('img/' . $komik['sampul']);
        }


        $this->komikmodel->delete($id);
        session()->setFlashdata('pesan', 'Data Berhasil dihapus');

        return redirect()->to('/Komik/index');
    }

    public function edit($slug)
    {
        $data = [
            'title' => 'Form Ubah Data Komik',
            'validation' => \Config\Services::validation(),
            'komik' => $this->komikmodel->getKomik($slug)
        ];


        return view('komik/edit', $data);
    }

    public function update($id)
    {
        // cek judul
        $komiklama = $this->komikmodel->getKomik($this->request->getVar('slug'));

        if ($komiklama['judul'] == $this->request->getVar('judul')) {
            $rule_judul = 'required';
        } else {
            $rule_judul = 'required|is_unique[komik.judul]';
        }

        if (!$this->validate([
            'judul' => [
                'rules' => $rule_judul,
                'errors' => [
                    'required' => '{field} komik harus diisi.',
                    'is_unique' => '{field} judul komik sudah terdaftar'
                ]
            ],
            'sampul' => [
                'rules' => 'max_size[sampul,1024]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    // 'uploaded' => 'Pilih gambar sampul terlebih dahulu',
                    'max_size' => 'Ukuran gambar terlalu besar',
                    'is_image' => 'Yang anda pilih bukan gambar',
                    'mime_in' => 'Yang anda pilih bukan gambar'
                ]
            ]
        ])) {
            // $validation = \Config\Services::validation();
            return redirect()->to('/Komik/edit/' . $this->request->getVar('slug'))->withInput();
        }

        $fileSampul = $this->request->getFile('sampul');

        // cek gambar, apakah tetap gambar lama
        if ($fileSampul->getError() == 4) {
            $namaSampul = $this->request->getVar('sampulLama');
        } else {
            // generate nama file random
            $namaSampul = $fileSampul->getRandomName();
            // pindahkan gambar
            $fileSampul->move('img', $namaSampul);
            // hapus file lama
            unlink('img/' . $this->request->getVar('sampulLama'));
        }


        $slug = url_title($this->request->getVar('judul'), '-', true);

        $this->komikmodel->save([
            'id' => $id,
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $namaSampul
        ]);

        session()->setFlashdata('pesan', 'Data Berhasil diubah');

        return redirect()->to('/Komik/index');
    }
}
