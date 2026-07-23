<x-layouts.app title="Audit log – Commerce Engine">
    <h1 class="text-2xl font-semibold mb-6">Audit <span class="text-[#d7e600]">log</span></h1>

    @if (session('status'))
        <div class="mb-4 text-sm text-[#d7e600] bg-[#1c1c1c] border border-[#2e2e2e] rounded px-3 py-2">
            {{ session('status') }}
        </div>
    @endif

    <form method="GET" action="{{ route('audit-logs.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 bg-[#1c1c1c] border border-[#2e2e2e] rounded-lg p-4">
        <div>
            <label for="user_id" class="block text-sm mb-1">Používateľ</label>
            <select id="user_id" name="user_id"
                class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
                <option value="">— všetci —</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="action" class="block text-sm mb-1">Akcia</label>
            <select id="action" name="action"
                class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
                <option value="">— všetky —</option>
                @foreach ($actions as $action)
                    <option value="{{ $action }}" @selected(request('action') == $action)>{{ $action }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="model" class="block text-sm mb-1">Typ záznamu</label>
            <select id="model" name="model"
                class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
                <option value="">— všetky —</option>
                @foreach ($models as $model)
                    <option value="{{ $model }}" @selected(request('model') == $model)>{{ class_basename($model) }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end gap-3">
            <button type="submit" class="rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
                Filtrovať
            </button>
            <a href="{{ route('audit-logs.index') }}" class="text-sm text-[#EDEDEC] opacity-70 hover:opacity-100">Zrušiť filter</a>
        </div>
    </form>

    <div class="rounded-lg border border-[#2e2e2e] bg-[#1c1c1c] overflow-hidden">
        <table class="w-full text-sm text-left text-[#EDEDEC]">
            <thead class="bg-[#141414] text-xs uppercase opacity-70">
                <tr>
                    <th class="px-4 py-3">Dátum</th>
                    <th class="px-4 py-3">Používateľ</th>
                    <th class="px-4 py-3">Akcia</th>
                    <th class="px-4 py-3">Typ záznamu</th>
                    <th class="px-4 py-3">ID záznamu</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($auditLogs as $auditLog)
                    <tr class="border-t border-[#2e2e2e]">
                        <td class="px-4 py-3 opacity-70">{{ $auditLog->created_at->format('d.m.Y H:i') }}</td>
                        <td class="px-4 py-3 font-medium">{{ $auditLog->user?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $auditLog->action }}</td>
                        <td class="px-4 py-3">{{ $auditLog->auditable_type ? class_basename($auditLog->auditable_type) : '—' }}</td>
                        <td class="px-4 py-3 opacity-70">{{ $auditLog->auditable_id ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center opacity-70">Zatiaľ neboli zaznamenané žiadne udalosti.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $auditLogs->links() }}
    </div>
</x-layouts.app>
