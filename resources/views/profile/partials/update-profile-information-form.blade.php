<section>
    <header>
        <h2 class="text-xl font-black text-slate-900 tracking-tight">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm font-bold text-slate-400 uppercase tracking-widest">
            {{ __("Sync your account's primary identification and contact relay.") }}
        </p>
    </header>

    @if (Route::has('verification.send'))
        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>
    @endif

    <form method="post" action="{{ route('profile.update') }}" class="mt-8 space-y-6">
        @csrf
        @method('patch')

        <div class="space-y-2">
            <x-input-label for="name" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" :value="__('System Identifier')" />
            <x-text-input id="name" name="name" type="text" class="block w-full py-4 px-6 bg-slate-50 border-none rounded-2xl text-slate-700 font-bold focus:ring-4 focus:ring-[#00ADC5]/10" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="space-y-2">
            <x-input-label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1" :value="__('Corporate Email')" />
            <x-text-input id="email" name="email" type="email" class="block w-full py-4 px-6 bg-slate-50 border-none rounded-2xl text-slate-700 font-bold focus:ring-4 focus:ring-[#00ADC5]/10" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-amber-50 rounded-2xl border border-amber-100">
                    <p class="text-[10px] font-black uppercase tracking-widest text-amber-600 mb-2">
                        Verification Pending
                    </p>

                    <button form="send-verification" class="text-xs font-bold text-amber-700 underline hover:text-amber-800 transition-colors">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-emerald-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="px-8 py-4 bg-[#00ADC5] rounded-2xl text-xs font-black text-white uppercase tracking-[0.2em] shadow-lg shadow-cyan-200 hover:bg-[#007A8A] transition-all active:scale-95">
                {{ __('Update Details') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-[10px] font-black uppercase tracking-widest text-emerald-500"
                >{{ __('Identity Updated Successfully.') }}</p>
            @endif
        </div>
    </form>
</section>