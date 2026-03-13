@extends('main')

@section('body_class', 'scroll-mode')

@section('content')
<div class="min-h-screen bg-[#FBFBFD] p-6 md:p-12">
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-100 text-gray-600 text-sm font-medium rounded-2xl hover:bg-gray-50 transition-all duration-300 group">
                <i class="fas fa-home mr-2 text-blue-500"></i> Начало
            </a>
        </div>

        <div class="bg-white border border-gray-100 rounded-[2rem] p-6 md:p-8 shadow-sm">
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-[#1D1D1F]">STT Debug</h1>
            <p class="text-gray-500 mt-2 text-sm">Изпраща текст директно към <strong>/api/stt/ingest</strong> за конкретна сесия.</p>

            <form id="stt-debug-form" class="mt-6 space-y-5">
                <div>
                    <label for="session_id" class="block text-sm font-semibold text-gray-700 mb-2">Session ID</label>
                    <input id="session_id" name="session_id" type="text" required
                        class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-300"
                        placeholder="напр. 550e8400-e29b-41d4-a716-446655440000">
                </div>

                <div>
                    <label for="text" class="block text-sm font-semibold text-gray-700 mb-2">Text / Transcript</label>
                    <textarea id="text" name="text" rows="5" required
                        class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-300"
                        placeholder="Въведи текст за ingest..."></textarea>
                </div>

                <div>
                    <label for="token" class="block text-sm font-semibold text-gray-700 mb-2">STT Token</label>
                    <input id="token" name="token" type="text"
                        class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-300"
                        placeholder="X-STT-TOKEN / bearer token">
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" id="submit-btn"
                        class="px-5 py-3 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition-colors">
                        Send to /api/stt/ingest
                    </button>
                    <span id="status" class="text-sm text-gray-500"></span>
                </div>
            </form>

            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Response</label>
                <pre id="response" class="bg-gray-50 border border-gray-100 rounded-xl p-4 text-xs text-gray-700 overflow-auto min-h-[120px]"></pre>
            </div>
        </div>
    </div>
</div>

<script>
    const form = document.getElementById('stt-debug-form');
    const statusEl = document.getElementById('status');
    const responseEl = document.getElementById('response');
    const submitBtn = document.getElementById('submit-btn');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const sessionId = document.getElementById('session_id').value.trim();
        const text = document.getElementById('text').value.trim();
        const token = document.getElementById('token').value.trim();

        if (!sessionId || !text) {
            statusEl.textContent = 'Session ID и Text са задължителни.';
            statusEl.className = 'text-sm text-rose-600';
            return;
        }

        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
        statusEl.textContent = 'Изпращане...';
        statusEl.className = 'text-sm text-gray-500';

        try {
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            };

            if (token) {
                headers['X-STT-TOKEN'] = token;
            }

            const response = await fetch('/api/stt/ingest', {
                method: 'POST',
                headers,
                body: JSON.stringify({
                    session_id: sessionId,
                    text,
                    token
                })
            });

            const payload = await response.json().catch(() => ({
                ok: false,
                message: 'Invalid JSON response'
            }));

            responseEl.textContent = JSON.stringify(payload, null, 2);

            if (response.ok) {
                statusEl.textContent = 'Успешно изпратено.';
                statusEl.className = 'text-sm text-emerald-600';
            } else {
                statusEl.textContent = `Грешка (${response.status}).`;
                statusEl.className = 'text-sm text-rose-600';
            }
        } catch (error) {
            responseEl.textContent = JSON.stringify({
                ok: false,
                message: error?.message || 'Request failed'
            }, null, 2);
            statusEl.textContent = 'Network error.';
            statusEl.className = 'text-sm text-rose-600';
        } finally {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-60', 'cursor-not-allowed');
        }
    });
</script>
@endsection
