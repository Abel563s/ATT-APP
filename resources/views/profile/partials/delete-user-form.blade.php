<section class="space-y-5">
    <header>
        <h2 class="text-lg font-black text-rose-900 tracking-tight">
            {{ __('Account Termination') }}
        </h2>
        <p class="mt-1 text-xs font-bold text-rose-400 uppercase tracking-widest">
            {{ __('Once your profile node is purged, all associated data will be permanently decommissioned.') }}
        </p>
    </header>

    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-6 py-2.5 bg-rose-600 rounded-xl text-[10px] font-black text-white uppercase tracking-widest shadow-lg shadow-rose-200 hover:bg-rose-700 transition-all active:scale-95">
        {{ __('Purge Session') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
            @csrf
            @method('delete')

            <h2 class="text-xl font-black text-slate-900 tracking-tight">
                {{ __('Confirm Termination Protocol') }}
            </h2>

            <p class="mt-3 text-sm font-medium text-slate-500 leading-relaxed">
                {{ __('This action is irreversible. Please input your secure access key to confirm the complete decommissioning of your identity node.') }}
            </p>

            <div class="mt-6 space-y-2">
                <x-input-label for="password" value="{{ __('Access Key') }}" class="sr-only" />
                <x-text-input id="password" name="password" type="password"
                    class="block w-full py-2.5 px-3 bg-slate-50 border-none rounded-xl text-slate-700 font-bold focus:ring-2 focus:ring-rose-500/10 text-sm"
                    placeholder="{{ __('Access Key') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1" />
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-6 py-2.5 bg-white border-2 border-slate-100 rounded-xl text-[10px] font-black text-slate-400 uppercase tracking-widest hover:bg-slate-50 transition-all">
                    {{ __('Abort') }}
                </button>

                <button type="submit"
                    class="px-6 py-2.5 bg-rose-600 rounded-xl text-[10px] font-black text-white uppercase tracking-widest shadow-xl shadow-rose-200 hover:bg-rose-700 transition-all active:scale-95">
                    {{ __('Confirm Purge') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
