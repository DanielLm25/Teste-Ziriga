<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'date_of_birth' => 'required|date_format:d/m/Y',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'date_of_birth' => \Carbon\Carbon::createFromFormat('d/m/Y', $validated['date_of_birth'])->format('Y-m-d'),
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json(['message' => 'Usuário criado com sucesso'], 201);
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'date_of_birth' => 'nullable|date_format:d/m/Y',
            'password' => 'nullable|string|min:8',
        ]);

        if (isset($validated['name'])) {
            $user->name = $validated['name'];
        }
        if (isset($validated['email'])) {
            $user->email = $validated['email'];
        }
        if (isset($validated['date_of_birth'])) {
            $user->date_of_birth = \Carbon\Carbon::createFromFormat('d/m/Y', $validated['date_of_birth'])->format('Y-m-d');
        }
        if (isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        return response()->json(['message' => 'Usuário atualizado com sucesso']);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Usuário deletado com sucesso.']);
    }
}
