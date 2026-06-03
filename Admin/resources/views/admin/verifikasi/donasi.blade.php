@extends('layouts.admin')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-[#1B3022]">Verifikasi Donasi Manual</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola dan verifikasi bukti transfer dari donatur.</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 px-3 py-1 bg-green-50 text-green-600 rounded-full border border-green-100 animate-pulse">
                <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                <span class="text-[10px] font-bold uppercase tracking-wider">Live Monitoring</span>
            </div>
            <form action="{{ route('admin.verify.donation.index') }}" method="GET" class="flex items-center gap-2">
                <label for="per_page" class="text-sm text-gray-600 font-medium">Tampilkan:</label>
                <select name="per_page" id="per_page" onchange="this.form.submit()" class="text-sm border-gray-200 rounded-lg focus:ring-[#2D5A27] focus:border-[#2D5A27]">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Tampilkan Semua</option>
                </select>
            </form>
            <span class="bg-[#2D5A27] text-white px-4 py-1.5 rounded-full text-sm font-medium shadow-sm">
                {{ $donations instanceof \Illuminate\Pagination\LengthAwarePaginator ? $donations->total() : $donations->count() }} Data
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-4 animate__animated animate__fadeIn" role="alert">
            <span class="block sm:inline font-medium">{{ session('success') }}</span>
        </div>
    @endif
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Donatur & Kampanye</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Bukti</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Status</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($donations as $d)
                <tr class="hover:bg-gray-50 transition-colors {{ $d->status == 'pending' ? 'bg-amber-50/30' : '' }}">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-800">{{ $d->user->name ?? 'Anonim' }}</div>
                        <div class="text-[11px] text-gray-500 mt-0.5">{{ $d->created_at->format('d M Y, H:i') }}</div>
                        <div class="text-xs text-blue-600 mt-1 font-medium truncate max-w-[250px]" title="{{ $d->campaign->title }}">
                            {{ $d->campaign->title }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-black text-gray-900 text-lg">Rp {{ number_format($d->amount, 0, ',', '.') }}</div>
                        <div class="text-[10px] text-gray-400 font-mono mt-1">ID: {{ $d->order_id }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($d->proof_path)
                        <a href="http://127.0.0.1:8001/storage/{{ $d->proof_path }}" target="_blank" class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-emerald-100 transition border border-emerald-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            View Proof
                        </a>
                        @else
                            <span class="text-[10px] text-gray-400 font-medium italic">Otomatis / Tanpa Bukti</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($d->status == 'pending')
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-100 text-amber-700 border border-amber-200">Pending</span>
                        @elseif($d->status == 'paid')
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-green-100 text-green-700 border border-green-200">Verified</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-red-100 text-red-700 border border-red-200">Rejected</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($d->status == 'pending')
                        <div class="flex justify-end gap-2">
                            <form action="{{ route('admin.verify.donation.update', $d->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="paid">
                                <button type="submit" class="bg-emerald-600 text-white px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-emerald-700 transition shadow-sm">
                                    Approve
                                </button>
                            </form>
                            <form action="{{ route('admin.verify.donation.update', $d->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="bg-white text-red-600 border border-red-100 px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-red-50 transition">
                                    Reject
                                </button>
                            </form>
                        </div>
                        @else
                            <span class="text-xs text-gray-400 italic">No Actions</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-10 h-10 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="font-medium">Belum ada donasi manual yang tercatat.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($donations instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-6">
            {{ $donations->appends(request()->query())->links() }}
        </div>
    @endif
</div>
</div>
@endsection

@push('scripts')
<script>
    // Auto refresh every 30 seconds
    setTimeout(function(){
       window.location.reload();
    }, 30000);
</script>
@endpush
