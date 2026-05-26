<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TestResult;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            // 'email' => 'admin@gmail.com',
            'password' => bcrypt('123456')
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => '123456'
        ]);

        $response->assertStatus(302);

        TestResult::create([
            'test_case' => 'Login Test',
            'result' => 'Đăng nhập thành công',
            'status' => 'PASS'
        ]);
    }
}