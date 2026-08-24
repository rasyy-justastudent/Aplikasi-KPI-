<?php

namespace App\Controllers;

use App\Models\KategoriKpiModel;
use App\Models\IndikatorKpiModel;

class IndikatorController extends BaseController
{
    protected $kategoriModel;
    protected $indikatorModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriKpiModel();
        $this->indikatorModel = new IndikatorKpiModel();
    }

    public function index()
    {
        $kategoriId = $this->request->getGet('kategori_id');

        $kategoris = $this->kategoriModel->findAll();

        if (!$kategoriId && count($kategoris) > 0) {
            $kategoriId = $kategoris[0]['id'];
        }

        $selectedKategori = $this->kategoriModel->find($kategoriId);
        $indikators = $kategoriId ? $this->indikatorModel->getByKategori($kategoriId) : [];

        $data = [
            'title'            => 'Master Indikator Evaluation KPI (5 Pilar)',
            'kategoris'        => $kategoris,
            'selectedKategori' => $selectedKategori,
            'indikators'       => $indikators,
        ];

        return view('indikator/index', $data);
    }
}
