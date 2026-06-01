@extends('layouts.admin')

@section('content')
<div class="p-8">
    <h2 class="text-2xl font-bold text-[#1B3022] mb-6">Laporan Keuangan</h2>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b text-gray-500">
                    <th class="pb-4">Nama Kampanye</th>
                    <th class="pb-4">Total Donasi</th>
                    <th class="pb-4">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="py-4">Tangan Andi Lepas</td>
                    <td class="py-4 font-semibold">Rp 5.000.000</td>
                    <td class="py-4"><span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">Selesai</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection