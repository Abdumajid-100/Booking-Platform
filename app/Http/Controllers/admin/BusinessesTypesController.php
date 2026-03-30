<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessesTypes;
use Illuminate\Http\Request;

class BusinessesTypesController extends Controller
{
    public function index()
    {
        $types = BusinessesTypes::query()->latest()->get();

        return view('admin.businesses_types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.businesses_types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:businesses_types,name',
        ]);

        BusinessesTypes::create($data);

        return redirect()
            ->route('admin.businesses-types.index')
            ->with('success', 'Тип бизнеса добавлен.');
    }

    public function show(BusinessesTypes $businessesType)
    {
        $businessesType->loadCount('businesses');

        return view('admin.businesses_types.show', compact('businessesType'));
    }

    public function edit(BusinessesTypes $businessesType)
    {
        return view('admin.businesses_types.edit', compact('businessesType'));
    }

    public function update(Request $request, BusinessesTypes $businessesType)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:businesses_types,name,' . $businessesType->id,
        ]);

        $businessesType->update($data);

        return redirect()
            ->route('admin.businesses-types.index')
            ->with('success', 'Тип бизнеса обновлен.');
    }

    public function destroy(BusinessesTypes $businessesType)
    {
        if ($businessesType->businesses()->exists()) {
            return redirect()
                ->route('admin.businesses-types.index')
                ->with('error', 'Нельзя удалить тип, к которому уже привязаны бизнесы.');
        }

        $businessesType->delete();

        return redirect()
            ->route('admin.businesses-types.index')
            ->with('success', 'Тип бизнеса удален.');
    }
}
