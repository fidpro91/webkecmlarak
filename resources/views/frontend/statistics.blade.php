@extends('layouts.frontend')

@section('title', 'Statistik Kependudukan')

@section('content')
    <!-- Banner Header -->
    <div class="bg-[#5c0c16] py-16 text-white relative border-b border-[#7a1220]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
            <nav class="flex justify-center sm:justify-start items-center gap-2 text-xs text-amber-300 mb-2 font-semibold uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
                <span>/</span>
                <span class="text-rose-200">Statistik</span>
            </nav>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Statistik & Data Kependudukan</h1>
            <p class="mt-2 text-sm text-rose-100/90 max-w-xl">
                Visualisasi agregasi data jumlah penduduk dan sebaran kewilayahan di 15 desa se-Kecamatan Mlarak.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <span class="text-xs text-slate-500 font-medium block">Total Penduduk</span>
                    <p class="text-2xl font-extrabold text-slate-900">{{ number_format($totalPopulation, 0, ',', '.') }}</p>
                    <span class="text-[11px] text-emerald-600 font-semibold">Jiwa Terdaftar</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fa-solid fa-tree-city"></i>
                </div>
                <div>
                    <span class="text-xs text-slate-500 font-medium block">Total Desa Binaan</span>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $totalVillages }}</p>
                    <span class="text-[11px] text-teal-600 font-semibold">Desa Mandiri / Maju</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <div>
                    <span class="text-xs text-slate-500 font-medium block">Rata-rata Penduduk</span>
                    <p class="text-2xl font-extrabold text-slate-900">{{ number_format($avgPopulation, 0, ',', '.') }}</p>
                    <span class="text-[11px] text-sky-600 font-semibold">Jiwa per Desa</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fa-solid fa-ranking-star"></i>
                </div>
                <div>
                    <span class="text-xs text-slate-500 font-medium block">Desa Terpadat</span>
                    <p class="text-lg font-extrabold text-slate-900 truncate">{{ $maxVillage->nama ?? '-' }}</p>
                    <span class="text-[11px] text-amber-600 font-semibold">{{ number_format($maxVillage->jumlah_penduduk ?? 0, 0, ',', '.') }} Jiwa</span>
                </div>
            </div>
        </div>

        <!-- Chart.js Visualization Container -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-xl tracking-tight">Grafik Jumlah Penduduk per Desa</h3>
                    <p class="text-xs text-slate-500 mt-1">Perbandingan jumlah penduduk di 15 desa Kecamatan Mlarak</p>
                </div>
            </div>
            <div class="h-80 sm:h-96 w-full">
                <canvas id="villagesChart"></canvas>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden space-y-4 p-6 sm:p-8">
            <h3 class="font-extrabold text-slate-900 text-xl tracking-tight pb-2 border-b border-slate-100">
                Tabel Data Monografi Kependudukan
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-bold tracking-wider text-[11px] border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4">No</th>
                            <th class="py-3.5 px-4">Nama Desa</th>
                            <th class="py-3.5 px-4">Kepala Desa</th>
                            <th class="py-3.5 px-4">Luas Wilayah</th>
                            <th class="py-3.5 px-4 text-right">Jumlah Penduduk (Jiwa)</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($villages as $index => $v)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 font-semibold text-slate-400">{{ $index + 1 }}</td>
                                <td class="py-3.5 px-4 font-bold text-slate-900">{{ $v->nama }}</td>
                                <td class="py-3.5 px-4 font-medium">{{ $v->kepala_desa ?? '-' }}</td>
                                <td class="py-3.5 px-4">{{ $v->luas_wilayah ?? '-' }}</td>
                                <td class="py-3.5 px-4 font-extrabold text-emerald-700 text-right">
                                    {{ number_format($v->jumlah_penduduk, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <a href="{{ route('villages.show', $v->slug) }}" class="text-emerald-600 hover:text-emerald-800 font-semibold">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50 font-extrabold text-slate-900 text-xs border-t border-slate-200">
                        <tr>
                            <td colspan="4" class="py-4 px-4 text-right uppercase tracking-wider">Total Penduduk:</td>
                            <td class="py-4 px-4 text-right text-emerald-800 text-sm">
                                {{ number_format($totalPopulation, 0, ',', '.') }} Jiwa
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('villagesChart').getContext('2d');
        const labels = @json($chartLabels);
        const data = @json($chartData);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Penduduk (Jiwa)',
                    data: data,
                    backgroundColor: 'rgba(5, 150, 105, 0.85)',
                    hoverBackgroundColor: 'rgba(4, 120, 87, 1)',
                    borderColor: 'rgb(5, 150, 105)',
                    borderWidth: 1,
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toLocaleString('id-ID') + ' Jiwa';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 11 },
                            callback: function(value) {
                                return value.toLocaleString('id-ID');
                            }
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    x: {
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 10 },
                            maxRotation: 45,
                            minRotation: 45
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
