<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $users = User::latest('id')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = [User::ROLE_ADMIN, User::ROLE_MEMBER];
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $data = $request->except('image');

            if ($request->hasFile('image')) {
                $data['image'] = Storage::put('users', $request->file('image'));
            }

            User::query()->create($data);

            return redirect()
                ->route('admin.users.index')
                ->with('success', true);
        } catch (\Throwable $th) {
            return back()->with('success', false);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = [User::ROLE_ADMIN, User::ROLE_MEMBER];

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $data = $request->except('image');

            $data['is_active'] = isset($data['is_active']) ? 1 : 0;

            if ($request->hasFile('image')) {
                $data['image'] = Storage::put('users', $request->file('image'));
            }

            $oldImage = $user->image;

            $user->update($data);

            if (
                $request->hasFile('image')
                && !empty($oldImage)
                && Storage::exists($oldImage)
            ) {
                Storage::delete($oldImage);
            }

            return redirect()
                ->route('admin.users.edit', $user->id)
                ->with('success', true);
        } catch (\Throwable $th) {
            return back()->with('success', false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()
                ->route('admin.users.index')
                ->with('success', true);
        } catch (\Throwable $th) {
            return back()->with('success', false);
        }
    }

    public function trash()
    {
        $trashList = User::onlyTrashed()->latest('id')->paginate(5);

        return view('admin.users.trash', compact('trashList'));
    }

    public function forceDestroy($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->forceDelete();
            if (Storage::exists($user->image)) {
                Storage::delete($user->image);
            }
            return redirect()
                ->route('admin.users.trash')
                ->with('success', true);
        } catch (\Throwable $th) {
            return back()->with('success', false);
        }
    }

    public function restore($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);

            // dd($user);
            $user->restore();
            return redirect()
                ->route('admin.users.index')
                ->with('success', true);
        } catch (\Throwable $th) {
            return back()->with('success', false);
        }
    }
}
