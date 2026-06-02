@extends('layouts.admin')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-[#1B3022]">Verifikasi Akun Pengguna</h2>
            <p class="text-sm text-gray-500 mt-1">Verifikasi identitas pengguna untuk menjadi Fundraiser.</p>
        </div>
        <div class="flex items-center gap-4">
            <form action="{{ route('admin.verify.account.index') }}" method="GET" class="flex items-center gap-2">
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
                {{ $verifications instanceof \Illuminate\Pagination\LengthAwarePaginator ? $verifications->total() : $verifications->count() }} Pengajuan
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
                             <a href="http://127.0.0.1:8001/storage/{{ $v->ktp_photo }}" target="_blank" class="inline-flex items-center gap-1.5 text-[#2D5A27] bg-[#2D5A27]/5 px-2 py-1 rounded-md text-[10px] font-black uppercase tracking-wider hover:bg-[#2D5A27]/10 transition border border-[#2D5A27]/10">
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
                            <form action="{{ route('admin.verify.account.update', $v->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="bg-[#2D5A27] text-white px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-[#1B3022] transition shadow-sm">
                                    Approve
                                </button>
                            </form>
                            <form action="{{ route('admin.verify.account.update', $v->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="bg-white text-red-600 border border-red-100 px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-red-50 transition">
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