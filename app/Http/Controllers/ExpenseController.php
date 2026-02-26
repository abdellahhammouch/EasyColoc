<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    
    private function assertActiveMember(Colocation $colocation): void
    {
        abort_unless(
            $colocation->users()
                ->where('users.id', auth()->id())
                ->wherePivotNull('left_at')
                ->exists(),
            403
        );
    }

    public function index(Request $request, Colocation $colocation)
    {
        $this->assertActiveMember($colocation);

        $query = $colocation->expenses()
            ->with(['payer', 'category'])
            ->orderByDesc('expense_date')
            ->orderByDesc('id');

        if ($request->filled('month')) {
            [$year, $month] = array_pad(explode('-', $request->month), 2, null);
            if ($year && $month) {
                $query->whereYear('expense_date', (int) $year)
                      ->whereMonth('expense_date', (int) $month);
            }
        }

        $expenses = $query->paginate(15)->withQueryString();
        $categories = $colocation->categories()->orderBy('name')->get();

        return view('expenses.index', compact('colocation', 'expenses', 'categories'));
    }

    public function create(Colocation $colocation)
    {
        $this->assertActiveMember($colocation);

        $categories = $colocation->categories()->orderBy('name')->get();

        return view('expenses.create', compact('colocation', 'categories'));
    }

    public function store(Request $request, Colocation $colocation)
    {
        $this->assertActiveMember($colocation);

        $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'expense_date' => ['required', 'date'],
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(fn ($q) =>
                    $q->where('colocation_id', $colocation->id)
                ),
            ],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        Expense::create([
            'colocation_id' => $colocation->id,
            'category_id' => $request->category_id,
            'paid_by' => auth()->id(),
            'title' => $request->title,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'notes' => $request->notes,
        ]);

        return redirect()
            ->route('expenses.index', $colocation)
            ->with('status', 'Dépense ajoutée.');
    }
}