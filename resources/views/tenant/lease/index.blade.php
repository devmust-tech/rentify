<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">My Lease</h2>
        </div>
    </x-slot>

    {{-- Success / Error Messages --}}
    @if(session('success'))
        <div class="mb-6 rounded-xl bg-emerald-50 p-4 ring-1 ring-emerald-200">
            <div class="flex items-center gap-x-3">
                <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-xl bg-red-50 p-4 ring-1 ring-red-200">
            <div class="flex items-center gap-x-3">
                <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Active Lease --}}
    @if($activeLease)
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 mb-8">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-x-3">
                        <div class="rounded-lg bg-emerald-100 p-2">
                            <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900">Active Lease</h3>
                    </div>
                    <div class="flex items-center gap-x-3">
                        @if($activeLease->signed_at)
                            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Signed on {{ $activeLease->signed_at->format('d/m/Y') }}
                            </span>
                        @endif
                        <x-status-badge :status="$activeLease->status" />
                    </div>
                </div>
            </div>

            <div class="px-6 py-6">
                <dl class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Property</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $activeLease->unit->property->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Unit</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $activeLease->unit->unit_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1"><x-status-badge :status="$activeLease->status" /></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $activeLease->start_date->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">End Date</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $activeLease->end_date?->format('d/m/Y') ?? 'Open-ended' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Monthly Rent</dt>
                        <dd class="mt-1 text-lg font-bold text-gray-900">KSh {{ number_format($activeLease->rent_amount, 2) }}</dd>
                    </div>
                </dl>

                {{-- Signature Display --}}
                @if($activeLease->signed_at && $activeLease->signature_url)
                    <div class="mt-6 border-t border-gray-100 pt-6">
                        <dt class="text-sm font-medium text-gray-500 mb-3">Your Signature</dt>
                        <div class="inline-block rounded-lg border border-gray-200 bg-gray-50 p-3">
                            <img src="{{ Storage::url($activeLease->signature_url) }}" alt="Tenant signature" class="h-20">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Pending Leases (Awaiting Signature) --}}
    @if($pendingLeases->count() > 0)
        @foreach($pendingLeases as $pendingLease)
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 mb-8"
                 x-data="signaturePad('{{ $pendingLease->id }}')">
                <div class="border-b border-gray-100 bg-amber-50/50 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-x-3">
                            <div class="rounded-lg bg-amber-100 p-2">
                                <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">Lease Awaiting Your Signature</h3>
                                <p class="text-sm text-gray-500">Review the details below and sign to activate your tenancy</p>
                            </div>
                        </div>
                        <x-status-badge :status="$pendingLease->status" />
                    </div>
                </div>

                <div class="px-6 py-6">
                    {{-- Lease Details --}}
                    <dl class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Property</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $pendingLease->unit->property->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Unit</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $pendingLease->unit->unit_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Monthly Rent</dt>
                            <dd class="mt-1 text-lg font-bold text-gray-900">KSh {{ number_format($pendingLease->rent_amount, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $pendingLease->start_date->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">End Date</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $pendingLease->end_date?->format('d/m/Y') ?? 'Open-ended' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Deposit</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">KSh {{ number_format($pendingLease->deposit, 2) }}</dd>
                        </div>
                    </dl>

                    {{-- Terms & Conditions --}}
                    @if($pendingLease->terms)
                        <div class="mt-6 border-t border-gray-100 pt-6">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Terms & Conditions</h4>
                            <div class="rounded-lg bg-gray-50 p-4 ring-1 ring-gray-200 max-h-48 overflow-y-auto">
                                <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $pendingLease->terms }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Signing Section --}}
                    <div class="mt-6 border-t border-gray-100 pt-6">
                        <template x-if="!showSigningArea">
                            <button @click="showSigningArea = true"
                                    type="button"
                                    class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                Sign Now
                            </button>
                        </template>

                        <template x-if="showSigningArea">
                            <form method="POST" action="{{ route('tenant.lease.sign', $pendingLease) }}" @submit.prevent="submitSignature">
                                @csrf
                                <input type="hidden" name="signature" x-ref="signatureInput">

                                <div class="space-y-6">
                                    {{-- Signature Canvas --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-3">Draw Your Signature</label>
                                        <div class="inline-block rounded-xl border-2 border-dashed border-gray-300 bg-white p-1"
                                             :class="{ 'border-indigo-400 ring-2 ring-indigo-100': isDrawing }">
                                            <canvas x-ref="canvas"
                                                    width="400"
                                                    height="200"
                                                    class="block cursor-crosshair rounded-lg touch-none"
                                                    @mousedown="startDrawing($event)"
                                                    @mousemove="draw($event)"
                                                    @mouseup="stopDrawing()"
                                                    @mouseleave="stopDrawing()"
                                                    @touchstart.prevent="startDrawingTouch($event)"
                                                    @touchmove.prevent="drawTouch($event)"
                                                    @touchend.prevent="stopDrawing()">
                                            </canvas>
                                        </div>
                                        <div class="mt-2 flex items-center gap-x-3">
                                            <button type="button" @click="clearCanvas()"
                                                    class="inline-flex items-center gap-x-1.5 rounded-lg bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                Clear
                                            </button>
                                            <span x-show="!hasSigned" class="text-sm text-gray-400">Sign above using your mouse or finger</span>
                                            <span x-show="hasSigned" x-cloak class="text-sm text-emerald-600 font-medium">Signature captured</span>
                                        </div>
                                    </div>

                                    {{-- Agreement Checkbox --}}
                                    <div class="rounded-lg bg-gray-50 p-4 ring-1 ring-gray-200">
                                        <label class="flex items-start gap-x-3 cursor-pointer">
                                            <input type="checkbox" name="agree_terms" value="1" x-model="agreed"
                                                   class="mt-0.5 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                            <span class="text-sm text-gray-700 leading-relaxed">
                                                I have read and agree to the terms and conditions of this lease agreement.
                                                I understand that by signing, I am entering into a binding rental agreement for the property and unit described above.
                                            </span>
                                        </label>
                                    </div>

                                    {{-- Validation Errors --}}
                                    @if($errors->any())
                                        <div class="rounded-lg bg-red-50 p-4 ring-1 ring-red-200">
                                            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    {{-- Submit Buttons --}}
                                    <div class="flex items-center gap-x-3">
                                        <button type="submit"
                                                :disabled="!agreed || !hasSigned || submitting"
                                                class="inline-flex items-center gap-x-2 rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                            <svg x-show="!submitting" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            <span x-text="submitting ? 'Signing...' : 'Sign & Accept Lease'"></span>
                                        </button>
                                        <button type="button" @click="showSigningArea = false; clearCanvas()"
                                                class="rounded-lg bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </template>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    {{-- Previous Leases (exclude active and pending) --}}
    @php $previousLeases = $leases->reject(fn($l) => in_array($l->status->value, ['active', 'pending'])); @endphp
    @if($previousLeases->count() > 0)
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <div class="flex items-center gap-x-3">
                    <div class="rounded-lg bg-gray-100 p-2">
                        <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">Previous Leases</h3>
                </div>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Property / Unit</th>
                        <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Period</th>
                        <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($previousLeases as $lease)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $lease->unit->property->name }} / {{ $lease->unit->unit_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $lease->start_date->format('d/m/Y') }} - {{ $lease->end_date?->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-sm"><x-status-badge :status="$lease->status" /></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- No leases at all --}}
    @if($leases->count() === 0)
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <h3 class="mt-3 text-sm font-semibold text-gray-900">No leases</h3>
                <p class="mt-1 text-sm text-gray-500">You do not have any lease agreements yet.</p>
            </div>
        </div>
    @endif

    @push('scripts')
    <script>
        function signaturePad(leaseId) {
            return {
                showSigningArea: false,
                isDrawing: false,
                hasSigned: false,
                agreed: false,
                submitting: false,
                ctx: null,
                lastX: 0,
                lastY: 0,

                init() {
                    this.$nextTick(() => {
                        // Canvas will be initialized when signing area is shown
                        this.$watch('showSigningArea', (value) => {
                            if (value) {
                                this.$nextTick(() => this.initCanvas());
                            }
                        });
                    });
                },

                initCanvas() {
                    const canvas = this.$refs.canvas;
                    if (!canvas) return;
                    this.ctx = canvas.getContext('2d');
                    this.ctx.strokeStyle = '#1e293b';
                    this.ctx.lineWidth = 2.5;
                    this.ctx.lineCap = 'round';
                    this.ctx.lineJoin = 'round';
                },

                getPos(e) {
                    const canvas = this.$refs.canvas;
                    const rect = canvas.getBoundingClientRect();
                    const scaleX = canvas.width / rect.width;
                    const scaleY = canvas.height / rect.height;
                    return {
                        x: (e.clientX - rect.left) * scaleX,
                        y: (e.clientY - rect.top) * scaleY
                    };
                },

                getTouchPos(e) {
                    const canvas = this.$refs.canvas;
                    const rect = canvas.getBoundingClientRect();
                    const touch = e.touches[0];
                    const scaleX = canvas.width / rect.width;
                    const scaleY = canvas.height / rect.height;
                    return {
                        x: (touch.clientX - rect.left) * scaleX,
                        y: (touch.clientY - rect.top) * scaleY
                    };
                },

                startDrawing(e) {
                    this.isDrawing = true;
                    const pos = this.getPos(e);
                    this.lastX = pos.x;
                    this.lastY = pos.y;
                    this.ctx.beginPath();
                    this.ctx.moveTo(pos.x, pos.y);
                },

                draw(e) {
                    if (!this.isDrawing) return;
                    const pos = this.getPos(e);
                    this.ctx.lineTo(pos.x, pos.y);
                    this.ctx.stroke();
                    this.ctx.beginPath();
                    this.ctx.moveTo(pos.x, pos.y);
                    this.lastX = pos.x;
                    this.lastY = pos.y;
                    this.hasSigned = true;
                },

                startDrawingTouch(e) {
                    this.isDrawing = true;
                    const pos = this.getTouchPos(e);
                    this.lastX = pos.x;
                    this.lastY = pos.y;
                    this.ctx.beginPath();
                    this.ctx.moveTo(pos.x, pos.y);
                },

                drawTouch(e) {
                    if (!this.isDrawing) return;
                    const pos = this.getTouchPos(e);
                    this.ctx.lineTo(pos.x, pos.y);
                    this.ctx.stroke();
                    this.ctx.beginPath();
                    this.ctx.moveTo(pos.x, pos.y);
                    this.lastX = pos.x;
                    this.lastY = pos.y;
                    this.hasSigned = true;
                },

                stopDrawing() {
                    this.isDrawing = false;
                },

                clearCanvas() {
                    if (this.ctx) {
                        this.ctx.clearRect(0, 0, this.$refs.canvas.width, this.$refs.canvas.height);
                    }
                    this.hasSigned = false;
                },

                submitSignature() {
                    if (!this.hasSigned || !this.agreed || this.submitting) return;
                    this.submitting = true;
                    const dataUrl = this.$refs.canvas.toDataURL('image/png');
                    this.$refs.signatureInput.value = dataUrl;
                    this.$el.submit();
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
