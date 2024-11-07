<?php

namespace Tests\Feature;

use Tests\TestCase;

class NegativeTest extends TestCase
{
    protected $roles = ['admin', 'moderator', 'member', 'emptyEmail', 'emptyPassword', 'wrongEmail', 'lessPassword'];

    public function testLoginFailed()
    {
        foreach ($this->roles as $role) {
            $credentials = $this->getCredentials($role);
        
            $response = $this->post('/login', $credentials);
            $response->seeStatusCode(400);
            $response->seeJsonStructure([
                'status',
                'success',
                'message',
                'data' => []
            ]);
        }
    }

    private function getCredentials($role)
    {
        $credentials = [
            'admin' => [
                'email' => 'admin@example.com',
                'password' => '1234567'
            ],
            'moderator' => [
                'email' => 'mod@example.com',
                'password' => '1234567'
            ],
            'member' => [
                'email' => 'staff@example.com',
                'password' => '1234567'
            ],
            'emptyEmail' => [
                'email' => '',
                'password' => '123456'
            ],
            'emptyPassword' => [
                'email' => 'admin@example.com',
                'password' => ''
            ],
            'wrongEmail' => [
                'email' => 'example',
                'password' => '123456'
            ],
            'lessPassword' => [
                'email' => 'example@example.com',
                'password' => '123'
            ]
        ];
    
        return $credentials[$role];
    }
}