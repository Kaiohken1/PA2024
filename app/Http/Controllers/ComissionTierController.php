<?php

namespace App\Http\Controllers;

use App\Models\CommissionTier;
use Illuminate\Http\Request;

class CommissionTierController extends Controller
{
    public function index()
    {
        $tiers = CommissionTier::all();
        return view('admin.commission_tiers.index', compact('tiers'));
    }

    public function create()
    {
        return view('admin.commission_tiers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'min_amount' => 'required|numeric',
            'max_amount' => 'nullable|numeric',
            'percentage' => 'required|numeric',
        ]);

        CommissionTier::create($request->all());
        return redirect()->route('commission_tiers.index');
    }

    public function edit($id)
    {
        $tier = CommissionTier::findOrFail($id);
        return view('admin.commission_tiers.edit', compact('tier'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'min_amount' => 'required|numeric',
            'max_amount' => 'nullable|numeric',
            'percentage' => 'required|numeric',
        ]);

        $tier = CommissionTier::findOrFail($id);
        $tier->update($request->all());
        return redirect()->route('commission_tiers.index');
    }

    public function destroy($id)
    {
        $tier = CommissionTier::findOrFail($id);
        $tier->delete();
        return redirect()->route('commission_tiers.index');
    }
}

