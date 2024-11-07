<?php

namespace Tests\Feature;

use Tests\TestCase;

class PositiveTest extends TestCase
{
    protected $roles = ['admin', 'moderator', 'member'];

    public function testLogin()
    {
        foreach ($this->roles as $role) {
            $credentials = $this->getCredentials($role);
        
            $response = $this->post('/login', $credentials);
            $response->seeStatusCode(200);
            $response->seeJsonStructure([
                'status',
                'success',
                'message',
                'data' => [
                    'email',
                    'token'
                ]
            ]);
        }
    }

    public function testRegister()
    {
        $body = [
            'nama' => 'John Doe',
            'email' => 'john.doe@example.com',
            'umur' => 26,
            'password' => '123456'
        ];

        $response = $this->post('/register', $body);
        $response->seeStatusCode(200);
        $response->seeJsonStructure([
            'status',
            'success',
            'message',
            'data' => [
                'email',
                'token'
            ]
        ]);
    }

    public function testGetUsers()
    {
        foreach ($this->roles as $role) {
            $credentials = $this->getCredentials($role);
        
            $response = $this->post('/login', $credentials);
            $response->seeStatusCode(200);
            $response->seeJsonStructure([
                'data' => ['token']
            ]);
        
            $responseData = json_decode($response->response->getContent(), true);
            $token = $responseData['data']['token'];
        
            $response = $this->get('/users', [
                'Authorization' => 'Bearer ' . $token
            ]);

            $response->seeStatusCode(200);
            $response->seeJsonStructure([
                'status',
                'success',
                'message',
                'data' => [
                    'current_page',
                    'data' => [
                        '*' => ['id', 'email', 'nama', 'umur', 'roles_name']
                    ],
                    'first_page_url',
                    'last_page',
                    'last_page_url',
                    'links' => [
                        '*' => ['url', 'label', 'active']
                    ],
                    'next_page_url',
                    'path',
                    'per_page',
                    'prev_page_url',
                    'to',
                    'total'
                ]
            ]);
        }
    }

    public function testGetUserById()
    {
        foreach ($this->roles as $role) {
            $credentials = $this->getCredentials($role);
        
            $response = $this->post('/login', $credentials);
            $response->seeStatusCode(200);
            $response->seeJsonStructure([
                'data' => ['token']
            ]);
        
            $responseData = json_decode($response->response->getContent(), true);
            $token = $responseData['data']['token'];
        
            $response = $this->get('/users/1', [
                'Authorization' => 'Bearer ' . $token
            ]);

            $response->seeStatusCode(200);
            $response->seeJsonStructure([
                'status',
                'success',
                'message',
                'data' => [
                    '*' => ['id', 'email', 'nama', 'umur', 'roles_name']
                ]
            ]);
        }
    }

    public function testCreateUserAdmin()
    {
        $credentials = $this->getCredentials($this->roles[0]);
    
        $response = $this->post('/login', $credentials);
        $response->seeStatusCode(200);
        $response->seeJsonStructure([
            'data' => ['token']
        ]);
    
        $responseData = json_decode($response->response->getContent(), true);
        $token = $responseData['data']['token'];

        $body = [
            'nama' => 'John Cena',
            'email' => 'cena@example.com',
            'umur' => 30,
            'password' => '123456',
            'status' => 'member'
        ];

        $response = $this->post('/users', $body, [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->seeStatusCode(200);
        $response->seeJsonStructure([
            'status',
            'success',
            'message',
            'data' => []
        ]);
    }

    public function testCreateUserMember()
    {
        $credentials = $this->getCredentials($this->roles[2]);
    
        $response = $this->post('/login', $credentials);
        $response->seeStatusCode(200);
        $response->seeJsonStructure([
            'data' => ['token']
        ]);
    
        $responseData = json_decode($response->response->getContent(), true);
        $token = $responseData['data']['token'];

        $body = [
            'nama' => 'John Wick',
            'email' => 'wick@example.com',
            'umur' => 30,
            'password' => '123456',
            'status' => 'member'
        ];

        $response = $this->post('/users', $body, [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->seeStatusCode(401);
        $response->seeJsonStructure([
            'status',
            'success',
            'message',
            'data' => []
        ]);
    }

    public function testEditUser()
    {
        $credentials = $this->getCredentials($this->roles[0]);
    
        $response = $this->post('/login', $credentials);
        $response->seeStatusCode(200);
        $response->seeJsonStructure([
            'data' => ['token']
        ]);
    
        $responseData = json_decode($response->response->getContent(), true);
        $token = $responseData['data']['token'];

        $body = [
            'nama' => 'John Wick',
            'email' => 'wick@example.com',
            'umur' => 35,
            'password' => '123456',
            'status' => 'member'
        ];

        $response = $this->put('/users/27', $body, [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->seeStatusCode(200);
        $response->seeJsonStructure([
            'status',
            'success',
            'message',
            'data' => []
        ]);
    }

    public function testDeleteAdmin()
    {
        $credentials = $this->getCredentials($this->roles[0]);
    
        $response = $this->post('/login', $credentials);
        $response->seeStatusCode(200);
        $response->seeJsonStructure([
            'data' => ['token']
        ]);
    
        $responseData = json_decode($response->response->getContent(), true);
        $token = $responseData['data']['token'];
    
        $response = $this->delete('/users/28',[], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->seeStatusCode(200);
        $response->seeJsonStructure([
            'status',
            'success',
            'message',
            'data' => []
        ]);
    }

    public function testDeleteMember()
    {
        $credentials = $this->getCredentials($this->roles[2]);
    
        $response = $this->post('/login', $credentials);
        $response->seeStatusCode(200);
        $response->seeJsonStructure([
            'data' => ['token']
        ]);
    
        $responseData = json_decode($response->response->getContent(), true);
        $token = $responseData['data']['token'];
    
        $response = $this->delete('/users/26',[], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->seeStatusCode(401);
        $response->seeJsonStructure([
            'status',
            'success',
            'message',
            'data' => []
        ]);
    }

    private function getCredentials($role)
    {
        $credentials = [
            'admin' => [
                'email' => 'admin@example.com',
                'password' => '123456'
            ],
            'moderator' => [
                'email' => 'mod@example.com',
                'password' => '123456'
            ],
            'member' => [
                'email' => 'staff@example.com',
                'password' => '123456'
            ]
        ];
    
        return $credentials[$role];
    }
}
