<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Controller;
use App\Models\ModelNews;
use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;

class News extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $modelNews = new ModelNews();
            $data = $modelNews->findAll();
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
        $modelNews = new ModelNews();

        $data = $modelNews->orLike('id', $id)
            ->orLike('judul', $id)->get()->getResult();

            if(count($data) > 1) {
                $response = [
                    'status' => 200,
                    'error' => "false",
                    'message' => '',
                    'totaldata' => count($data),
                    'data' => $data,
            ];

            return $this->respond($response, 200);
            }else if(count($data) == 1) {
                $response = [
                    'status' => 200,
                    'error' => "false",
                    'message' => '',
                    'totaldata' => count($data),
                    'data' => $data,
                ];

            return $this->respond($response, 200);
            }else {
                return $this->failNotFound('maaf data ' . $id . ' tidak ditemukan');
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
        $modelNews = new ModelNews();
        $judul = $this->request->getPost("judul");
        $deskripsi = $this->request->getPost("deskripsi");

        $validation = \Config\Services::validation();
        
        $valid = $this->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
        ]);

        if(!$valid){
            $response = [
                'status' => 404,
                'error' => true,
                'message' => $validation->getErrors(),
            ];

        return $this->respond($response, 404);
        }else {
            $modelNews->insert([
                'judul' => $judul,
                'deskripsi' => $deskripsi,
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
        $model = new ModelNews();

        $data = [
            'judul' => $this->request->getVar("judul"),
            'deskripsi' => $this->request->getVar("deskripsi"),
        ];

        $data = $this->request->getRawInput();
        $model->update($id, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'message' => "Data News Anda dengan ID $id berhasil diupdate"
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
        $modelNews = new ModelNews();

        $cekData = $modelNews->find($id);
        if($cekData) {
            $modelNews->delete($id);
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
