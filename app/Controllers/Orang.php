<?php

namespace App\Controllers;

use App\Models\OrangModel;


class Orang extends BaseController
{
    protected $orangmodel;
    public function __construct()
    {
        $this->orangmodel = new OrangModel();
    }

    public function index()
    {
        $currentPage = $this->request->getVar('page_orang') ? $this->request->getVar('page_orang') : 1;

        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $orang = $this->orangmodel->search($keyword);
        } else {
            $orang = $this->orangmodel;
        }

        $data = [
            'title' => 'Daftar Orang',
            // 'orang' => $this->orangmodel->findAll()
            'orang' => $orang->paginate(5, 'orang'),
            'pager' => $this->orangmodel->pager,
            'currentPage' => $currentPage
        ];

        return view('orang/index', $data);
    }
}
