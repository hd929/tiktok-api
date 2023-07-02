<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function users()
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->tick = !!$user->tick;
        }

        return response()->json(["data" => $users]);
    }

    public function search(Request $request)
    {
        $q = $request->query('q');
        $type = $request->query('type');
        $amouts = 0;

        switch ($type)
        {
        case 'less':
            $amouts = 5;
            break;
        case 'more':
            $amouts = 10;
            break;
        default:
            return ["Result" => "Invalid type"];
        }

        $users = User::where('full_name', 'like', "%$q%")
            ->orWhere('nickname', 'like', "%$q%")
            ->take($amouts)
            ->get();

        foreach ($users as $user) {
            $user->tick = !!$user->tick;
        }

        $users = $users->sortByDesc('followings_count')->values();

        return response()->json(["data" => $users]);
    }

    public function add(Request $req)
    {
        $user = new User;
        $user->id = $req->id;
        $user->first_name = $req->first_name ?: '';
        $user->last_name = $req->last_name ?: '';
        $user->nickname = $req->nickname;
        $user->bio = $req->bio;
        $user->tick = $req->tick ? 1 : 0;
        $user->followings_count = (int) $req->followings_count;

        if ($req->hasFile('avatar')) {
            $file = $req->file('avatar');
            $path = $file->store('public/avatars');
            $url = Storage::url($path);

            $user->avatar = asset($url);
        } else {
            $user->avatar = '';
        }

        if ($user->last_name) {
            $user->full_name = $user->first_name . " " . $user->last_name;
        } else {
            $user->full_name = $user->first_name;
        }

        if (!$user->first_name) {
            return ["Result" => "First name is required"];
        }

        $result = $user->save();

        if ($result) {
            return ["Result" => "Data has been saved"];
        } else {
            return ["Result" => "Operation Failed"];
        }
    }

    public function update(Request $req)
    {
        $user = User::find($req->id);
        $user->name = $req->name ? $req->name : $user->name;
        $user->email = $req->email ? $req->email : $user->email;

        $result = $user->save();

        if ($result) {
            return ["Result"  => "Data is updated"];
        } else {
            return ["Result" => "Update failed"];
        }
    }
}
