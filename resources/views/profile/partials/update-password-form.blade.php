<section>
    <header>
        <h2 class="text-xl font-black text-slate-900 tracking-tight">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm font-bold text-slate-400 uppercase tracking-widest">
            {{ __('Rotate your system access key to maintain high-level security.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-8 space-y-6">
        @csrf
        @method('put')

        <div class="space-y-2">
            <x-input-label for="update_password_current_password"
                class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" :value="__('Current Key')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password"
                class="block w-full py-4 px-6 bg-slate-50 border-none rounded-2xl text-slate-700 font-bold focus:ring-4 focus:ring-[#00ADC5]/10"
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="space-y-2">
            <x-input-label for="update_password_password"
                class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" :value="__('New Private Key')" />
            <x-text-input id="update_password_password" name="password" type="password"
                class="block w-full py-4 px-6 bg-slate-50 border-none rounded-2xl text-slate-700 font-bold focus:ring-4 focus:ring-[#00ADC5]/10"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="space-y-2">
            <x-input-label for="update_password_password_confirmation"
                class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" :value="__('Key Confirmation')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="block w-full py-4 px-6 bg-slate-50 border-none rounded-2xl text-slate-700 font-bold focus:ring-4 focus:ring-[#00ADC5]/10"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit"
                class="px-8 py-4 bg-[#00ADC5] rounded-2xl text-xs font-black text-white uppercase tracking-[0.2em] shadow-lg shadow-cyan-200 hover:bg-[#007A8A] transition-all active:scale-95">
                {{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-[10px] font-black uppercase tracking-widest text-emerald-500">
                    {{ __('Key Rotated Successfully.') }}</p>
            @endif
        </div>
    </form>
</section>