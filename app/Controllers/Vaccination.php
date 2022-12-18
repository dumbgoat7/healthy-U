<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Controller;
use App\Models\ModelVaccination;
use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
class Vaccination extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $modelVaccination = new ModelVaccination();
            $data = $modelVaccination->findAll();
            $response = [
                'status' => 200,
                'error' => "false",
                'message' => '',
                'totaldata' => count($data),
                'data' => $data,
            ];

        return $this->respond($response, 200);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $modelVaccination = new ModelVaccination();

        $data = $modelVaccination->orLike('id', $cari)
            ->orLike('program', $cari)->get()->getRow();

            if(($data) != null) {
                $response = [
                    'status' => 200,
                    'error' => "false",
                    'message' => '',
                    'data' => $data,
            ];

            return $this->respond($response, 200);
            
            }else {
                return $this->failNotFound('maaf data ' . $cari . ' tidak ditemukan');
            }
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $modelVaccination = new ModelVaccination();
        $program = $this->request->getPost("program");
        $idUser = $this->request->getPost("idUser");
        $tanggal = $this->request->getPost("tanggal");
        $pelaksana = $this->request->getPost("pelaksana");


        $validation = \Config\Services::validation();
        
        $valid = $this->validate([
            'program' => 'required',
            'idUser' => 'required',
            'tanggal' => 'required|valid_date',
            'pelaksana' => 'required',
            
            
        ]);

        if(!$valid){
            $response = [
                'status' => 404,
                'error' => true,
                'message' => $validation->getErrors(),
            ];

        return $this->respond($response, 404);
        }else {
            $modelVaccination->insert([
                'program' => $program,
                'idUser' => $idUser,
                'tanggal' => $tanggal,
                'pelaksana' => $pelaksana,
            ]);

        $response = [
            'status' => 201,
            'error' => "false",
            'message' => "Data berhasil disimpan"
        ];

        return $this->respond($response, 201);
        }
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $model = new ModelVaccination();

        $data = [
            'program' => $this->request->getVar("program"),
            'idUser' => $this->request->getVar("idUser"),
            'tanggal' => $this->request->getVar("tanggal"),
            'pelaksana' => $this->request->getVar("pelaksana"),
        ];

        $data = $this->request->getRawInput();
        $model->update($id, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'message' => "Data Anda dengan ID $id berhasil dibaharukan"
            ];
        return $this->respond($response);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $modelVaccination = new ModelVaccination();

        $cekData = $modelVaccination->find($id);
        if($cekData) {
            $modelVaccination->delete($id);
            $response = [
                'status' => 200,
                'error' => null,
                'message' => "Selamat data news id $id sudah berhasil dihapus"
            ];
            return $this->respondDeleted($response);
        }else {
        return $this->failNotFound('Data tidak ditemukan kembali');
        }
    }
}
