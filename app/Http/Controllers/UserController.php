<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Bonus;
use App\Models\Promo;

class UserController extends Controller
{
    public function account()
    {
        return view('account');
    }

    public function deposit()
    {
        return view('deposit');
    }

    public function withdraw()
    {
        return view('withdraw');
    }

    public function bonusRequest(Request $request)
    {
        $request->validate([
            'bonus_code' => 'required|string',
            'amount' => 'required|numeric|min:1'
        ]);

        // Bonus kodu kontrolü ve işlemi
        $bonus = Bonus::where('code', $request->bonus_code)
            ->where('active', true)
            ->first();

        if (!$bonus) {
            return back()->withErrors(['bonus_code' => 'Geçersiz bonus kodu']);
        }

        // Bonus işlemi burada yapılacak
        return back()->with('success', 'Bonus talebiniz alındı');
    }

    public function processDeposit(Request $request)
    {
        $request->validate([
            'method' => 'required|string',
            'amount' => 'required|numeric|min:1'
        ]);

        // Para yatırma işlemi burada yapılacak
        return back()->with('success', 'Para yatırma talebiniz alındı');
    }

    public function processWithdraw(Request $request)
    {
        $request->validate([
            'method' => 'required|string',
            'amount' => 'required|numeric|min:1'
        ]);

        // Para çekme işlemi burada yapılacak
        return back()->with('success', 'Para çekme talebiniz alındı');
    }

    public function promoCode(Request $request)
    {
        $request->validate([
            'promo_code' => 'required|string'
        ]);

        // Promosyon kodu kontrolü ve işlemi
        $promo = Promo::where('code', $request->promo_code)
            ->where('active', true)
            ->first();

        if (!$promo) {
            return back()->withErrors(['promo_code' => 'Geçersiz promosyon kodu']);
        }

        // Promosyon işlemi burada yapılacak
        return back()->with('success', 'Promosyon kodunuz kullanıldı');
    }
} 