<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index() {
        $users = User::where('role', 'staff')->get();
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $r) {
        User::create([
            'name' => $r->name,
            'email' => $r->email,
            'password' => bcrypt($r->password),
            'role' => 'staff',
            'status' => 'active'
        ]);

        return back()->with('success', 'Tạo nhân viên thành công');
    }

    public function toggle($id) {
        $u = User::find($id);   

        $u->status = $u->status == 'active' ? 'locked' : 'active';
        $u->save();

        return back()->with('success', 'Cập nhật trạng thái');
    }

    public function lock($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'locked';
        $user->save();

        return back()->with('success', 'Đã khóa tài khoản');
    }

    public function unlock($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'active';
        $user->save();  

        return back()->with('success', 'Đã mở khóa tài khoản');
    }
}
