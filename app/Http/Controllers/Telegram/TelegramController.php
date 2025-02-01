<?php
namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TelegramController extends Controller
{
    public function index()
    {
        return view('telegram.auth.login');
    }
    public function authByTelegram(Request $request)
    {

            $request->validate([
                'phone'       => 'required|string|max:12',
                'first_name'  => 'required|string',
                'last_name'   => 'nullable|string',
                'tg_username' => 'nullable|string',
                'tg_id'       => 'required|integer',
            ]);

            $userData = [
                'tg_id' => $request->tg_id,
                'first_name'  => $request->first_name,
                'second_name' => $request->last_name ?? '',
                'phone'       => $request->phone,
                'tg_username' => $request->tg_username ?? '',
            ];

            $user = User::where('phone', $request->phone)->first();
            $userWithTgId = User::where('tg_id', $request->tg_id)->first();

            if ($userWithTgId && $userWithTgId->phone !== $request->phone) {

                return response()->json([
                    'message' => 'Телефон вже прив`язаний до цього акаунту телеграм',
                ], 400);
            }

            if (!$user) {
                $user = User::create($userData);
            }

            Auth::login($user);
            return response()->json([
                'message' => 'Logged in via Telegram',
                'redirect' => route('home'),
                'user' => $user
            ], 200);
    }

}
