<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteInfo;
use App\Http\Requests\Admin\UpdateSiteSettingRequest;
use App\Services\CloudinaryService;

class AdminSiteSettingController extends Controller
{
    protected $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    public function index()
    {
        $settings = SiteInfo::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(UpdateSiteSettingRequest $request)
    {
        $data = $request->validated();

        // Handle Image Upload first
        if ($request->hasFile('logo')) {
            $oldPublicId = SiteInfo::get('logo_public_id');
            if ($oldPublicId) {
                $this->cloudinaryService->delete($oldPublicId);
            }

            $uploadResult = $this->cloudinaryService->upload($request->file('logo'), 'settings');
            
            if (!$uploadResult) {
                return redirect()->back()->with('error', 'Gagal mengunggah logo ke server.')->withInput();
            }

            // Remove logo from loop data and update manually to include public_id
            unset($data['logo']);
            SiteInfo::updateOrCreate(['key' => 'logo'], ['value' => $uploadResult['url']]);
            SiteInfo::updateOrCreate(['key' => 'logo_public_id'], ['value' => $uploadResult['public_id']]);
        }

        // Loop through the rest of the text settings
        foreach ($data as $key => $value) {
            // Null values are stored as empty strings or null
            SiteInfo::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan situs berhasil diperbarui.');
    }
}
