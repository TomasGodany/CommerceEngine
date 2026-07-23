<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CouponController extends Controller
{
    /**
     * Display a listing of the coupons.
     */
    public function index(): View
    {
        return view('coupons.index', [
            'coupons' => Coupon::latest()->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new coupon.
     */
    public function create(): View
    {
        return view('coupons.create');
    }

    /**
     * Store a newly created coupon in storage.
     */
    public function store(StoreCouponRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');

        Coupon::create($validated);

        return redirect()->route('coupons.index')->with('status', 'Kupón bol úspešne vytvorený.');
    }

    /**
     * Show the form for editing the specified coupon.
     */
    public function edit(Coupon $coupon): View
    {
        return view('coupons.edit', [
            'coupon' => $coupon,
        ]);
    }

    /**
     * Update the specified coupon in storage.
     */
    public function update(UpdateCouponRequest $request, Coupon $coupon): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');

        $coupon->update($validated);

        return redirect()->route('coupons.index')->with('status', 'Kupón bol úspešne upravený.');
    }

    /**
     * Remove the specified coupon from storage.
     */
    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        return redirect()->route('coupons.index')->with('status', 'Kupón bol úspešne odstránený.');
    }
}
