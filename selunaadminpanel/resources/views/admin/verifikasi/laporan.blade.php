@extends('layouts.admin')

@section('header')
<div class="flex flex-col gap-4 lg:flex-row lg:justify-between lg:items-center px-4 lg:px-8 pt-6 lg:pt-8 pb-4">
    <div>
        <h2 class="text-2xl font-bold text-[#1B3022]">Verifikasi Laporan Kampanye</h2>
        <p class="text-gray-500 text-sm">Review dan setujui laporan pertanggungjawaban dari fundraiser.</p>
    </div>
    <div class="flex flex-wrap items-center gap-3">
        <form action="{{ route('admin.verify.report.index') }}" method="GET" class="flex items-center gap-3 bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-2 pr-3">
                <label for="sort" class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Urutan:</label>
                <select name="sort" onchange="this.form.submit()" class="text-xs font-bold text-gray-700 bg-transparent border-none focus:ring-0 cursor-pointer">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>A-Z (Nama File)</option>
                    <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Z-A (Nama File)</option>
                </select>
            </div>
        </form>
    </div>
</div>
@endsection

@section('content')
<div class="px-4 lg:px-8 pb-8">
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kampanye & Fundraiser</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">File Laporan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tgl Upload</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($reports as $report)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-gray-900">{{ $report->campaign->title }}</span>
                            <span class="text-xs text-gray-500">{{ $report->campaign->user->username }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-red-50 rounded-lg">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <a href="{{ $report->url }}" target="_blank" class="text-sm font-medium text-blue-600 hover:underline">{{ $report->original_name }}</a>
                                <span class="text-xs text-gray-400">{{ $report->file_size_formatted }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $report->created_at->format('d M Y, H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($report->status === 'verified')
                            <span class="px-3 py-1 text-xs font-medium text-emerald-600 bg-emerald-50 rounded-full">Diverifikasi</span>
                        @elseif($report->status === 'rejected')
                            <span class="px-3 py-1 text-xs font-medium text-rose-600 bg-rose-50 rounded-full">Ditolak</span>
                        @else
                            <span class="px-3 py-1 text-xs font-medium text-amber-600 bg-amber-50 rounded-full">Menunggu</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($report->status === 'pending')
                        <div class="flex items-center gap-2">
                            <form action="{{ route('admin.verify.report.verify', $report->id) }}" method="POST" data-confirm data-confirm-title="Setujui Laporan?" data-confirm-body="Anda akan menyetujui laporan ini. Tindakan ini tidak dapat dibatalkan." data-confirm-icon="✅" data-confirm-ok="Ya, Setujui">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-emerald-500 hover:bg-emerald-600 rounded-lg transition-colors" data-action="update">
                                    Setujui
                                </button>
                            </form>
                            <button onclick="openRejectModal({{ $report->id }})" class="px-3 py-1.5 text-xs font-bold text-white bg-rose-500 hover:bg-rose-600 rounded-lg transition-colors">
                                Tolak
                            </button>
                        </div>
                        @else
                             <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        Belum ada laporan yang perlu diverifikasi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>{{-- end px wrapper --}}

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl">
        <h3 class="text-xl font-bold text-gray-900 mb-2">Tolak Laporan</h3>
        <p class="text-gray-500 text-sm mb-6">Berikan alasan penolakan laporan ini agar fundraiser dapat memperbaikinya.</p>
        
        <form id="rejectForm" method="POST" data-seluna>
            @csrf
            <textarea name="note" rows="4" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl mb-6 focus:ring-2 focus:ring-rose-500 focus:outline-none transition-all" placeholder="Contoh: File tidak terbaca atau data tidak valid..."></textarea>
            
            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()" class="flex-1 py-3 text-sm font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all">Batal</button>
                <button type="submit" class="flex-1 py-3 text-sm font-bold text-white bg-rose-500 hover:bg-rose-600 rounded-xl shadow-lg shadow-rose-200 transition-all" data-action="update">Konfirmasi Tolak</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(id) {
        document.getElementById('rejectForm').action = `/admin/verifikasi/laporan/${id}/reject`;
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endsection
