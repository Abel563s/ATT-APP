<x-app-layout>
    <div class="py-6 space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}"
                    class="w-12 h-12 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-900 transition-all group">
                    <i data-lucide="arrow-left" class="w-5 h-5 group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <nav class="flex text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">
                        <span class="text-slate-600 italic">System Infrastructure</span>
                    </nav>
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">Core Identifiers</h2>
                    <p class="text-slate-500 font-medium italic mt-1">Manage system-wide attendance protocols and status
                        indicators.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-8">
                <!-- System Identifiers -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                        <h3
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <i data-lucide="settings-2" class="w-4 h-4"></i>
                            General System Identifiers
                        </h3>
                    </div>
                    <form action="{{ route('admin.settings.update') }}" method="POST" class="p-8 space-y-6">
                        @csrf
                        @foreach($settings as $setting)
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ $setting->label }}</label>
                                <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}"
                                    class="w-full rounded-2xl border-none bg-slate-50 p-4 font-bold text-slate-700 focus:ring-4 focus:ring-[#00ADC5]/10 text-sm">
                            </div>
                        @endforeach
                        <div class="pt-4">
                            <button type="submit"
                                class="inline-flex items-center px-8 py-4 bg-[#00ADC5] rounded-2xl text-[10px] font-black text-white uppercase tracking-widest shadow-xl shadow-cyan-100 hover:bg-[#007A8A] transition-all active:scale-95">
                                <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                                Synchronize System State
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Attendance Codes List -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                        <h3
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <i data-lucide="hash" class="w-4 h-4"></i>
                            Operational Protocols (Attendance Codes)
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <tbody class="divide-y divide-slate-100">
                                @foreach($codes as $code)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-8 py-6">
                                            <div class="flex items-center gap-6">
                                                <div
                                                    class="w-12 h-12 rounded-2xl {{ $code->bg_color }} {{ $code->text_color }} ring-1 {{ $code->ring_color }} flex items-center justify-center font-black text-xs shadow-sm shadow-slate-100">
                                                    {{ $code->code }}
                                                </div>
                                                <div class="flex flex-col">
                                                    <span
                                                        class="font-black text-slate-800 tracking-tight leading-none mb-1">{{ $code->label }}</span>
                                                    <span
                                                        class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $code->description }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <button type="button" onclick="editCode({{ json_encode($code) }})"
                                                class="px-5 py-2.5 bg-white border border-slate-200 rounded-[1.25rem] text-[10px] font-black text-slate-400 uppercase tracking-widest hover:border-[#00ADC5] hover:text-[#00ADC5] transition-all shadow-sm">
                                                Reconfigure Node
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-6">
                <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl">
                    <div class="relative z-10 space-y-6">
                        <div
                            class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-cyan-400">
                            <i data-lucide="info" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-white/90 uppercase tracking-[0.2em] mb-3">System Status
                            </h4>
                            <p class="text-xs text-white/50 leading-relaxed font-medium">
                                Modifying core identifiers triggers a global state synchronization. All active
                                attendance grids will adopt these visual protocols immediately.
                            </p>
                        </div>
                        <ul class="space-y-3 pt-4 border-t border-white/5">
                            <li
                                class="flex items-center gap-3 text-[10px] font-bold text-white/40 uppercase tracking-tight">
                                <span class="w-1 h-1 rounded-full bg-cyan-400"></span>
                                Visual Identity Sync
                            </li>
                            <li
                                class="flex items-center gap-3 text-[10px] font-bold text-white/40 uppercase tracking-tight">
                                <span class="w-1 h-1 rounded-full bg-cyan-400"></span>
                                Protocol Label Override
                            </li>
                        </ul>
                    </div>
                    <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editCodeModal" class="fixed inset-0 z-[100] overflow-y-auto hidden" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity z-[100]" aria-hidden="true"
                onclick="hideEditModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-middle bg-white rounded-[3rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/20 relative z-[110]">
                <form id="editCodeForm" method="POST" action="">
                    @csrf
                    @method('PUT')

                    <div class="bg-white px-10 pt-12 pb-8">
                        <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-50">
                            <div id="modalIconPreview"
                                class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-sm shadow-lg">
                                CODE
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900 tracking-tight" id="modal-title">Configure
                                    Protocol</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                                    Editing: <span id="modalCodeName" class="text-[#00ADC5]">P</span></p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Display
                                    Label</label>
                                <input type="text" name="label" id="edit-label" required
                                    class="w-full rounded-2xl border-none bg-slate-50 p-4 font-bold text-slate-700 focus:ring-4 focus:ring-[#00ADC5]/10 text-sm">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">BG
                                        Class (Tailwind)</label>
                                    <input type="text" name="bg_color" id="edit-bg" required
                                        class="w-full rounded-xl border-none bg-slate-50 p-3 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/20 text-xs">
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Text
                                        Class</label>
                                    <input type="text" name="text_color" id="edit-text" required
                                        class="w-full rounded-xl border-none bg-slate-50 p-3 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/20 text-xs">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-10 py-10 flex flex-col gap-3">
                        <button type="submit"
                            class="w-full py-4 bg-[#00ADC5] rounded-2xl text-[10px] font-black text-white uppercase tracking-widest shadow-xl shadow-cyan-100 hover:bg-[#007A8A] transition-all active:scale-95">
                            Update Operational Protocol
                        </button>
                        <button type="button" onclick="hideEditModal()"
                            class="w-full py-4 bg-white border border-slate-200 rounded-2xl text-[10px] font-black text-slate-400 uppercase tracking-widest hover:bg-slate-100 transition-all">
                            Abort changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editCode(code) {
            const form = document.getElementById('editCodeForm');
            form.action = `/admin/settings/codes/${code.id}`;

            document.getElementById('edit-label').value = code.label;
            document.getElementById('edit-bg').value = code.bg_color;
            document.getElementById('edit-text').value = code.text_color;

            document.getElementById('modalCodeName').textContent = code.code;
            document.getElementById('modalIconPreview').textContent = code.code;
            document.getElementById('modalIconPreview').className = `w-14 h-14 rounded-2xl flex items-center justify-center font-black text-sm shadow-lg ${code.bg_color} ${code.text_color}`;

            document.getElementById('editCodeModal').classList.remove('hidden');
        }

        function hideEditModal() {
            document.getElementById('editCodeModal').classList.add('hidden');
        }
    </script>
</x-app-layout>