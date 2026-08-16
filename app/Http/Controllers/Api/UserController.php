<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:supervisor')
            ->only(['store', 'update','destroy']);
    }
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $query = User::query();

    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    $users = $query->latest()->paginate(15);

    $users->getCollection()->each(function ($user) {
        if ($user->role === 'student') {
            $user->load('studentProfile');
        } elseif ($user->role === 'teacher') {
            $user->load('teacherProfile');
        }
    });

    return $users;
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

         if ($request->hasFile('personal_image')) {
            $validated['personal_image'] = $request->file('personal_image')
                ->store(
                    'tenants/' . tenant()->id . '/personal_images',
                    'public'
                );
        }
        $validated['password'] = Hash::make(
            $validated['password']
        );

        $user = User::create($validated);

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
   public function show(User $user)
{
    if ($user->role === 'student') {
        $user->load('studentProfile');
    } elseif ($user->role === 'teacher') {
        $user->load('teacherProfile');
    }

    return response()->json($user);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request,User $user)
    {
        $validated = $request->validated();

        if (isset($validated['password'])) {

            $validated['password'] = Hash::make(
                $validated['password']
            );

        }
        if ($request->hasFile('personal_image')) {

            if ($user->personal_image) {
                Storage::disk('public')
                    ->delete($user->personal_image);
            }

            $validated['personal_image'] = $request->file('personal_image')
                ->store(
                    'tenants/' . tenant()->id . '/personal_images',
                    'public'
                );
        }


        $user->update($validated);

        return response()->json($user);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {      
         if ($user->personal_image) {
            Storage::disk('public')
                ->delete($user->personal_image);
        }
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
