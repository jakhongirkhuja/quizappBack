<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Project;
use App\Models\StartPage;
use App\Models\Tarif;
use App\Models\User;
use App\Models\UserLead;
use Illuminate\Support\Str;
use App\Services\ProjectService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    public function check(){
        $startPage =StartPage::first();
        return response()->json($startPage);
    }
    public function login(LoginRequest $request){
        $credentials = $request->validated();
        $user = User::where('email', $request->input('email'))->firstOrFail();
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

       
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ]);
    }
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
       
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $data['name']= 'Новый проект';
        $data['user_id']= $user->id;
        $data['uuid'] = Str::uuid();
        Project::create($data);
        $userLeads = new UserLead();
        $userLeads->user_id = $user->id;
        $userLeads->leads_added = 5;
        $userLeads->valid_from = Carbon::now();
        $userLeads->valid_until =  Carbon::now()->addWeek();
        $userLeads->test  =true;
        $userLeads->save();
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
    public function userUpdate(UserUpdateRequest $request){
        $data = $request->validated();
        $user = User::find(Auth::id());
        $user->name = $data['name'];
        $user->password= Hash::make($data['password']);
        return response()->json($user,201);
    }
    public function tarif(){
        return response()->json(Tarif::orderby('leads','asc')->get());
    }
}
