@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0">Verifikasi Donasi</h3>
        <div class="text-muted small">Total: {{ $donations->total() }} Transaksi</div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4 animate__animated animate__fadeIn">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 24px;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted small fw-bold">ORDER ID</th>
                        <th class="px-4 py-3 text-muted small fw-bold">DONATUR</th>
                        <th class="px-4 py-3 text-muted small fw-bold">CAMPAIGN</th>
                        <th class="px-4 py-3 text-muted small fw-bold">BRUTO</th>
                        <th class="px-4 py-3 text-muted small fw-bold">FEE (5%)</th>
                        <th class="px-4 py-3 text-muted small fw-bold">NETTO</th>
                        <th class="px-4 py-3 text-muted small fw-bold">PAYMENT</th>
                        <th class="px-4 py-3 text-muted small fw-bold">STATUS</th>
                        <th class="px-4 py-3 text-muted small fw-bold text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donations as $donation)
                        <tr>
                            <td class="px-4 py-3">
                                <span class="fw-bold small">{{ $donation->order_id }}</span>
                                <div class="text-muted" style="font-size: 10px;">{{ $donation->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="fw-bold small">{{ $donation->user->username }}</div>
                                <div class="text-muted" style="font-size: 10px;">{{ $donation->user->email }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-truncate small fw-bold" style="max-width: 200px;">{{ $donation->campaign->title }}</div>
                                <div class="text-muted" style="font-size: 10px;">ID: {{ $donation->campaign_id }}</div>
                            </td>
                            <td class="px-4 py-3 font-monospace small fw-bold">
                                Rp {{ number_format($donation->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 font-monospace small text-danger">
                                Rp {{ number_format($donation->fee_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 font-monospace small text-success fw-bold">
                                Rp {{ number_format($donation->net_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="small fw-bold">{{ $donation->payment_method }}</div>
                                @if($donation->payment_method === 'Midtrans')
                                    @php
                                        $mColor = match($donation->midtrans_status) {
                                            'settlement', 'capture' => 'success',
                                            'pending' => 'warning',
                                            'expire', 'cancel', 'deny' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $mColor }}-subtle text-{{ $mColor }} border-0 rounded-pill px-2" style="font-size: 8px;">
                                        {{ strtoupper($donation->midtrans_status) }}
                                    </span>
                                @elseif($donation->proof_path)
                                    <a href="{{ Storage::url($donation->proof_path) }}" target="_blank" class="btn btn-xs btn-outline-primary py-0" style="font-size: 10px;">
                                        Lihat Bukti
                                    </a>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($donation->status === 'success')
                                    <span class="badge bg-success rounded-pill px-3" style="font-size: 10px;">Selesai</span>
                                @elseif($donation->status === 'pending')
                                    <span class="badge bg-warning text-dark rounded-pill px-3" style="font-size: 10px;">Menunggu</span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3" style="font-size: 10px;">Gagal</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($donation->status === 'pending')
                                    <div class="d-flex gap-2 justify-content-center">
                                        <form action="{{ route('admin.donations.verify', $donation->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 fw-bold" style="font-size: 10px;">Verifikasi</button>
                                        </form>
                                        <form action="{{ route('admin.donations.reject', $donation->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold" style="font-size: 10px;">Tolak</button>
                                        </form>
                                    </div>
                                @else
                                    <div class="text-muted small">
                                        By: {{ $donation->verifier->username ?? 'System' }}<br>
                                        {{ $donation->verified_at ? $donation->verified_at->format('d/m/y') : '' }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i data-lucide="inbox" class="mb-2" style="width: 48px; height: 48px; opacity: 0.2;"></i>
                                <p>Belum ada data donasi.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-light border-top">
            {{ $donations->links() }}
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th { letter-spacing: 0.5px; }
    .badge { letter-spacing: 0.3px; }
</style>
@endpush
