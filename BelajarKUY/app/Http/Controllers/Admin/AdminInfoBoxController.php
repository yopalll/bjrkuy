<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfoBox;
use App\Http\Requests\Admin\StoreInfoBoxRequest;
use App\Http\Requests\Admin\UpdateInfoBoxRequest;

class AdminInfoBoxController extends Controller
{
    public function index()
    {
        $infoBoxes = InfoBox::orderBy('order_position')->paginate(15);
        return view('admin.info_boxes.index', compact('infoBoxes'));
    }

    public function create()
    {
        return view('admin.info_boxes.create');
    }

    public function store(StoreInfoBoxRequest $request)
    {
        InfoBox::create($request->validated());

        return redirect()->route('admin.info-boxes.index')->with('success', 'Info Box berhasil ditambahkan.');
    }

    public function edit(InfoBox $infoBox)
    {
        return view('admin.info_boxes.edit', compact('infoBox'));
    }

    public function update(UpdateInfoBoxRequest $request, InfoBox $infoBox)
    {
        $infoBox->update($request->validated());

        return redirect()->route('admin.info-boxes.index')->with('success', 'Info Box berhasil diperbarui.');
    }

    public function destroy(InfoBox $infoBox)
    {
        $infoBox->delete();

        return redirect()->route('admin.info-boxes.index')->with('success', 'Info Box berhasil dihapus.');
    }
}
