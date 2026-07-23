@php
    $emailTemplate = $emailTemplate ?? null;
@endphp

@if ($errors->any())
    <div class="mb-4 text-sm text-red-400 bg-[#1c1c1c] border border-[#2e2e2e] rounded px-3 py-2">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div>
    <label for="name" class="block text-sm mb-1">Názov šablóny</label>
    <input id="name" type="text" name="name" value="{{ old('name', $emailTemplate?->name) }}" required
        class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
</div>

<div class="mt-4">
    <label for="subject" class="block text-sm mb-1">Predmet</label>
    <input id="subject" type="text" name="subject" value="{{ old('subject', $emailTemplate?->subject) }}" required
        class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
</div>

<div class="mt-4">
    <label for="body" class="block text-sm mb-1">Text správy</label>
    <textarea id="body" name="body" rows="8" required
        class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">{{ old('body', $emailTemplate?->body) }}</textarea>
</div>
