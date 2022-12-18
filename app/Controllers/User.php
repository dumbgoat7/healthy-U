<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Controller;
use App\Models\ModelUser;
use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
class User extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $modelUser = new ModelUser();
            $data = $modelUser->findAll();
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
    public function show($cari = null)
    {
        $modelUser = new ModelUser();

        $data = $modelUser->orLike('id', $cari)
            ->orLike('email', $cari)->get()->getRow();

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
        $modelUser = new ModelUser();
        $username = $this->request->getPost("username");
        $email = $this->request->getPost("email");
        $tanggalLahir = $this->request->getPost("tanggalLahir");
        $noHp = $this->request->getPost("noHp");
        $password = $this->request->getPost("password");

        $validation = \Config\Services::validation();
        
        $valid = $this->validate([
            'username' => [
                'label' => 'Username',
                'rules' => 'required|is_unique[users.username]',
                'errors' => [
                    'required' => 'Username tidak boleh kosong',
                    'is_unique' => 'Username sudah terdaftar'    
                ]
            ],
            'email' => [
                'rules'  => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'valid_email' => 'Inputkan email',
                    'required' => 'Email tidak boleh kosong',
                    'is_unique' => 'Email sudah terdaftar'
                ],
            ],
            'tanggalLahir' => [
                'rules'  => 'required|valid_date',
                'errors' => [
                    'valid_date' => 'Inputkan tanggal lahir',
                    'required' => 'Tanggal Lahir tidak boleh kosong',
                ],
            ],
            'noHp' => [
                'rules'  => 'required|numeric|min_length[10]|max_length[13]',
                'errors' => [
                    'required' => 'No Hp tidak boleh kosong',
                    'numeric' => 'Inputan invalid',
                    'min_length' => 'No Hp minimal 10 digit',
                    'max_length' => 'No Hp maksimal 13 digit',
                ],
            ],
            'password' => [
                'rules'  => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Password tidak boleh kosong',
                    'min_length' => 'Password minimal 8 karakter',
                ]
            ],
            
        ]);

        if(!$valid){
            $response = [
                'status' => 404,
                'error' => true,
                'message' => $validation->getErrors(),
            ];

        return $this->respond($response, 404);
        }else {
            $modelUser->insert([
                'username' => $username,
                'email' => $email,
                'tanggalLahir' => $tanggalLahir,
                'noHp' => $noHp,
                'password' => $password,
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
        $model = new ModelUser();

        $data = [
            'username' => $this->request->getVar("username"),
            'email' => $this->request->getVar("email"),
            'tanggalLahir' => $this->request->getVar("tanggalLahir"),
            'noHp' => $this->request->getVar("noHp"),
            'password' => $this->request->getVar("password"),
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
}
