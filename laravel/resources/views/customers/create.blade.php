<x-app-layout>
    <x-slot name="head">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600,700" rel="stylesheet" />
        <style>
            body, .font-sans { font-family: 'Figtree', theme('fontFamily.sans'), sans-serif; }
        </style>
    </x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <i class="bi bi-person-plus-fill text-pink-400 text-2xl"></i>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight font-sans">
                {{ __('Tambah Customer') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen font-sans">
        <div class="max-w-2xl mx-auto px-2 sm:px-4">
            <div class="bg-white shadow-lg rounded-sm p-6 sm:p-8 border border-gray-100">
                <form method="POST" action="{{ route('customers.store') }}" class="space-y-5">
                    @csrf
                    @include('customers.partials.form')

                    <div class="mt-6 flex flex-col sm:flex-row items-center justify-end gap-3">
                        <a href="{{ route('customers.index') }}" class="inline-flex items-center text-sm font-semibold text-white bg-black shadow-lg hover:bg-gray-900 transition px-5 py-2 rounded-sm font-sans">
                            <i class="bi bi-arrow-left-circle mr-1"></i> Batal
                        </a>
                        <button type="submit" class="inline-flex items-center gap-1 px-5 py-2 rounded-sm shadow-lg font-sans text-white bg-black hover:bg-gray-900 transition font-semibold">
                            <i class="bi bi-save2 mr-1"></i> {{ __('Simpan') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
