<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Http\Requests\Admin\StorePartnerRequest;
use App\Http\Requests\Admin\UpdatePartnerRequest;
use App\Services\CloudinaryService;

class AdminPartnerController extends Controller
{
    protected $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    public function index()
    {
        $partners = Partner::orderBy('order_position')->paginate(15);
        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(StorePartnerRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $uploadResult = $this->cloudinaryService->upload($request->file('logo'), 'partners');
            
            if (!$uploadResult) {
                return redirect()->back()->with('error', 'Gagal mengunggah logo ke server.')->withInput();
            }

            $data['logo_url'] = $uploadResult['url'];
            $data['logo_public_id'] = $uploadResult['public_id'];
        }

        Partner::create($data);

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil ditambahkan.');
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(UpdatePartnerRequest $request, Partner $partner)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($partner->logo_public_id) {
                $this->cloudinaryService->delete($partner->logo_public_id);
            }

            $uploadResult = $this->cloudinaryService->upload($request->file('logo'), 'partners');
            
            if (!$uploadResult) {
                return redirect()->back()->with('error', 'Gagal mengunggah logo ke server.')->withInput();
            }

            $data['logo_url'] = $uploadResult['url'];
            $data['logo_public_id'] = $uploadResult['public_id'];
        }

        $partner->update($data);

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil diperbarui.');
    }

    public function destroy(Partner $partner)
    {
        if ($partner->logo_public_id) {
            $this->cloudinaryService->delete($partner->logo_public_id);
        }

        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil dihapus.');
    }
}
