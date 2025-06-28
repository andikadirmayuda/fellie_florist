<div class="space-y-5 font-sans">
    <div>
        <x-input-label for="name" :value="__('Nama')" class="text-xs font-semibold text-black !text-black" />
        <div class="relative">
            <i class="bi bi-person absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input id="name" name="name" type="text" class="mt-1 block w-full pl-9 py-2 rounded-sm border border-gray-900 bg-black text-white placeholder-white focus:border-indigo-400 focus:ring-indigo-400 text-sm" value="{{ old('name', $customer->name ?? '') }}" required autofocus />
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="email" :value="__('Email')" class="text-xs font-semibold text-black !text-black" />
        <div class="relative">
            <i class="bi bi-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input id="email" name="email" type="email" class="mt-1 block w-full pl-9 py-2 rounded-sm border border-gray-900 bg-black text-white placeholder-white focus:border-indigo-400 focus:ring-indigo-400 text-sm" value="{{ old('email', $customer->email ?? '') }}" />
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>

    <div>
        <x-input-label for="phone" :value="__('No. Telepon')" class="text-xs font-semibold text-black !text-black" />
        <div class="relative">
            <i class="bi bi-telephone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input id="phone" name="phone" type="text" class="mt-1 block w-full pl-9 py-2 rounded-sm border border-gray-900 bg-black text-white placeholder-white focus:border-indigo-400 focus:ring-indigo-400 text-sm" value="{{ old('phone', $customer->phone ?? '') }}" required />
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
    </div>

    <div>
        <x-input-label for="type" :value="__('Tipe Customer')" class="text-xs font-semibold text-black !text-black" />
        <div class="relative">
            <i class="bi bi-person-badge absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <select id="type" name="type" class="mt-1 block w-full pl-9 py-2 rounded-sm border border-gray-900 bg-black text-white focus:border-indigo-400 focus:ring-indigo-400 text-sm appearance-none">
                @foreach($customerTypes as $type)
                    <option value="{{ $type }}" {{ (old('type', $customer->type ?? '') === $type) ? 'selected' : '' }}>
                        {{ ucfirst($type) }}
                    </option>
                @endforeach
            </select>
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('type')" />
    </div>

    <div>
        <x-input-label for="address" :value="__('Alamat')" class="text-xs font-semibold text-black !text-black" />
        <div class="relative">
            <i class="bi bi-geo-alt absolute left-3 top-3 text-gray-400 text-sm"></i>
            <textarea id="address" name="address" rows="3" class="mt-1 block w-full pl-9 py-2 rounded-sm border border-gray-900 bg-black text-white placeholder-white focus:border-indigo-400 focus:ring-indigo-400 text-sm resize-none">{{ old('address', $customer->address ?? '') }}</textarea>
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('address')" />
    </div>

    <div>
        <x-input-label for="city" :value="__('Kota')" class="text-xs font-semibold text-black !text-black" />
        <div class="relative">
            <i class="bi bi-buildings absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input id="city" name="city" type="text" class="mt-1 block w-full pl-9 py-2 rounded-sm border border-gray-900 bg-black text-white placeholder-white focus:border-indigo-400 focus:ring-indigo-400 text-sm" value="{{ old('city', $customer->city ?? '') }}" />
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('city')" />
    </div>
</div>
