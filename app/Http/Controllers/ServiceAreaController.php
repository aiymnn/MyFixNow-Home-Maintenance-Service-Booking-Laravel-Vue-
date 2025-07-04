<?php

namespace App\Http\Controllers;

use App\Models\ServiceArea;
use App\Models\State;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $states = State::all()->select('id', 'name');
        $serviceArea = ServiceArea::with(['state'])->latest()->paginate(10);
        return Inertia::render('Admin/ServiceArea/Index', [
            'serviceAreas' => $serviceArea,
            'states' => $states,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
        ]);

        // dd($validated);

        ServiceArea::create($validated);

        return redirect()->back()->with('success', 'Service area created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceArea $serviceArea)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceArea $serviceArea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceArea $serviceArea)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
        ]);

        // dd($validated);

        $serviceArea->update($validated);

        return redirect()->back()->with('success', 'Service area updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceArea $serviceArea)
    {
        $serviceArea->delete();

        return redirect()->back()->with('success', 'Service area deleted successfully.');
    }
}
