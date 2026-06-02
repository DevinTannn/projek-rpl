@extends('layouts.admin')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-[#1B3022]">Verifikasi Kampanye</h2>
            <p class="text-sm text-gray-500 mt-1">Verifikasi kampanye penggalangan dana baru yang masuk.</p>
        </div>
        <div class="flex items-center gap-4">
            <form action="{{ route('admin.verify.campaign.index') }}" method="GET" class="flex items-center gap-2">
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
                {{ $campaigns instanceof \Illuminate\Pagination\LengthAwarePaginator ? $campaigns->total() : $campaigns->count() }} Kampanye
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-4 animate__animated animate__fadeIn" role="alert">
            <span class="block sm:inline font-medium">{{ session('success') }}</span>
        </div>
    @endif
    
    <div class="grid gap-6">
        @forelse($campaigns as $c)
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row items-center justify-between hover:shadow-md transition-shadow relative overflow-hidden">
            @if($c->status == 'pending')
                <div class="absolute top-0 left-0 w-1.5 h-full bg-amber-400"></div>
            @endif
            
            <div class="flex flex-col md:flex-row items-center gap-6 w-full">
                <div class="w-full md:w-32 h-32 bg-gray-100 rounded-2xl overflow-hidden shadow-inner flex-shrink-0">
                    @if($c->banner)
                        <img src="http://127.0.0.1:8001/storage/{{ $c->banner }}" alt="Banner" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                </div>
                <div class="flex-grow">
                    <div class="flex items-center gap-3 mb-1">
                        <h3 class="font-black text-xl text-gray-800">{{ $c->title }}</h3>
                        @if($c->status == 'pending')
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-widest bg-amber-100 text-amber-700 border border-amber-200">Pending</span>
                        @elseif($c->status == 'active')
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-widest bg-green-100 text-green-700 border border-green-200">Active</span>
                        @else
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-widest bg-red-100 text-red-700 border border-red-200">Rejected</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 mb-3">
                        Oleh: <span class="font-bold text-[#2D5A27]">{{ $c->user->name }}</span> • <span class="text-gray-400">{{ $c->created_at->format('d M Y') }}</span>
                    </p>
                    <div class="flex flex-wrap gap-2 items-center">
                        <span class="bg-[#1B3022]/5 text-[#1B3022] px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wide border border-[#1B3022]/10">
                            {{ $c->tag }}
                        </span>
                        <div class="h-4 w-px bg-gray-200 mx-1"></div>
                        <span class="text-gray-600 text-xs font-medium">Target: <span class="text-gray-900 font-bold">Rp {{ number_format($c->goal_amount, 0, ',', '.') }}</span></span>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 mt-6 md:mt-0 w-full md:w-auto">
                @if($c->status == 'pending')
                    <form action="{{ route('admin.verify.campaign.update', $c->id) }}" method="POST" class="flex-grow md:flex-grow-0">
                        @csrf
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="w-full bg-[#2D5A27] text-white px-8 py-3 rounded-xl font-bold hover:bg-[#1B3022] transition shadow-lg shadow-[#2D5A27]/20">
                            Approve
                        </button>
                    </form>
                    <form action="{{ route('admin.verify.campaign.update', $c->id) }}" method="POST" class="flex-grow md:flex-grow-0">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="w-full bg-white text-red-600 border border-red-100 px-8 py-3 rounded-xl font-bold hover:bg-red-50 transition">
                            Reject
                        </button>
                    </form>
                @else
                    <div class="text-xs text-gray-400 italic font-medium px-4">No Actions Required</div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white p-16 rounded-3xl border-2 border-dashed border-gray-100 text-center text-gray-400">
            <svg class="w-16 h-16 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <p class="text-lg font-medium">Belum ada kampanye yang tercatat.</p>
        </div>
        @endforelse
    </div>

    @if($campaigns instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-8">
            {{ $campaigns->appends(request()->query())->links() }}
        </div>
    @endif
</div>
</div>
@endsection