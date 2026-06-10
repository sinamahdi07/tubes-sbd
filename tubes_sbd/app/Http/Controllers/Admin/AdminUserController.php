<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->boolean('trash')) {
            $query->onlyTrashed();
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('role')) {
            $query->where('is_admin', $request->role === 'admin');
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function toggleAdmin(User $user)
    {
        // Prevent demoting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kamu tidak bisa mengubah status admin dirimu sendiri!');
        }

        $user->update(['is_admin' => ! $user->is_admin]);

        $status = $user->is_admin ? 'dijadikan Admin' : 'dicabut status Admin-nya';

        return back()->with('success', "User {$user->name} berhasil {$status}.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri!');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dipindahkan ke trash.');
    }

    public function restore(int $user)
    {
        User::onlyTrashed()->findOrFail($user)->restore();

        return redirect()->route('admin.users.index', ['trash' => 1])
            ->with('success', 'User berhasil direstore.');
    }

    public function forceDestroy(int $user)
    {
        if ($user === auth()->id()) {
            return back()->with('error', 'Kamu tidak bisa menghapus permanen akunmu sendiri!');
        }

        User::onlyTrashed()->findOrFail($user)->forceDelete();

        return redirect()->route('admin.users.index', ['trash' => 1])
            ->with('success', 'User berhasil dihapus permanen.');
    }
}
