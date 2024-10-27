<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    public function index(User $user) {

    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        // Создание нового пользователя
        $user = User::create([
            'first_name' => $request->input('name'),
            'last_name' => $request->input('surname'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        // Аутентификация пользователя
        Auth::login($user);

        return redirect()->route('home');
    }

    public function showList() {
        $users = User::all();

        return view('users', compact('users'));
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|string|max:255',
            'password' => 'required|min:6',
        ]);

        // Попытка аутентификации
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('home');
        }

        // Ошибка аутентификации
        return back()->withErrors([
            'email' => 'Неверные учетные данные.',
        ]);
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('home');
    }

    public function profile(?int $id = null) {
        if (is_null($id)) {
            $user = Auth::user();
        } else {
            $user = User::findOrFail($id);
        }
        return view('profile', compact('user'));
    }

    public function editProfile(?int $id = null) {
        if (is_null($id)) {
            $user = Auth::user();
        } else {
            $user = User::findOrFail($id);
        }
        return view('editProfile', compact('user'));
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id, // уникальность, исключая текущего пользователя
        ]);

        $user->update([
            'first_name' => $request->input('name'),
            'last_name' => $request->input('surname'),
            'email' => $request->input('email'),
            'password' => $request->input('password') ? Hash::make($request->input('password')) : $user->password,
        ]);

        return redirect()->route('home');
    }

    public function downloadProfileAsPdf(int $id)
    {
        $user = User::findOrFail($id);

        // Генерируем PDF из представления `userProfilePDF`
        $pdf = Pdf::loadView('userProfilePDF', compact('user'));

        // Возвращаем PDF-файл как ответ для загрузки
        return $pdf->download('profile_' . $user->id . '.pdf');
    }
}
