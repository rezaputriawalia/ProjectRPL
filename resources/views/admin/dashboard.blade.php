<x-layouts.app
    title="Dashboard Admin"
    role="admin"
    brand="SIGAP Admin"
    subtitle="Rumah Sakit Jiwa"
    active="beranda"
    :nav-items="$navItems"
    :user-name="$admin?->name ?? 'Admin SIGAP'"
    user-role="Administrator Utama"
    search-placeholder="Cari pasien, dokter, bangsal..."
    :show-footer="false"
>
    <div class="admin-dashboard">
        <div class="admin-dashboard__header d-flex flex-column flex-lg-row justify-content-between gap-3">
            <div>
                <h1>Dashboard Admin</h1>
                <p>Pantau seluruh aktivitas Rumah Sakit Jiwa secara real-time.</p>
            </div>
        </div>

        <div class="row g-4 admin-stats-row">
            @foreach ($stats as $stat)
                <div class="col-12 col-sm-6 col-xl-3">
                    <x-admin.stat-card
                        :label="$stat['label']"
                        :value="$stat['value']"
                        :description="$stat['description']"
                        :icon="$stat['icon']"
                        :accent="$stat['accent']"
                    />
                </div>
            @endforeach
        </div>

        <div class="row g-4 admin-main-row">
            <div class="col-12 col-xl-8">
                <section class="admin-panel admin-panel--chart">
                    <div class="admin-panel__header">
                        <div>
                            <h2>Jumlah Pasien per Bangsal</h2>
                            <p>Distribusi real-time pasien di seluruh unit</p>
                        </div>
                        <div class="admin-filter-pills" aria-label="Filter grafik pasien per bangsal">
                            <a href="{{ route('admin.dashboard', ['period' => 'mingguan']) }}" @class(['is-active' => $period === 'mingguan'])>Mingguan</a>
                            <a href="{{ route('admin.dashboard', ['period' => 'bulanan']) }}" @class(['is-active' => $period === 'bulanan'])>Bulanan</a>
                        </div>
                    </div>
                    <div class="admin-chart-wrap">
                        <canvas id="wardPatientsChart" aria-label="Grafik pasien per bangsal"></canvas>
                    </div>
                </section>
            </div>

            <div class="col-12 col-xl-4">
                <section class="admin-panel admin-capacity-panel">
                    <div class="admin-panel__header admin-panel__header--plain">
                        <h2>Kapasitas Bangsal</h2>
                    </div>

                    <div class="admin-capacity-list">
                        @forelse ($wardCapacities as $ward)
                            <div class="admin-capacity-item">
                                <div class="admin-capacity-item__top">
                                    <strong>{{ $ward['name'] }}</strong>
                                    <span>{{ $ward['occupied'] }} / {{ $ward['capacity'] }}</span>
                                </div>
                                <div class="admin-progress admin-progress--{{ $ward['tone'] }}" role="progressbar" aria-valuenow="{{ $ward['percentage'] }}" aria-valuemin="0" aria-valuemax="100">
                                    <span style="width: {{ $ward['percentage'] }}%"></span>
                                </div>
                                <div class="admin-capacity-item__percent">{{ $ward['percentage'] }}%</div>
                            </div>
                        @empty
                            <p class="admin-empty-state">Belum ada data kapasitas bangsal.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>

        <div class="row g-4 admin-bottom-row">
            <div class="col-12 col-xl-5">
                <section class="admin-panel admin-activity-panel">
                    <div class="admin-panel__header admin-panel__header--plain">
                        <h2>Aktivitas Terbaru</h2>
                    </div>

                    <div class="admin-timeline">
                        @forelse ($activities as $activity)
                            <div class="admin-timeline__item">
                                <span class="admin-timeline__icon"><i class="{{ $activity['icon'] }}"></i></span>
                                <div>
                                    <strong>{{ str($activity['action'])->headline() }}</strong>
                                    <p>{{ $activity['description'] }}</p>
                                    <small>{{ $activity['user_name'] }} - {{ $activity['time'] }}</small>
                                </div>
                            </div>
                        @empty
                            <p class="admin-empty-state">Belum ada aktivitas terbaru.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <div class="col-12 col-xl-4">
                <section class="admin-panel admin-notification-panel">
                    <div class="admin-panel__header admin-panel__header--plain">
                        <h2>Notifikasi Sistem</h2>
                    </div>

                    <div class="admin-notification-list">
                        @forelse ($notifications as $notification)
                            <x-admin.notification
                                :title="$notification['title']"
                                :description="$notification['description']"
                                :tone="$notification['tone']"
                                :icon="$notification['icon']"
                            />
                        @empty
                            <p class="admin-empty-state">Tidak ada notifikasi sistem.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <div class="col-12 col-xl-3">
                <section class="admin-panel admin-user-summary-panel">
                    <div class="admin-panel__header admin-panel__header--plain">
                        <h2>Ringkasan User</h2>
                    </div>
                    <div class="admin-doughnut-wrap">
                        <canvas id="userSummaryChart" aria-label="Ringkasan user"></canvas>
                    </div>
                    <div class="admin-user-legend">
                        @foreach ($userSummary['labels'] as $index => $label)
                            <div>
                                <span></span>
                                <strong>{{ $label }}</strong>
                                <em>{{ number_format($userSummary['data'][$index]) }}</em>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        const wardChartPayload = @json($wardChart);
        const userSummaryPayload = @json($userSummary);

        const wardCanvas = document.getElementById('wardPatientsChart');
        if (wardCanvas) {
            new Chart(wardCanvas, {
                type: 'bar',
                data: {
                    labels: wardChartPayload.labels,
                    datasets: [{
                        data: wardChartPayload.data,
                        backgroundColor: '#4a835f',
                        borderRadius: 12,
                        borderSkipped: false,
                        barThickness: 34,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#2f302d',
                            padding: 12,
                            cornerRadius: 12,
                        },
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#6f6a62', font: { size: 12, weight: 600 } },
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#eee8de' },
                            ticks: { color: '#9a948c', precision: 0 },
                        },
                    },
                },
            });
        }

        const userCanvas = document.getElementById('userSummaryChart');
        if (userCanvas) {
            new Chart(userCanvas, {
                type: 'doughnut',
                data: {
                    labels: userSummaryPayload.labels,
                    datasets: [{
                        data: userSummaryPayload.data,
                        backgroundColor: ['#7b622d', '#d8b66b', '#4a835f'],
                        borderColor: '#ffffff',
                        borderWidth: 5,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: { display: false },
                    },
                },
            });
        }
    </script>
@endpush
</x-layouts.app>