<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pricing;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PricingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pricings = Pricing::all();
        return Inertia::render('Admin/Pricings/Index', [
            'pricings' => $pricings
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Pricings/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'plan_name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'type' => 'required|in:monthly,yearly'
        ]);

        Pricing::create([
            'plan_name' => $request->plan_name,
            'slug' => Str::slug($request->plan_name . '-' . $request->type),
            'description' => $request->description,
            'price' => $request->price,
            'type' => $request->type
        ]);

        return redirect()->route('pricings.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pricing $pricing)
    {
        return Inertia::render('Admin/Pricings/Show', [
            'pricing' => $pricing
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pricing $pricing)
    {
        return Inertia::render('Admin/Pricings/Edit', [
            'pricing' => $pricing
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pricing $pricing)
    {
        $request->validate([
            'plan_name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'type' => 'required|in:monthly,yearly'
        ]);

        $pricing->update([
            'plan_name' => $request->plan_name,
            'slug' => Str::slug($request->plan_name . '-' . $request->type),
            'description' => $request->description,
            'price' => $request->price,
            'type' => $request->type
        ]);

        return redirect()->route('pricings.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pricing $pricing)
    {
        $pricing->delete();
        return redirect()->route('pricings.index');
    }

    public function showJoinNowPage()
    {
        $pricings = Pricing::all();
        $monthlyPlans = $pricings->where('type', 'monthly')->keyBy('plan_name');
        $yearlyPlans = $pricings->where('type', 'yearly')->keyBy('plan_name');
    
        $plans = [];
        foreach ($monthlyPlans as $planName => $monthlyPlan) {
            if (isset($yearlyPlans[$planName])) {
                $yearlyPlan = $yearlyPlans[$planName];
                $monthlyFeatures = array_map('trim', explode(',', $monthlyPlan->description));
                $yearlyFeatures = array_map('trim', explode(',', $yearlyPlan->description));
                $plans[strtolower($planName)] = [
                    'monthly' => [
                        'price' => $monthlyPlan->price,
                        'features' => $monthlyFeatures,
                    ],
                    'yearly' => [
                        'price' => $yearlyPlan->price,
                        'features' => $yearlyFeatures,
                    ],
                ];
            }
        }
    
        return Inertia::render('joinNow/join_now', [
            'plans' => $plans,
        ]);
    }
}
