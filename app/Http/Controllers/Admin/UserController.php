<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{ 
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('photo', function($row){
                    return '<img src="'.$row->photo_url.'" class="w-10 h-10 rounded-full object-cover border mx-auto">';
                })
                ->addColumn('status_badge', function($row){
                    $class = $row->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                    $text = $row->is_active ? 'Active' : 'Inactive';
                    return '<span class="px-2 py-1 text-xs font-semibold rounded-full '.$class.'">'.$text.'</span>';
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('admin.users.edit', $row->id).'" class="bg-blue-600 hover:bg-blue-800 text-white text-[10px] font-bold py-1 px-3 rounded-md shadow-sm transition inline-block">Edit User</a>';
                    return $btn;
                })
                ->rawColumns(['photo', 'status_badge', 'action'])
                ->make(true);
        }
        return view('admin.users.index');
    }
 
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
 
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'sometimes|in:admin,customer',
            'is_active' => 'sometimes|boolean',
        ]);

        $user->name = $request->name;
 
        if (auth()->id() !== $user->id && $request->has('role')) {
            $user->role = $request->role;
        }
 
        if ($user->role !== 'admin' && $request->has('is_active')) {
            $user->is_active = $request->is_active;
        } elseif ($user->role === 'admin') {
            $user->is_active = true;  
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
 
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own admin account.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}