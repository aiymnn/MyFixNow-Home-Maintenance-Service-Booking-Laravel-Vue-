<?php

namespace App\Http\Controllers\Provider;

use Inertia\Inertia;
use App\Models\Service;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use App\Http\Controllers\Controller;
use App\Models\ServiceArea;
use App\Models\ServiceAreaService;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::with(['category'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);


        if ($services->isEmpty() && $services->currentPage() > 1) {
            return redirect()->route('services.index', ['page' => $services->currentPage() - 1]);
        }

        return Inertia::render('Provider/Service/Index', [
            'services' => $services,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Provider/Service/Create', [
            'serviceCategories' => ServiceCategory::select('id', 'name')->get(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'category_id' => 'required|exists:service_categories,id',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // dd($validated);

        // ✅ Simpan thumbnail dalam services/thumbnail
        $thumbnailPath = $request->file('image')->store('services/thumbnail', 'public');

        // ✅ Create service
        $service = Service::create([
            'user_id' => auth()->id(),
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'duration_minutes' => $validated['duration_minutes'],
            'image' => $thumbnailPath,
            // 'is_approved' => false,
        ]);

        // ✅ Simpan gallery dalam services/gallery
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $galleryPath = $image->store('services/gallery', 'public');

                ServiceImage::create([
                    'service_id' => $service->id,
                    'image_path' => $galleryPath,
                ]);
            }
        }

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        $states = State::select('id', 'name')->get();
        $areas = ServiceArea::select('id', 'name', 'state_id')->get();

        return Inertia::render('Provider/Service/Show', [
            'service' => $service->load([
                'category:id,name',
                'gallery:id,service_id,image_path',
                'areas' => fn($q) => $q->select('service_areas.id', 'service_areas.name', 'state_id'),
                'areas.state:id,name',
            ]),
            'states' => $states,
            'areas' => $areas,
        ]);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        $serviceCategories = ServiceCategory::select('id', 'name')->get();

        $service->load(['category', 'gallery']);

        return Inertia::render('Provider/Service/Edit', [
            'service' => [
                ...$service->toArray(),
                'image' => $service->image ? asset('storage/' . $service->image) : null,
                'gallery' => $service->gallery->map(fn($item) => [
                    'id' => $item->id,
                    'image_path' => asset('storage/' . $item->image_path),
                ]),
            ],
            'serviceCategories' => $serviceCategories,
        ]);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'category_id' => 'required|exists:service_categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'deleted_gallery_ids' => 'nullable|array',
            'deleted_gallery_ids.*' => 'uuid|exists:service_images,id',
        ]);

        // dd($validated);

        // Update basic fields
        $service->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'duration_minutes' => $validated['duration_minutes'],
            'category_id' => $validated['category_id'],
        ]);

        // Replace thumbnail image
        if ($request->hasFile('image')) {
            // Delete old thumbnail image if exists
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }

            // Store new thumbnail
            $path = $request->file('image')->store('services/thumbnail', 'public');
            $service->update(['image' => $path]);
        }

        // Add new gallery images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $path = $file->store('services/gallery', 'public');
                $service->gallery()->create([
                    'image_path' => $path,
                ]);
            }
        }

        // Delete selected gallery images
        if (!empty($validated['deleted_gallery_ids'])) {
            $imagesToDelete = ServiceImage::whereIn('id', $validated['deleted_gallery_ids'])->get();

            foreach ($imagesToDelete as $image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }

                $image->delete(); // soft delete or forceDelete() if needed
            }
        }

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        // Delete main image if exists
        if ($service->image && Storage::disk('public')->exists($service->image)) {
            Storage::disk('public')->delete($service->image);
        }

        // Delete gallery images if related
        foreach ($service->gallery as $galleryImage) {
            if (
                $galleryImage->image_path &&
                Storage::disk('public')->exists($galleryImage->image_path)
            ) {
                Storage::disk('public')->delete($galleryImage->image_path);
            }

            // Optionally delete gallery DB entry
            $galleryImage->delete();
        }

        // Delete the service record
        $service->delete();

        return to_route('services.index')->with('success', 'Service and its images deleted successfully.');
    }

    public function assignArea(Request $request, Service $service)
    {
        $validated = $request->validate([
            'area_id' => ['required', 'exists:service_areas,id'],
        ]);

        $alreadyAssigned = ServiceAreaService::where('service_id', $service->id)
            ->where('area_id', $validated['area_id'])
            ->exists();

        if (!$alreadyAssigned) {
            ServiceAreaService::create([
                'service_id' => $service->id,
                'area_id' => $validated['area_id'],
            ]);
        } else {
            return to_route('services.show', $service->id)->with('error', 'The area already assigned!');
        }

        return to_route('services.show', $service->id)->with('success', 'New area assigned successfully!');
    }

    public function removeArea(Service $service, ServiceArea $area)
    {
        // dd([
        //     'service_id' => $service->id,
        //     'area_id' => $area->id,
        //     'found' => ServiceAreaService::where('service_id', $service->id)->where('area_id', $area->id)->exists(),
        // ]);
        $matchedArea = $service->areas->where('id', $area->id)->first();

        if (!$matchedArea || !$matchedArea->pivot) {
            return back()->with('error', 'Area not assigned to this service.');
        }

        // dd($matchedArea->pivot);

        $matchedArea->pivot->delete();

        return back()->with('success', 'Area removed successfully.');
    }
}
