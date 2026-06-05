<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], $this->messages());

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('topicos.index'))
                ->with('success', 'Login realizado.');
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'As credenciais informadas não conferem.']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function profile()
    {
        $user = auth()->user();

        return view('perfil.show', [
            'user' => $user,
            'totalTopicos' => $user->topicos()->count(),
            'totalRespostas' => $user->respostas()->count(),
            'meusTopicos' => $user->topicos()
                ->withCount('respostas')
                ->latest('data_criacao')
                ->latest('id')
                ->take(5)
                ->get(),
            'minhasRespostas' => $user->respostas()
                ->with('topico')
                ->latest('data_resposta')
                ->latest('id')
                ->take(5)
                ->get(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('users', 'name')->ignore($user->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ], $this->messages());

        $user->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Perfil atualizado.');
    }

    public function updateProfilePhoto(Request $request)
    {
        $validated = $request->validate([
            'foto_perfil' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], $this->messages());

        $user = $request->user();

        if ($user->foto_perfil) {
            Storage::disk('public')->delete($user->foto_perfil);
        }

        $user->update([
            'foto_perfil' => $validated['foto_perfil']->store('perfis', 'public'),
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Foto atualizada.');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255', 'unique:users,name'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], $this->messages());

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('topicos.index')
            ->with('success', 'Conta criada.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('topicos.index')
            ->with('success', 'Sessão encerrada.');
    }

    private function messages(): array
    {
        return [
            'required' => 'Este campo é obrigatório.',
            'email' => 'Informe um e-mail válido.',
            'min' => 'Informe pelo menos :min caracteres.',
            'max' => 'Informe no máximo :max caracteres.',
            'unique' => 'Este valor já está em uso.',
            'confirmed' => 'A confirmação da senha não confere.',
            'image' => 'Envie um arquivo de imagem válido.',
            'mimes' => 'A imagem deve ser JPG, JPEG, PNG ou WEBP.',
        ];
    }
}
