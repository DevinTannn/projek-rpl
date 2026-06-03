@extends('layouts.admin')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <h2 class="text-3xl font-black text-[#1B3022]">Dashboard Utama</h2>
        <p class="text-gray-500">Ringkasan aktivitas platform SELUNA hari ini.</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform">
                <svg class="w-12 h-12 text-[#2D5A27]" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
            </div>
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Total Dana Terkumpul</p>
            <h3 class="text-2xl font-black text-[#2D5A27] mt-2">Rp {{ number_format($totalDonations, 0, ',', '.') }}</h3>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform">
                <svg class="w-12 h-12 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
            </div>
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Total Fundraiser</p>
            <h3 class="text-2xl font-black text-[#1B3022] mt-2">{{ number_format($totalFundraisers) }} <span class="text-xs font-normal text-gray-400">Akun</span></h3>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform">
                <svg class="w-12 h-12 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.4503-.43l-7 4.872a1 1 0 00-.147 1.378l7 9.492a1 1 0 001.604-.207l1.04-1.921a2 2 0 013.255-1.956l1.328 1.13a1 1 0 001.442-1.44l-7.117-6.096c.224-.134.46-.243.704-.32a2 2 0 003.111-2.072l-1.328-3.32a.999.999 0 00-.819-.68c-.141-.019-.283-.028-.426-.028-.948 0-1.84.417-2.457 1.114l-.001.002-.132.148z" clip-rule="evenodd"></path></svg>
            </div>
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Kampanye Aktif</p>
            <h3 class="text-2xl font-black text-[#1B3022] mt-2">{{ number_format($activeCampaigns) }} <span class="text-xs font-normal text-gray-400">Aktif</span></h3>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow bg-amber-50/50">
            <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform text-amber-600">
                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            </div>
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest text-amber-700">Perlu Verifikasi</p>
            <h3 class="text-2xl font-black text-amber-600 mt-2">{{ number_format($pendingVerifications) }} <span class="text-xs font-normal text-amber-400">Kampanye</span></h3>
        </div>
    </div>

    {{-- Recent Donations Table --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center">
            <h4 class="font-black text-[#1B3022]">Donasi Terbaru</h4>
            <a href="{{ route('admin.verify.donation.index') }}" class="text-xs font-bold text-[#2D5A27] hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Donatur</th>
                        <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Kampanye</th>
                        <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Jumlah</th>
                        <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentDonations as $d)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">{{ $d->user->name ?? 'Anonim' }}</div>
                            <div class="text-[10px] text-gray-400">{{ $d->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600 truncate max-w-[200px]" title="{{ $d->campaign->title }}">{{ $d->campaign->title }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-black text-[#1B3022]">Rp {{ number_format($d->amount, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($d->status == 'pending')
                                <span class="px-2 py-1 rounded-md text-[9px] font-black uppercase tracking-widest bg-amber-100 text-amber-700">Pending</span>
                            @elseif($d->status == 'paid')
                                <span class="px-2 py-1 rounded-md text-[9px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-700">Verified</span>
                            @else
                                <span class="px-2 py-1 rounded-md text-[9px] font-black uppercase tracking-widest bg-red-100 text-red-700">Rejected</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-400">Belum ada donasi hari ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection