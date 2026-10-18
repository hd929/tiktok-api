<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

/**
 * Xử lý đăng ký / đăng nhập / đăng xuất / lấy user hiện tại.
 *
 * Dùng Sanctum token: frontend gửi Authorization: Bearer <token>.
 */
class AuthController extends Controller
{
    /**
     * Đăng ký tài khoản mới.
     * Trả về token để frontend dùng ngay (không cần đăng nhập lại).
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['nullable', 'string', 'max:50'],
            'nickname' => ['required', 'string', 'max:50', 'unique:users,nickname'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'max:255'],
            // Avatar có thể là file upload hoặc để trống (dùng mặc định).
            'avatar' => ['nullable', 'image', 'max:5120'],
        ]);

        $fullName = trim($data['first_name'] . ' ' . ($data['last_name'] ?? ''));
        $avatar = 'https://i.pravatar.cc/150?u=' . urlencode($data['nickname']);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $avatar = asset(Storage::url($path));
        }

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'] ?? '',
            'full_name' => $fullName,
            'nickname' => $data['nickname'],
            'email' => $data['email'],
            'password' => $data['password'],
            'avatar' => $avatar,
            'bio' => '',
            'tick' => false,
            'followings_count' => 0,
            'likes_count' => 0,
            'followed' => false,
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'data' => $this->userResource($user),
            'token' => $token,
        ], 201);
    }

    /**
     * Đăng nhập: kiểm tra email + password, tạo token.
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email hoặc mật khẩu không đúng.'],
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'data' => $this->userResource($user),
            'token' => $token,
        ]);
    }

    /**
     * Đăng xuất: xoá token hiện tại.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['data' => true]);
    }

    /**
     * Lấy thông tin user đang đăng nhập (qua Bearer token).
     */
    public function me(Request $request)
    {
        return response()->json(['data' => $this->userResource($request->user())]);
    }

    /**
     * Định dạng response cho user (ẩn password, cast bool).
     */
    private function userResource(User $user): array
    {
        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'full_name' => $user->full_name,
            'nickname' => $user->nickname,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'bio' => $user->bio,
            'tick' => (bool) $user->tick,
            'followings_count' => (int) $user->followings_count,
            'likes_count' => (int) $user->likes_count,
            'followed' => (bool) $user->followed,
        ];
    }
}
