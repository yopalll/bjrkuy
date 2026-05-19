<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Http\Requests\Admin\StoreSliderRequest;
use App\Http\Requests\Admin\UpdateSliderRequest;
use App\Services\CloudinaryService;

class AdminSliderController extends Controller
{
    protected $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = Slider::orderBy('order_position')->paginate(15);
        return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSliderRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $uploadResult = $this->cloudinaryService->upload($request->file('image'), 'sliders');
            
            if (!$uploadResult) {
                return redirect()->back()->with('error', 'Gagal mengunggah gambar ke server.')->withInput();
            }

            $data['image_url'] = $uploadResult['url'];
            $data['image_public_id'] = $uploadResult['public_id'];
        }

        Slider::create($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSliderRequest $request, Slider $slider)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($slider->image_public_id) {
                $this->cloudinaryService->delete($slider->image_public_id);
            }

            // Upload gambar baru
            $uploadResult = $this->cloudinaryService->upload($request->file('image'), 'sliders');
            
            if (!$uploadResult) {
                return redirect()->back()->with('error', 'Gagal mengunggah gambar ke server.')->withInput();
            }

            $data['image_url'] = $uploadResult['url'];
            $data['image_public_id'] = $uploadResult['public_id'];
        }

        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        if ($slider->image_public_id) {
            $this->cloudinaryService->delete($slider->image_public_id);
        }

        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('success', 'Slider berhasil dihapus.');
    }
}
