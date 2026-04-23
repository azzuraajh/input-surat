<?php

namespace App\Http\Controllers;

use App\Http\Requests\Letter\StoreLetterRequest;
use App\Http\Requests\Letter\UpdateLetterRequest;
use App\Models\Letter;
use App\Services\LetterService;
use Illuminate\Http\Request;

class LetterController extends Controller
{
    public function __construct(private readonly LetterService $letterService) {}

    public function index(Request $request)
    {
        $allowedSorts = ['date', 'letter_no', 'subject', 'sender', 'recipient', 'status', 'created_at'];
        $sort = in_array($request->string('sort')->toString(), $allowedSorts, true)
            ? $request->string('sort')->toString()
            : 'date';
        $direction = $request->string('direction')->toString() === 'asc' ? 'asc' : 'desc';

        $letters = Letter::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->string('q')->toString();
                $query->where(function ($sub) use ($q) {
                    $sub->where('letter_no', 'like', "%{$q}%")
                        ->orWhere('subject', 'like', "%{$q}%")
                        ->orWhere('sender', 'like', "%{$q}%")
                        ->orWhere('recipient', 'like', "%{$q}%")
                        ->orWhere('category', 'like', "%{$q}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        return view('letters.index', [
            'letters' => $letters,
            'filters' => [
                'q' => $request->string('q')->toString(),
                'status' => $request->string('status')->toString(),
                'sort' => $sort,
                'direction' => $direction,
            ],
            'statuses' => Letter::statuses(),
        ]);
    }

    public function create()
    {
        return view('letters.create', [
            'letter' => new Letter([
                'date' => now()->toDateString(),
                'status' => Letter::STATUS_DRAFT,
            ]),
            'statuses' => Letter::statuses(),
        ]);
    }

    public function store(StoreLetterRequest $request)
    {
        $payload = $request->validated();
        $payload['letter_no'] = $this->letterService->generateLetterNo(new \DateTime($payload['date']));
        $payload['created_by'] = auth()->id();
        $payload['updated_by'] = auth()->id();

        $letter = Letter::create($payload);

        return redirect()
            ->route('letters.show', $letter)
            ->with('status', 'Surat berhasil dibuat.');
    }

    public function show(Letter $letter)
    {
        return view('letters.show', compact('letter'));
    }

    public function edit(Letter $letter)
    {
        return view('letters.edit', [
            'letter' => $letter,
            'statuses' => Letter::statuses(),
        ]);
    }

    public function update(UpdateLetterRequest $request, Letter $letter)
    {
        $payload = $request->validated();
        $payload['updated_by'] = auth()->id();

        $letter->update($payload);

        return redirect()
            ->route('letters.show', $letter)
            ->with('status', 'Surat berhasil diperbarui.');
    }

    public function destroy(Letter $letter)
    {
        $letter->delete();

        return redirect()
            ->route('letters.index')
            ->with('status', 'Surat berhasil dihapus (soft delete).');
    }
}
