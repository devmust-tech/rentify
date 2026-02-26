<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-x-3">
                <a href="{{ route('landlord.agreements.index') }}"
                   class="inline-flex items-center justify-center rounded-lg p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Agreement Details</h2>
                    <p class="mt-0.5 text-sm text-gray-500">Agreement with {{ $agreement->agent->name }}</p>
                </div>
            </div>
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

    {{-- Agreement Details Card --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 mb-8"
         x-data="agreementSignaturePad()">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-x-3">
                    <div class="rounded-lg bg-indigo-100 p-2">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">Agreement Information</h3>
                </div>
                <div class="flex items-center gap-x-3">
                    @if($agreement->signed_at)
                        <span class="inline-flex items-center gap-x-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Signed on {{ $agreement->signed_at->format('d/m/Y') }}
                        </span>
                    @endif
                    <x-status-badge :status="$agreement->status" />
                </div>
            </div>
        </div>

        <div class="px-6 py-6">
            <dl class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Agent</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $agreement->agent->name }}</dd>
                    <dd class="text-xs text-gray-500">{{ $agreement->agent->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Commission Rate</dt>
                    <dd class="mt-1 text-lg font-bold text-gray-900">{{ $agreement->commission_rate }}%</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Payment Day</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">Day {{ $agreement->payment_day }} of each month</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $agreement->start_date->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">End Date</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $agreement->end_date?->format('d/m/Y') ?? 'Open-ended' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1.5"><x-status-badge :status="$agreement->status" /></dd>
                </div>
            </dl>

            {{-- Terms & Conditions --}}
            @if($agreement->terms)
                <div class="mt-6 border-t border-gray-100 pt-6">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Terms & Conditions</h4>
                    <div class="rounded-lg bg-gray-50 p-4 ring-1 ring-gray-200 max-h-48 overflow-y-auto">
                        <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $agreement->terms }}</p>
                    </div>
                </div>
            @endif

            {{-- Signature Display (if already signed) --}}
            @if($agreement->signed_at && $agreement->signature_url)
                <div class="mt-6 border-t border-gray-100 pt-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 mb-1">Your Signature</h4>
                            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Signed on {{ $agreement->signed_at->format('d/m/Y \a\t H:i') }}
                            </span>
                        </div>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                            <img src="{{ asset('storage/' . $agreement->signature_url) }}" alt="Landlord signature" class="h-16">
                        </div>
                    </div>
                </div>
            @endif

            {{-- Signing Section (only for pending agreements) --}}
            @if($agreement->status->value === 'pending')
                <div class="mt-6 border-t border-gray-100 pt-6">
                    <template x-if="!showSigningArea">
                        <button @click="showSigningArea = true"
                                type="button"
                                class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            Sign Agreement
                        </button>
                    </template>

                    <template x-if="showSigningArea">
                        <form method="POST" action="{{ route('landlord.agreements.sign', $agreement) }}" enctype="multipart/form-data" @submit.prevent="submitSignature">
                            @csrf
                            <input type="hidden" name="signature" x-ref="signatureInput">

                            <div class="space-y-6">
                                {{-- Signature Method Toggle --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-3">Signature Method</label>
                                    <div class="flex gap-x-3">
                                        <button type="button" @click="signMethod = 'draw'; uploadPreview = null"
                                                :class="signMethod === 'draw' ? 'bg-indigo-600 text-white ring-indigo-600' : 'bg-white text-gray-700 ring-gray-300 hover:bg-gray-50'"
                                                class="inline-flex items-center gap-x-2 rounded-lg px-4 py-2 text-sm font-medium ring-1 transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            Draw Signature
                                        </button>
                                        <button type="button" @click="signMethod = 'upload'; clearCanvas()"
                                                :class="signMethod === 'upload' ? 'bg-indigo-600 text-white ring-indigo-600' : 'bg-white text-gray-700 ring-gray-300 hover:bg-gray-50'"
                                                class="inline-flex items-center gap-x-2 rounded-lg px-4 py-2 text-sm font-medium ring-1 transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            Upload Photo
                                        </button>
                                    </div>
                                </div>

                                {{-- Draw Signature Canvas --}}
                                <div x-show="signMethod === 'draw'" x-transition>
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

                                {{-- Upload Signature Photo --}}
                                <div x-show="signMethod === 'upload'" x-transition>
                                    <div class="flex justify-center rounded-xl border-2 border-dashed border-gray-300 px-6 py-8 transition hover:border-indigo-400">
                                        <div class="text-center">
                                            <template x-if="!uploadPreview">
                                                <div>
                                                    <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    <div class="mt-3 flex text-sm text-gray-600">
                                                        <label class="relative cursor-pointer rounded-md font-semibold text-indigo-600 hover:text-indigo-500">
                                                            <span>Upload signature photo</span>
                                                            <input type="file" name="signature_photo" accept="image/*" class="sr-only" @change="previewUpload($event)">
                                                        </label>
                                                    </div>
                                                    <p class="mt-1.5 text-xs text-gray-500">Take a photo of your handwritten signature (JPG, PNG up to 5MB)</p>
                                                </div>
                                            </template>
                                            <template x-if="uploadPreview">
                                                <div>
                                                    <img :src="uploadPreview" class="mx-auto h-24 rounded-lg border border-gray-200">
                                                    <button type="button" @click="removeUpload()" class="mt-2 text-sm font-medium text-red-600 hover:text-red-500">Remove</button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                {{-- Agreement Checkbox --}}
                                <div class="rounded-lg bg-gray-50 p-4 ring-1 ring-gray-200">
                                    <label class="flex items-start gap-x-3 cursor-pointer">
                                        <input type="checkbox" name="agree_terms" value="1" x-model="agreed"
                                               class="mt-0.5 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                                        <span class="text-sm text-gray-700 leading-relaxed">
                                            I have read and agree to the terms and conditions of this agent agreement.
                                            I understand that by signing, I am entering into a binding agreement with the agent for the management of my properties.
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
                                            :disabled="!agreed || (!hasSigned && !uploadPreview) || submitting"
                                            class="inline-flex items-center gap-x-2 rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                        <svg x-show="!submitting" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        <span x-text="submitting ? 'Signing...' : 'Sign & Accept Agreement'"></span>
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
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function agreementSignaturePad() {
            return {
                showSigningArea: false,
                signMethod: 'draw',
                isDrawing: false,
                hasSigned: false,
                agreed: false,
                submitting: false,
                uploadPreview: null,
                ctx: null,
                lastX: 0,
                lastY: 0,

                init() {
                    this.$nextTick(() => {
                        this.$watch('showSigningArea', (value) => {
                            if (value) {
                                this.$nextTick(() => this.initCanvas());
                            }
                        });
                    });
                },

                previewUpload(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    if (file.size > 5 * 1024 * 1024) { alert('File must be under 5MB'); return; }
                    const reader = new FileReader();
                    reader.onload = (e) => this.uploadPreview = e.target.result;
                    reader.readAsDataURL(file);
                },

                removeUpload() {
                    this.uploadPreview = null;
                    const input = this.$el.querySelector('input[name="signature_photo"]');
                    if (input) input.value = '';
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
                    if (!this.agreed || this.submitting) return;
                    if (this.signMethod === 'draw' && !this.hasSigned) return;
                    if (this.signMethod === 'upload' && !this.uploadPreview) return;
                    this.submitting = true;
                    if (this.signMethod === 'draw') {
                        const dataUrl = this.$refs.canvas.toDataURL('image/png');
                        this.$refs.signatureInput.value = dataUrl;
                    }
                    this.$el.submit();
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
