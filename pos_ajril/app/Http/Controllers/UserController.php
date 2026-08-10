<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SearchRequest $request)
    {
        $keyword = $request->input('search');

        if ($keyword) {
            $users = User::whereRaw("MATCH(name, email) AGAINST(? IN BOOLEAN MODE)", [$keyword])
                // pencarian menggunakan indexing pada rancangan migrasi fullText pada kolom name dan email
                ->paginate(10)
                ->withQueryString();
        } else {
            $users = User::query()->paginate(10)->withQueryString();
        }

        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $dataReq = $request->validated();

        $data['name'] = $dataReq['name'];
        $data['email'] = $dataReq['email'];
        $data['password'] = Hash::make($dataReq['password']); // data dari input form pada create sesuai dengan name pada input
        $data['role_id'] = $dataReq['role_id'];
        // dimasukan ke kolom tabel users harus sesuai nama kolom pada database

        User::create($data);
        // preoses memasukan data ke tabel users

        return redirect()->route('admin.users')->with('success', 'User berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    /* menggunakan validasi UpdateRequest          menggunakan objek model User */
    public function update(UpdateRequest $request, User $user)
    {
        $dataReq = $request->validated();

        // Mengambil 'name' atau 'nama' agar tidak error
        $user->name    = $dataReq['name'] ?? $dataReq['nama'] ?? $user->name;
        $user->email   = $dataReq['email'] ?? $user->email;
        $user->role_id = $dataReq['role_id'] ?? $user->role_id;

        if (!empty($dataReq['password'])) {
            $user->password = Hash::make($dataReq['password']);
        }

        $user->save();

        return redirect()->route('admin.users')->with('success', 'User updated');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', 'User deleted');
    }
}
