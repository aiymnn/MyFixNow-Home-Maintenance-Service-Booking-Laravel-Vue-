<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $serviceCategories = ServiceCategory::latest()->paginate(10);

        if ($serviceCategories->isEmpty() && $serviceCategories->currentPage() > 1) {
            return redirect()->route('service-categories.index', ['page' => $serviceCategories->currentPage() - 1]);
        }

        return Inertia::render('Admin/ServiceCategory/Index', [
            'serviceCategories' => $serviceCategories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/ServiceCategory/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:255',
        ]);

        ServiceCategory::create($validated);

        return redirect()->route('service-categories.index')->with('success', 'Service category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceCategory $serviceCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceCategory $serviceCategory)
    {
        return Inertia::render('Admin/ServiceCategory/Edit', [
            'serviceCategory' => $serviceCategory,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
        ]);

        // dd($validated);
        $serviceCategory->update($validated);

        return redirect()->route('service-categories.index')->with('success', 'Service category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceCategory $serviceCategory)
    {
        $serviceCategory->delete();

        return redirect()->back()->with('success', 'Service category deleted successfully.');
    }
}
