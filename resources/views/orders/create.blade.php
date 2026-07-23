<x-layouts.app title="Nová objednávka – Commerce Engine">
    <h1 class="text-2xl font-semibold mb-6">Nová objedn<span class="text-[#d7e600]">ávka</span></h1>

    <form method="POST" action="{{ route('orders.store') }}" class="max-w-4xl bg-[#1c1c1c] border border-[#2e2e2e] text-[#EDEDEC] rounded-lg p-6">
        @csrf

        @include('orders._form')

        <div class="mt-6">
            <h2 class="text-lg font-semibold mb-3">Položky objednávky</h2>

            <div id="order-items" class="space-y-3">
                <div class="order-item-row grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                    <div class="md:col-span-8">
                        <label class="block text-sm mb-1">Produkt</label>
                        <select name="items[0][product_id]" required
                            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
                            <option value="">— vyberte produkt —</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} ({{ number_format((float) $product->price, 2) }} €)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm mb-1">Množstvo</label>
                        <input type="number" name="items[0][quantity]" min="1" value="1" required
                            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
                    </div>

                    <div class="md:col-span-1">
                        <button type="button" class="remove-item-row text-sm text-red-400 hover:underline">Zmazať</button>
                    </div>
                </div>
            </div>

            <button type="button" id="add-item-row" class="mt-3 text-sm text-[#d7e600] hover:underline">+ Pridať položku</button>
        </div>

        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
                Vytvoriť objednávku
            </button>
            <a href="{{ route('orders.index') }}" class="text-sm text-[#EDEDEC] opacity-70 hover:opacity-100">Zrušiť</a>
        </div>
    </form>

    <script>
        (function () {
            let itemIndex = 1;
            const container = document.getElementById('order-items');
            const template = container.querySelector('.order-item-row');

            document.getElementById('add-item-row').addEventListener('click', function () {
                const row = template.cloneNode(true);

                row.querySelectorAll('select, input').forEach(function (field) {
                    field.name = field.name.replace(/\[\d+\]/, '[' + itemIndex + ']');
                    field.value = field.tagName === 'SELECT' ? '' : (field.type === 'number' ? 1 : '');
                });

                container.appendChild(row);
                itemIndex++;
            });

            container.addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-item-row') && container.querySelectorAll('.order-item-row').length > 1) {
                    event.target.closest('.order-item-row').remove();
                }
            });
        })();
    </script>
</x-layouts.app>
