<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CasinoController extends Controller
{
    public function liveCasino()
    {
        return view('live-casino');
    }

    public function slots()
    {
        return view('slots');
    }

    public function otherGames()
    {
        return view('other-games');
    }

    public function sports()
    {
        return view('sports');
    }

    public function bonuses()
    {
        return view('bonuses');
    }

    public function tournaments()
    {
        return view('tournaments');
    }

    public function bonusRequest()
    {
        return view('bonus');
    }
} 