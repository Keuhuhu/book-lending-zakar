{{--
    Toast Notification Component
    Letakkan @include('components.toast') di dalam layouts/admin.blade.php
    tepat sebelum tag </body>
--}}

<div
    x-data="{
        toasts: [],
        add(msg, type = 'success') {
            const id = Date.now();
            this.toasts.push({ id, msg, type });
            setTimeout(() => this.remove(id), 4000);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }"
    x-on:toast.window="add($event.detail.msg, $event.detail.type)"
    class="fixed bottom-6 right-6 z-50 flex flex-col gap-3"
    style="min-width: 300px;"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            :class="{
                'bg-green-500': toast.type === 'success',
                'bg-red-500':   toast.type === 'error',
                'bg-yellow-500': toast.type === 'warning',
                'bg-blue-500':  toast.type === 'info',
            }"
            class="flex items-center gap-3 text-white px-5 py-3.5 rounded-2xl shadow-xl"
        >
            <span x-show="toast.type === 'success'"><i class="fas fa-check-circle text-lg"></i></span>
            <span x-show="toast.type === 'error'"><i class="fas fa-exclamation-circle text-lg"></i></span>
            <span x-show="toast.type === 'warning'"><i class="fas fa-triangle-exclamation text-lg"></i></span>
            <span x-show="toast.type === 'info'"><i class="fas fa-info-circle text-lg"></i></span>
            <span class="text-sm font-medium flex-1" x-text="toast.msg"></span>
            <button @click="remove(toast.id)" class="opacity-70 hover:opacity-100 ml-2">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
    </template>
</div>

{{-- Auto-trigger toast dari session Laravel --}}
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        window.dispatchEvent(new CustomEvent('toast', {
            detail: { msg: '{{ session('success') }}', type: 'success' }
        }));
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        window.dispatchEvent(new CustomEvent('toast', {
            detail: { msg: '{{ session('error') }}', type: 'error' }
        }));
    });
</script>
@endif

@if(session('warning'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        window.dispatchEvent(new CustomEvent('toast', {
            detail: { msg: '{{ session('warning') }}', type: 'warning' }
        }));
    });
</script>
@endif