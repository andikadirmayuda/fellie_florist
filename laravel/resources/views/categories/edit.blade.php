<x-app-layout>
    <x-slot name="header">
        <x-app.page-header title="Edit Kategori" />
    </x-slot>

    <x-app.card>
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf
            @method('PUT')

            <x-app.form-group
                label="Kode Kategori"
                for="code"
                :error="$errors->get('code')"
                helpText="Contoh: BP, BA, BQ, D">
                <x-app.input
                    id="code"
                    type="text"
                    name="code"
                    :value="old('code', $category->code)"
                    required
                    autofocus />
            </x-app.form-group>

            <x-app.form-group
                label="Nama Kategori"
                for="name"
                :error="$errors->get('name')"
                helpText="Contoh: Bunga Potong, Bunga Artificial">
                <x-app.input
                    id="name"
                    type="text"
                    name="name"
                    :value="old('name', $category->name)"
                    required />
            </x-app.form-group>

            <div class="flex items-center justify-end mt-4">
                <x-app.secondary-button href="{{ route('categories.index') }}" class="mr-3">
                    Batal
                </x-app.secondary-button>
                <x-app.primary-button type="submit">
                    Update Kategori
                </x-app.primary-button>
            </div>
        </form>
    </x-app.card>
</x-app-layout>
