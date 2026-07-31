<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::ordered()->get();
        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update([
            'is_active' => !$paymentMethod->is_active
        ]);

        $status = $paymentMethod->is_active ? 'aktif' : 'pasif';
        return redirect()->route('admin.payment-methods.index')
            ->with('success', "Ödeme yöntemi {$status} hale getirildi.");
    }
} 