<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\TestResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponTest extends TestCase
{

    public function test_create_coupon()
    {
        $coupon = Coupon::create([
            'code' => 'SALE10',
            'discount' => 10,
            'status' => 1
        ]);

        $this->assertDatabaseHas('coupons', [
            'code' => 'SALE10'
        ]);

        TestResult::create([
            'test_case' => 'Coupon Test',
            'result' => 'Tạo khuyến mãi thành công',
            'status' => 'PASS'
        ]);
    }
}