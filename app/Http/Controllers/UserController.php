<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        // Create the user
        $user = User::create([
            'firstname' => $validated['firstname'],
            'middlename' => $validated['middlename'] ?? null,
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $fullName = trim($user->firstname . ' ' . ($user->middlename ? $user->middlename . ' ' : '') . $user->lastname);

        return response()->json([
            'message' => 'User created successfully',
            'user' => [
                'id' => $user->id,
                'name' => $fullName,
                'email' => $user->email,
                'lastActive' => optional($user->updated_at)->toDateTimeString(),
                'status' => 'active',
            ],
        ], 201);
    }

    public function index(Request $request)
    {
        $query = User::query();

        // Optional search by name or email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                    ->orWhere('middlename', 'like', "%{$search}%")
                    ->orWhere('lastname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Placeholder for filter (active/inactive) - no status column yet, so ignored
        // $filter = $request->input('filter');

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        // Transform to the shape expected by the frontend
        $users->getCollection()->transform(function ($user) {
            return [
                'id' => $user->id,
                'name' => trim($user->firstname . ' ' . ($user->middlename ? $user->middlename . ' ' : '') . $user->lastname),
                'email' => $user->email,
                'lastActive' => optional($user->updated_at)->toDateTimeString(),
                'status' => 'active',
            ];
        });

        return response()->json($users);
    }

    public function show(User $user)
    {
        return response()->json([
            'id' => $user->id,
            'firstname' => $user->firstname,
            'middlename' => $user->middlename,
            'lastname' => $user->lastname,
            'email' => $user->email,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
        ]);

        $user->firstname = $validated['firstname'];
        $user->middlename = $validated['middlename'] ?? null;
        $user->lastname = $validated['lastname'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        $fullName = trim($user->firstname . ' ' . ($user->middlename ? $user->middlename . ' ' : '') . $user->lastname);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $fullName,
                'email' => $user->email,
                'lastActive' => optional($user->updated_at)->toDateTimeString(),
                'status' => 'active',
            ],
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}
