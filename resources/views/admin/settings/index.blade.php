<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Core Identifiers</h2>
                <p class="text-slate-500 text-sm font-medium">Manage system-wide attendance protocols and status indicators</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-8 space-y-6">
                <!-- System Settings -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2 bg-slate-50/30">
                        <i data-lucide="settings-2" class="w-4 h-4 text-slate-400"></i>
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">General Settings</h3>
                    </div>
                    <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6 space-y-5">
                        @csrf
                        @foreach($settings as $setting)
                            <div class="space-y-1.5">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ $setting->label }}</label>
                                <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}"
                                    class="w-full rounded-xl border-none bg-slate-50 p-3 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 text-sm">
                            </div>
                        @endforeach
                        <div class="pt-2">
                            <button type="submit"
                                class="inline-flex items-center px-6 py-2.5 bg-[#00ADC5] rounded-xl text-[10px] font-black text-white uppercase tracking-widest hover:bg-[#007A8A] transition-all active:scale-95 shadow-lg shadow-cyan-100">
                                <i data-lucide="save" class="w-3.5 h-3.5 mr-2"></i>
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Attendance Codes -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                        <div class="flex items-center gap-2">
                            <i data-lucide="hash" class="w-4 h-4 text-slate-400"></i>
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Attendance Codes</h3>
                        </div>
                        <button type="button" onclick="showCreateModal()"
                            class="px-4 py-2 bg-[#00ADC5] rounded-xl text-[10px] font-black text-white uppercase tracking-widest hover:bg-[#007A8A] transition-all shadow-sm flex items-center gap-2">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            Add Code
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <tbody class="divide-y divide-slate-100">
                                @foreach($codes as $code)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-6 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl {{ $code->bg_color }} {{ $code->text_color }} ring-1 {{ $code->ring_color }} flex items-center justify-center font-black text-xs shadow-sm">
                                                    {{ $code->code }}
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="font-black text-slate-800 tracking-tight leading-none mb-0.5 text-sm">{{ $code->label }}</span>
                                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">{{ $code->description }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" onclick="editCode({{ json_encode($code) }})"
                                                    class="p-2 bg-white border border-slate-200 rounded-lg text-slate-400 hover:border-[#00ADC5] hover:text-[#00ADC5] transition-all shadow-sm"
                                                    title="Edit">
                                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                                </button>
                                                <form action="{{ route('admin.settings.destroy_code', $code->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to remove this code?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 bg-white border border-slate-200 rounded-lg text-slate-400 hover:border-red-500 hover:text-red-500 transition-all shadow-sm"
                                                        title="Delete">
                                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="bg-slate-900 rounded-xl p-6 text-white relative overflow-hidden shadow-xl">
                    <div class="relative z-10 space-y-4">
                        <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-cyan-400">
                            <i data-lucide="info" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-white/90 uppercase tracking-widest mb-2">System Status</h4>
                            <p class="text-xs text-white/50 leading-relaxed font-medium">
                                Modifying core identifiers triggers a global state synchronization. All active attendance grids will adopt these visual protocols immediately.
                            </p>
                        </div>
                        <ul class="space-y-2 pt-3 border-t border-white/5">
                            <li class="flex items-center gap-2 text-[10px] font-bold text-white/40 uppercase tracking-tight">
                                <span class="w-1 h-1 rounded-full bg-cyan-400"></span>
                                Visual Identity Sync
                            </li>
                            <li class="flex items-center gap-2 text-[10px] font-bold text-white/40 uppercase tracking-tight">
                                <span class="w-1 h-1 rounded-full bg-cyan-400"></span>
                                Protocol Label Override
                            </li>
                        </ul>
                    </div>
                    <div class="absolute -right-16 -bottom-16 w-48 h-48 bg-cyan-500/10 rounded-full blur-3xl"></div>
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
                class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/20 relative z-[110]">
                <form id="editCodeForm" method="POST" action="">
                    @csrf
                    @method('PUT')

                    <div class="bg-white px-8 pt-8 pb-6">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                            <div id="modalIconPreview"
                                class="w-12 h-12 rounded-xl flex items-center justify-center font-black text-sm shadow-lg">
                                CODE
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900 tracking-tight" id="modal-title">Configure Protocol</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                                    Editing: <span id="modalCodeName" class="text-[#00ADC5]">P</span></p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div class="space-y-1.5">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Display Label</label>
                                <input type="text" name="label" id="edit-label" required
                                    class="w-full rounded-xl border-none bg-slate-50 p-3 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/20 text-sm">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">BG Class</label>
                                    <input type="text" name="bg_color" id="edit-bg" required
                                        class="w-full rounded-lg border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/20 text-xs">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Text Class</label>
                                    <input type="text" name="text_color" id="edit-text" required
                                        class="w-full rounded-lg border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/20 text-xs">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-8 py-6 flex flex-col gap-2">
                        <button type="submit"
                            class="w-full py-3 bg-[#00ADC5] rounded-xl text-[10px] font-black text-white uppercase tracking-widest hover:bg-[#007A8A] transition-all active:scale-95">
                            Update Protocol
                        </button>
                        <button type="button" onclick="hideEditModal()"
                            class="w-full py-3 bg-white border border-slate-200 rounded-xl text-[10px] font-black text-slate-400 uppercase tracking-widest hover:bg-slate-100 transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createCodeModal" class="fixed inset-0 z-[100] overflow-y-auto hidden" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity z-[100]" aria-hidden="true"
                onclick="hideCreateModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/20 relative z-[110]">
                <form id="createCodeForm" method="POST" action="{{ route('admin.settings.store_code') }}">
                    @csrf

                    <div class="bg-white px-8 pt-8 pb-6">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                            <div class="w-12 h-12 rounded-xl bg-cyan-50 text-[#00ADC5] flex items-center justify-center">
                                <i data-lucide="plus" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900 tracking-tight">Create Protocol</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">New Core Identifier</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Code</label>
                                    <input type="text" name="code" required placeholder="e.g. V"
                                        class="w-full rounded-xl border-none bg-slate-50 p-3 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/20 text-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Label</label>
                                    <input type="text" name="label" required placeholder="e.g. Vacation"
                                        class="w-full rounded-xl border-none bg-slate-50 p-3 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/20 text-sm">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Description</label>
                                <input type="text" name="description" placeholder="Short description"
                                    class="w-full rounded-xl border-none bg-slate-50 p-3 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/20 text-sm">
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">BG Class</label>
                                    <input type="text" name="bg_color" required placeholder="bg-blue-100"
                                        class="w-full rounded-lg border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/20 text-xs">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Text Class</label>
                                    <input type="text" name="text_color" required placeholder="text-blue-600"
                                        class="w-full rounded-lg border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/20 text-xs">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Ring Class</label>
                                    <input type="text" name="ring_color" placeholder="ring-blue-200" value="ring-transparent"
                                        class="w-full rounded-lg border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/20 text-xs">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-8 py-6 flex flex-col gap-2">
                        <button type="submit"
                            class="w-full py-3 bg-[#00ADC5] rounded-xl text-[10px] font-black text-white uppercase tracking-widest hover:bg-[#007A8A] transition-all active:scale-95">
                            Create Protocol
                        </button>
                        <button type="button" onclick="hideCreateModal()"
                            class="w-full py-3 bg-white border border-slate-200 rounded-xl text-[10px] font-black text-slate-400 uppercase tracking-widest hover:bg-slate-100 transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showCreateModal() {
            document.getElementById('createCodeModal').classList.remove('hidden');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
        function hideCreateModal() {
            document.getElementById('createCodeModal').classList.add('hidden');
        }
        function editCode(code) {
            const form = document.getElementById('editCodeForm');
            form.action = `/admin/settings/codes/${code.id}`;
            document.getElementById('edit-label').value = code.label;
            document.getElementById('edit-bg').value = code.bg_color;
            document.getElementById('edit-text').value = code.text_color;
            document.getElementById('modalCodeName').textContent = code.code;
            document.getElementById('modalIconPreview').textContent = code.code;
            document.getElementById('modalIconPreview').className = `w-12 h-12 rounded-xl flex items-center justify-center font-black text-sm shadow-lg ${code.bg_color} ${code.text_color}`;
            document.getElementById('editCodeModal').classList.remove('hidden');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
        function hideEditModal() {
            document.getElementById('editCodeModal').classList.add('hidden');
        }
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
    </script>
</x-app-layout>
