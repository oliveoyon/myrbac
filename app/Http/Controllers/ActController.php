<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ActController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $acts = Act::query()->when($search !== '', function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%'.$search.'%')
                    ->orWhere('act_number', 'like', '%'.$search.'%')
                    ->orWhere('year', 'like', '%'.$search.'%');
            });
        })->orderBy('year')->orderBy('id')->paginate(25)->withQueryString();

        return view('dashboard.admin.acts', compact('acts', 'search'));
    }

    public function create()
    {
        return view('dashboard.admin.act-form', ['act' => new Act]);
    }

    public function store(Request $request)
    {
        $act = Act::create($this->validated($request));
        LogService::logAction('Act Added', $act->toArray());

        return redirect()->route('acts.index')->with('success', 'Act added successfully.');
    }

    public function edit(Act $act)
    {
        return view('dashboard.admin.act-form', compact('act'));
    }

    public function update(Request $request, Act $act)
    {
        $act->update($this->validated($request, $act));
        LogService::logAction('Act Updated', $act->toArray());

        return redirect()->route('acts.index')->with('success', 'Act updated successfully.');
    }

    private function validated(Request $request, ?Act $act = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:1000'],
            'act_number' => ['nullable', 'string', 'max:255'],
            'year' => ['required', 'integer', 'between:1000,9999'],
            'url' => ['required', 'url:http,https', 'max:500', Rule::unique('acts', 'url')->ignore($act)],
        ]);
    }
}
