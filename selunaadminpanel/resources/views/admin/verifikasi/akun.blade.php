@extends('layouts.admin')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-[#1B3022]">Verifikasi Akun Pengguna</h2>
            <p class="text-sm text-gray-500 mt-1">Verifikasi identitas pengguna untuk menjadi Fundraiser.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 px-3 py-1 bg-green-50 text-green-600 rounded-full border border-green-100 animate-pulse">
                <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                <span class="text-[10px] font-bold uppercase tracking-wider">Live Monitoring</span>
            </div>

            <form action="{{ route('admin.verify.account.index') }}" method="GET" class="flex items-center gap-3 bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-2 border-r border-gray-100 pr-3">
                    <label for="sort" class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Urutan:</label>
                    <select name="sort" onchange="this.form.submit()" class="text-xs font-bold text-gray-700 bg-transparent border-none focus:ring-0 cursor-pointer">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>ID Terkecil</option>
                        <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>ID Terbesar</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <label for="per_page" class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Lihat:</label>
                    <select name="per_page" onchange="this.form.submit()" class="text-xs font-bold text-gray-700 bg-transparent border-none focus:ring-0 cursor-pointer">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                    </select>
                </div>
            </form>

            <span class="bg-[#2D5A27] text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-[#2D5A27]/20 flex items-center gap-2">
                <i data-lucide="users" class="w-4 h-4"></i>
                {{ $verifications instanceof \Illuminate\Pagination\LengthAwarePaginator ? $verifications->total() : $verifications->count() }} Data
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
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Identitas Pengguna</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Detail</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Status</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($verifications as $v)
                <tr class="hover:bg-gray-50 transition-colors {{ $v->status == 'pending' ? 'bg-amber-50/30' : '' }}">
                    <td class="px-6 py-4">
                        <div class="font-black text-gray-800">{{ $v->full_name }}</div>
                        <div class="text-[11px] text-gray-500 mt-0.5">{{ $v->user->email }}</div>
                        <div class="mt-2">
                             <a href="{{ env('SELUNA_MAIN_URL', 'http://127.0.0.1:8000') }}/storage/{{ $v->ktp_photo }}" target="_blank" class="inline-flex items-center gap-1.5 text-[#2D5A27] bg-[#2D5A27]/5 px-2 py-1 rounded-md text-[10px] font-black uppercase tracking-wider hover:bg-[#2D5A27]/10 transition border border-[#2D5A27]/10">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Lihat KTP
                            </a>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs text-gray-400 uppercase tracking-widest font-black">NIK</div>
                        <div class="text-sm font-mono text-gray-700 mb-2">{{ $v->nik }}</div>
                        <div class="text-xs text-gray-400 uppercase tracking-widest font-black">Organisasi</div>
                        <div class="text-sm font-bold text-gray-700">{{ $v->organization_name ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($v->status == 'pending')
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-100 text-amber-700 border border-amber-200">Pending</span>
                        @elseif($v->status == 'approved')
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-green-100 text-green-700 border border-green-200">Approved</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-red-100 text-red-700 border border-red-200">Rejected</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($v->status == 'pending')
                        <div class="flex justify-end gap-2">
                            <form action="{{ route('admin.verify.account.update', $v->id) }}" method="POST" data-seluna>
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="bg-[#2D5A27] text-white px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-[#1B3022] transition shadow-sm" data-action="update">
                                    Approve
                                </button>
                            </form>
                            <form action="{{ route('admin.verify.account.update', $v->id) }}" method="POST" data-seluna>
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="text-red-500 bg-red-50 px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-red-100 transition" data-action="update">
                                    Reject
                                </button>
                            </form>
                        </div>
                        @else
                            <span class="text-xs text-gray-400 italic font-medium px-4">No Actions</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-16 text-center text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <p class="text-lg font-medium">Belum ada pengajuan verifikasi akun.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($verifications instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-8">
            {{ $verifications->appends(request()->query())->links() }}
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