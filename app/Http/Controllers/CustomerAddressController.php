<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerAddressRequest;
use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Http\RedirectResponse;

class CustomerAddressController extends Controller
{
    /**
     * Store a newly created address for the given customer.
     */
    public function store(StoreCustomerAddressRequest $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_default'] = $request->boolean('is_default');

        $customer->addresses()->create($validated);

        return redirect()->route('customers.show', $customer)->with('status', 'Adresa bola úspešne pridaná.');
    }

    /**
     * Remove the specified address from storage.
     */
    public function destroy(CustomerAddress $customerAddress): RedirectResponse
    {
        $customer = $customerAddress->customer;

        $customerAddress->delete();

        return redirect()->route('customers.show', $customer)->with('status', 'Adresa bola úspešne odstránená.');
    }
}
