<?php

use function Livewire\Volt\{state, mount, computed};
use App\Models\FinalRankRecommendation;
use Illuminate\Support\Facades\DB;

state([
    'ranking_results',
    'recommendations',
    'debug_data',
    'preferences_data',
    'multimoora_results',
    'processing_steps',
    'all_alternatives',

    'top_internship_recommendation'
]);

mount(function () {
    $userId = auth('mahasiswa')->user()->id;

    // Centralized location categorization function
    $kategorisasiLokasi = function ($lokasi) {
        if (empty($lokasi)) {
            return 'Tidak Diketahui';
        }

        $lokasi = strtolower(trim($lokasi));

        // Remote work patterns
        $remotePatterns = ['remote', 'work from home', 'wfh', 'online', 'virtual', 'dari rumah'];
        foreach ($remotePatterns as $pattern) {
            if (strpos($lokasi, $pattern) !== false) {
                return 'remote';
            }
        }

        // Area Malang
        $areaMalang = ['malang', 'malang kota', 'kota malang', 'malang raya', 'batu', 'kabupaten malang'];
        foreach ($areaMalang as $area) {
            if (strpos($lokasi, $area) !== false) {
                return 'area malang raya';
            }
        }

        // International locations
        $luarNegeri = ['singapore', 'singapura', 'malaysia', 'thailand', 'vietnam', 'philippines', 'filipina', 'japan', 'jepang', 'korea', 'china', 'taiwan', 'hong kong', 'australia', 'new zealand', 'usa', 'america', 'united states', 'canada', 'uk', 'england', 'germany', 'france', 'netherlands', 'belanda', 'dubai', 'saudi arabia'];
        foreach ($luarNegeri as $negara) {
            if (strpos($lokasi, $negara) !== false) {
                return 'luar negeri';
            }
        }

        // East Java cities (outside Malang area)
        $jawaTimur = ['surabaya', 'sidoarjo', 'gresik', 'lamongan', 'tuban', 'bojonegoro', 'ngawi', 'madiun', 'ponorogo', 'pacitan', 'trenggalek', 'tulungagung', 'kediri', 'blitar', 'jombang', 'mojokerto', 'pasuruan', 'probolinggo', 'lumajang', 'jember', 'bondowoso', 'situbondo', 'banyuwangi', 'sampang', 'pamekasan', 'sumenep', 'bangkalan'];
        foreach ($jawaTimur as $kota) {
            if (strpos($lokasi, $kota) !== false) {
                return 'luar area malang raya (dalam provinsi)';
            }
        }

        // Default: Outside East Java province
        return 'luar provinsi jawa timur';
    };

    // Get ALL unique recommendations without limit for comprehensive analysis
    $allUniqueRecommendations = DB::table('final_rank_recommendation as frr1')
        ->join(DB::raw('(SELECT lowongan_magang_id, MAX(created_at) as latest_created_at FROM final_rank_recommendation WHERE mahasiswa_id = ' . $userId . ' GROUP BY lowongan_magang_id) as latest'), function ($join) {
            $join->on('frr1.lowongan_magang_id', '=', 'latest.lowongan_magang_id')->on('frr1.created_at', '=', 'latest.latest_created_at');
        })
        ->where('frr1.mahasiswa_id', $userId)
        ->select('frr1.*')
        ->orderBy('frr1.rank', 'asc')
        ->get();

    // Convert ALL alternatives to collection for comprehensive analysis
    $this->all_alternatives = $allUniqueRecommendations
        ->map(function ($item) use ($kategorisasiLokasi) {
            // Load the related data manually
            $lowonganMagang = DB::table('lowongan_magang')->where('id', $item->lowongan_magang_id)->first();

            $perusahaan = null;
            $pekerjaan = null;
            $bidangIndustri = null;

            if ($lowonganMagang) {
                $perusahaan = DB::table('perusahaan')->where('id', $lowonganMagang->perusahaan_id)->first();
                $pekerjaan = DB::table('pekerjaan')->where('id', $lowonganMagang->pekerjaan_id)->first();

                if ($perusahaan) {
                    $bidangIndustri = DB::table('bidang_industri')->where('id', $perusahaan->bidang_industri_id)->first();
                }
            }

            $originalLokasi = $perusahaan->lokasi ?? '';
            $categorizedLokasi = $kategorisasiLokasi($originalLokasi);

            return [
                'rank' => $item->rank,
                'avg_rank' => $item->avg_rank,
                'lowongan_id' => $item->lowongan_magang_id,
                'pekerjaan' => $pekerjaan->nama ?? '',
                'bidang_industri' => $bidangIndustri->nama ?? '',
                'lokasi' => $categorizedLokasi,
                'lokasi_original' => $originalLokasi,
                'jenis_magang' => $lowonganMagang->jenis_magang ?? '',
                'open_remote' => $lowonganMagang->open_remote ?? '',
                'nama_perusahaan' => $perusahaan->nama ?? '',
                'nama_lowongan' => $lowonganMagang->nama ?? '',
                'lowongan_magang' => [
                    'nama' => $lowonganMagang->nama ?? '',
                    'lokasi' => $categorizedLokasi,
                    'jenis_magang' => $lowonganMagang->jenis_magang ?? '',
                    'open_remote' => $lowonganMagang->open_remote ?? '',
                    'pekerjaan' => [
                        'nama' => $pekerjaan->nama ?? '',
                    ],
                    'perusahaan' => [
                        'id' => $perusahaan->id ?? null,
                        'nama' => $perusahaan->nama ?? '',
                        'lokasi' => $originalLokasi,
                        'bidang_industri' => [
                            'nama' => $bidangIndustri->nama ?? '',
                        ],
                    ],
                ],
            ];
        })
        ->toArray();

    // Store ALL alternatives for comprehensive analysis
    $this->ranking_results = $this->all_alternatives;

    // Get TOP 10 for display purposes only
    $this->recommendations = array_slice($this->all_alternatives, 0, 10);

    // Load user preferences from database
    $this->preferences_data = $this->loadUserPreferences($userId);

    // Initialize processing steps for debugging
    $this->processing_steps = [
        'database_load' => [
            'status' => 'loaded',
            'count' => count($this->all_alternatives),
            'display_count' => count($this->recommendations),
            'timestamp' => time(),
            'formatted_time' => date('Y-m-d H:i:s'),
        ],
    ];

    // Set debug data for compatibility
    $this->debug_data = [
        'final_ranking' => $this->ranking_results,
        'preferences' => $this->preferences_data,
        'total_alternatives' => count($this->all_alternatives),
        'displayed_alternatives' => count($this->recommendations),
    ];



    //
    //
    //
    //
    // THIS IS NEW QUERY
    $this->top_internship_recommendation = FinalRankRecommendation::where('mahasiswa_id', '=', $userId)
        ->orderBy('updated_at', 'desc')
        ->orderBy('rank', 'asc')
        ->take(10)
        ->get();
});

// Load user preferences from database
$loadUserPreferences = function ($userId) {
    // Centralized location categorization function (same as above)
    $kategorisasiLokasi = function ($lokasi) {
        if (empty($lokasi)) {
            return 'Tidak Diketahui';
        }

        $lokasi = strtolower(trim($lokasi));

        // Remote work patterns
        $remotePatterns = ['remote', 'work from home', 'wfh', 'online', 'virtual', 'dari rumah'];
        foreach ($remotePatterns as $pattern) {
            if (strpos($lokasi, $pattern) !== false) {
                return 'remote';
            }
        }

        // Area Malang
        $areaMalang = ['malang', 'malang kota', 'kota malang', 'malang raya', 'batu', 'kabupaten malang'];
        foreach ($areaMalang as $area) {
            if (strpos($lokasi, $area) !== false) {
                return 'area malang raya';
            }
        }

        // International locations
        $luarNegeri = ['singapore', 'singapura', 'malaysia', 'thailand', 'vietnam', 'philippines', 'filipina', 'japan', 'jepang', 'korea', 'china', 'taiwan', 'hong kong', 'australia', 'new zealand', 'usa', 'america', 'united states', 'canada', 'uk', 'england', 'germany', 'france', 'netherlands', 'belanda', 'dubai', 'saudi arabia'];
        foreach ($luarNegeri as $negara) {
            if (strpos($lokasi, $negara) !== false) {
                return 'luar negeri';
            }
        }

        // East Java cities (outside Malang area)
        $jawaTimur = ['surabaya', 'sidoarjo', 'gresik', 'lamongan', 'tuban', 'bojonegoro', 'ngawi', 'madiun', 'ponorogo', 'pacitan', 'trenggalek', 'tulungagung', 'kediri', 'blitar', 'jombang', 'mojokerto', 'pasuruan', 'probolinggo', 'lumajang', 'jember', 'bondowoso', 'situbondo', 'banyuwangi', 'sampang', 'pamekasan', 'sumenep', 'bangkalan'];
        foreach ($jawaTimur as $kota) {
            if (strpos($lokasi, $kota) !== false) {
                return 'luar area malang raya (dalam provinsi)';
            }
        }

        // Default: Outside East Java province
        return 'luar provinsi jawa timur';
    };

    // Get user preferences from criteria tables
    $preferences = [];

    // Get pekerjaan preference
    $pekerjaanPref = DB::table('kriteria_pekerjaan')->join('pekerjaan', 'kriteria_pekerjaan.pekerjaan_id', '=', 'pekerjaan.id')->where('kriteria_pekerjaan.mahasiswa_id', $userId)->orderBy('kriteria_pekerjaan.rank', 'asc')->first();

    if ($pekerjaanPref) {
        $preferences['pekerjaan'] = $pekerjaanPref->nama;
    }

    // Get bidang industri preference
    $bidangPref = DB::table('kriteria_bidang_industri')->join('bidang_industri', 'kriteria_bidang_industri.bidang_industri_id', '=', 'bidang_industri.id')->where('kriteria_bidang_industri.mahasiswa_id', $userId)->orderBy('kriteria_bidang_industri.rank', 'asc')->first();

    if ($bidangPref) {
        $preferences['bidang_industri'] = $bidangPref->nama;
    }

    // Get lokasi preference - IMPROVED: Apply categorization and normalization
    $lokasiPref = DB::table('kriteria_lokasi_magang')->join('lokasi_magang', 'kriteria_lokasi_magang.lokasi_magang_id', '=', 'lokasi_magang.id')->where('kriteria_lokasi_magang.mahasiswa_id', $userId)->orderBy('kriteria_lokasi_magang.rank', 'asc')->first();

    if ($lokasiPref) {
        // Store both original and categorized preference
        $originalPreference = $lokasiPref->kategori_lokasi;
        $categorizedPreference = $kategorisasiLokasi($originalPreference);

        $preferences['lokasi'] = $categorizedPreference;
        $preferences['lokasi_original'] = $originalPreference;
        $preferences['lokasi_kategori'] = $categorizedPreference;
    }

    // Get jenis magang preference
    $jenisPref = DB::table('kriteria_jenis_magang')->where('mahasiswa_id', $userId)->orderBy('rank', 'asc')->first();

    if ($jenisPref) {
        $preferences['jenis_magang'] = $jenisPref->jenis_magang;
    }

    // Get remote preference
    $remotePref = DB::table('kriteria_open_remote')->where('mahasiswa_id', $userId)->orderBy('rank', 'asc')->first();

    if ($remotePref) {
        $preferences['open_remote'] = $remotePref->open_remote;
    }

    return $preferences;
};

$refreshRecommendationData = function () {
    $userId = auth('mahasiswa')->user()->id;

    // Re-run the mount logic
    $this->mount();

    // Log the refresh for debugging
    \Log::info("Recommendation data refreshed for user: {$userId}", [
        'total_alternatives' => count($this->all_alternatives ?? []),
        'displayed_recommendations' => count($this->recommendations ?? []),
        'preferences_loaded' => !empty($this->preferences_data),
    ]);
};

$getFileAge = function ($type) {
    if (!isset($this->processing_steps[$type]['timestamp'])) {
        return 'Tidak Diketahui';
    }

    $timestamp = $this->processing_steps[$type]['timestamp'];
    $now = time();
    $diff = $now - $timestamp;

    if ($diff < 60) {
        return "{$diff} detik yang lalu";
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' menit yang lalu';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' jam yang lalu';
    } else {
        return floor($diff / 86400) . ' hari yang lalu';
    }
};

$hasRecommendationData = computed(function () {
    return !empty($this->all_alternatives) && is_array($this->all_alternatives) && count($this->all_alternatives) > 0;
});

$getProcessingSummary = computed(function () {
    if (empty($this->processing_steps)) {
        return [
            'total_files' => 0,
            'loaded_files' => 0,
            'error_files' => 0,
            'missing_files' => 0,
        ];
    }

    $summary = [
        'total_files' => count($this->processing_steps),
        'loaded_files' => 0,
        'error_files' => 0,
        'missing_files' => 0,
    ];

    foreach ($this->processing_steps as $step) {
        switch ($step['status']) {
            case 'loaded':
                $summary['loaded_files']++;
                break;
            case 'json_error':
            case 'file_error':
                $summary['error_files']++;
                break;
            case 'not_found':
                $summary['missing_files']++;
                break;
        }
    }

    return $summary;
});

// Ranking analysis computed property - UPDATED to use ALL alternatives
$getRankingAnalysis = computed(function () {
    // Use ALL alternatives for comprehensive analysis, not just the displayed ones
    if (!$this->all_alternatives || !is_array($this->all_alternatives) || count($this->all_alternatives) === 0) {
        return null;
    }

    $analysis = [
        'total_alternatives' => count($this->all_alternatives),
        'displayed_alternatives' => count($this->recommendations ?? []),
        'top_10' => array_slice($this->all_alternatives, 0, 10), // Get top 10 for display
        'score_distribution' => [],
        'preference_matches' => [],
        'criteria_performance' => [],
    ];

    // Calculate score distribution using avg_rank from ALL alternatives
    $scores = array_column($this->all_alternatives, 'avg_rank');
    if (!empty($scores)) {
        $analysis['score_distribution'] = [
            'highest' => max($scores),
            'lowest' => min($scores),
            'average' => array_sum($scores) / count($scores),
            'median' => $this->calculateMedian($scores),
        ];
    }

    // Analyze preference matches if preferences are available - USING ALL ALTERNATIVES
    if ($this->preferences_data) {
        $analysis['preference_matches'] = $this->analyzePreferenceMatches();
    }

    // Analyze criteria performance from ALL alternatives
    $analysis['criteria_performance'] = $this->analyzeCriteriaPerformance();

    return $analysis;
});

// Helper method to calculate median
$calculateMedian = function ($array) {
    if (empty($array)) {
        return 0;
    }

    sort($array);
    $count = count($array);
    $middle = floor(($count - 1) / 2);

    if ($count % 2) {
        return $array[$middle];
    } else {
        return ($array[$middle] + $array[$middle + 1]) / 2;
    }
};

// UPDATED: Helper method to analyze preference matches using ALL alternatives
$analyzePreferenceMatches = function () {
    if (!$this->preferences_data || !$this->all_alternatives) {
        return [];
    }

    $matches = [
        'job_type' => 0,
        'industry' => 0,
        'location' => 0,
        'payment_type' => 0,
        'remote_work' => 0,
        'total_perfect_matches' => 0,
        'total_analyzed' => count($this->all_alternatives), // Use ALL alternatives count
        'partial_matches' => [
            '4_of_5' => 0, // 4 out of 5 criteria match
            '3_of_5' => 0, // 3 out of 5 criteria match
            '2_of_5' => 0, // 2 out of 5 criteria match
            '1_of_5' => 0, // 1 out of 5 criteria match
        ],
    ];

    // Analyze ALL alternatives for comprehensive matching
    foreach ($this->all_alternatives as $alternative) {
        $matchCount = 0;
        $perfectMatch = true;

        // Check job type match
        if (isset($this->preferences_data['pekerjaan']) && isset($alternative['pekerjaan'])) {
            if (strtolower(trim($this->preferences_data['pekerjaan'])) === strtolower(trim($alternative['pekerjaan']))) {
                $matches['job_type']++;
                $matchCount++;
            } else {
                $perfectMatch = false;
            }
        }

        // Check industry match
        if (isset($this->preferences_data['bidang_industri']) && isset($alternative['bidang_industri'])) {
            if (strtolower(trim($this->preferences_data['bidang_industri'])) === strtolower(trim($alternative['bidang_industri']))) {
                $matches['industry']++;
                $matchCount++;
            } else {
                $perfectMatch = false;
            }
        }

        // Check location match with proper categorization
        if (isset($this->preferences_data['lokasi']) && isset($alternative['lokasi'])) {
            $preferenceLocation = $this->preferences_data['lokasi'];
            $alternativeLocation = $alternative['lokasi'];

            if ($preferenceLocation === $alternativeLocation) {
                $matches['location']++;
                $matchCount++;
            } else {
                $perfectMatch = false;
            }
        }

        // Check payment type match
        if (isset($this->preferences_data['jenis_magang']) && isset($alternative['jenis_magang'])) {
            if (strtolower(trim($this->preferences_data['jenis_magang'])) === strtolower(trim($alternative['jenis_magang']))) {
                $matches['payment_type']++;
                $matchCount++;
            } else {
                $perfectMatch = false;
            }
        }

        // Check remote work match
        if (isset($this->preferences_data['open_remote']) && isset($alternative['open_remote'])) {
            if (strtolower(trim($this->preferences_data['open_remote'])) === strtolower(trim($alternative['open_remote']))) {
                $matches['remote_work']++;
                $matchCount++;
            } else {
                $perfectMatch = false;
            }
        }

        // Count perfect matches
        if ($perfectMatch && $matchCount >= 5) {
            $matches['total_perfect_matches']++;
        }

        // Count partial matches
        switch ($matchCount) {
            case 4:
                $matches['partial_matches']['4_of_5']++;
                break;
            case 3:
                $matches['partial_matches']['3_of_5']++;
                break;
            case 2:
                $matches['partial_matches']['2_of_5']++;
                break;
            case 1:
                $matches['partial_matches']['1_of_5']++;
                break;
        }
    }

    return $matches;
};

// UPDATED: Helper method to analyze criteria performance using ALL alternatives
$analyzeCriteriaPerformance = function () {
    if (!$this->all_alternatives) {
        return [];
    }

    // Use ALL alternatives instead of just top alternatives
    $criteria = [];

    foreach ($this->all_alternatives as $alternative) {
        // Extract score-related fields
        foreach ($alternative as $key => $value) {
            if ((str_ends_with($key, '_score') || $key === 'avg_rank' || $key === 'rank') && is_numeric($value)) {
                if (!isset($criteria[$key])) {
                    $criteria[$key] = [];
                }
                $criteria[$key][] = $value;
            }
        }
    }

    $performance = [];
    foreach ($criteria as $criterion => $scores) {
        if (!empty($scores)) {
            $performance[$criterion] = [
                'average' => array_sum($scores) / count($scores),
                'highest' => max($scores),
                'lowest' => min($scores),
                'median' => $this->calculateMedian($scores),
                'total_analyzed' => count($scores), // Track how many were analyzed
                'criterion_name' => ucfirst(str_replace(['_score', '_'], [' Score', ' '], $criterion)),
                'std_deviation' => $this->calculateStandardDeviation($scores), // Add standard deviation
            ];
        }
    }

    return $performance;
};

// Helper method to calculate standard deviation
$calculateStandardDeviation = function ($array) {
    if (empty($array) || count($array) === 1) {
        return 0;
    }

    $mean = array_sum($array) / count($array);
    $variance =
        array_sum(
            array_map(function ($value) use ($mean) {
                return pow($value - $mean, 2);
            }, $array),
        ) / count($array);

    return sqrt($variance);
};

?>

<div>
    @if ($recommendations && count($recommendations) > 0)
        <!-- Enhanced Recommendation Analysis Section -->
        <div class="mb-8 space-y-4">
            <!-- Comprehensive Ranking Analysis Section -->
            <div class="p-4 bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-lg">
                <h3 class="font-bold text-indigo-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    🎯 Analisis Rekomendasi Personal Anda
                </h3>

                <!-- Quick Insights - Dynamic Version -->
                <div class="bg-white p-4 rounded-lg shadow-sm mb-6">

                    @php $analysis = $this->getRankingAnalysis; @endphp

                    <!-- Enhanced Analysis Cards -->
                    @if (isset($analysis['score_distribution']) && !empty($analysis['score_distribution']))
                        <!-- Overall Statistics -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div
                                class="bg-gradient-to-br from-blue-50 to-indigo-50 p-4 rounded-lg shadow-sm border border-blue-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-blue-700 text-sm">Total Alternatif</h4>
                                        <p class="text-2xl font-bold text-blue-600">
                                            {{ $analysis['total_alternatives'] }}</p>
                                        <p class="text-xs text-blue-500">Dipersonalisasi untuk Anda</p>
                                    </div>
                                    <div class="text-blue-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-green-50 to-emerald-50 p-4 rounded-lg shadow-sm border border-green-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-green-700 text-sm">Skor Tertinggi</h4>
                                        <p class="text-2xl font-bold text-green-600">
                                            {{ number_format($analysis['score_distribution']['highest'], 2) }}</p>
                                        <p class="text-xs text-green-500">Peluang terbaik</p>
                                    </div>
                                    <div class="text-green-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-purple-50 to-violet-50 p-4 rounded-lg shadow-sm border border-purple-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-purple-700 text-sm">Rata-rata Skor</h4>
                                        <p class="text-2xl font-bold text-purple-600">
                                            {{ number_format($analysis['score_distribution']['average'], 2) }}</p>
                                        <p class="text-xs text-purple-500">Kualitas menyeluruh</p>
                                    </div>
                                    <div class="text-purple-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-orange-50 to-red-50 p-4 rounded-lg shadow-sm border border-orange-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-orange-700 text-sm">Top 10</h4>
                                        <p class="text-2xl font-bold text-orange-600">{{ count($analysis['top_10']) }}
                                        </p>
                                        <p class="text-xs text-orange-500">Pilihan terdepan</p>
                                    </div>
                                    <div class="text-orange-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preference Matching Analysis -->
                        @if (!empty($analysis['preference_matches']) && isset($preferences_data))
                            <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
                                <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Seberapa Sesuai
                                    {{ $analysis['preference_matches']['total_analyzed'] ?? count($recommendations) }}
                                    Rekomendasi dengan Preferensi Anda?
                                </h4>

                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                                    <div
                                        class="text-center p-3 bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg border border-green-200">
                                        <p class="text-2xl font-bold text-green-600">
                                            {{ $analysis['preference_matches']['total_perfect_matches'] }}</p>
                                        <p class="text-sm text-green-700 font-medium">Kecocokan Sempurna</p>
                                        <p class="text-xs text-gray-600">Semua preferensi selaras</p>
                                    </div>
                                    <div
                                        class="text-center p-3 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-lg border border-blue-200">
                                        <p class="text-2xl font-bold text-blue-600">
                                            {{ $analysis['preference_matches']['job_type'] }}</p>
                                        <p class="text-sm text-blue-700 font-medium">Kecocokan Tipe Pekerjaan</p>
                                        <p class="text-xs text-gray-600">{{ $preferences_data['pekerjaan'] ?? 'N/A' }}
                                        </p>
                                    </div>
                                    <div
                                        class="text-center p-3 bg-gradient-to-br from-purple-50 to-violet-50 rounded-lg border border-purple-200">
                                        <p class="text-2xl font-bold text-purple-600">
                                            {{ $analysis['preference_matches']['industry'] }}</p>
                                        <p class="text-sm text-purple-700 font-medium">Kecocokan Industri</p>
                                        <p class="text-xs text-gray-600">
                                            {{ $preferences_data['bidang_industri'] ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div
                                        class="text-center p-3 bg-gradient-to-br from-orange-50 to-red-50 rounded-lg border border-orange-200">
                                        <p class="text-lg font-bold text-orange-600">
                                            {{ $analysis['preference_matches']['location'] }}/{{ $analysis['preference_matches']['total_analyzed'] ?? count($recommendations) }}
                                        </p>
                                        <p class="text-sm text-orange-700 font-medium">Kecocokan Lokasi</p>
                                        <p class="text-xs text-gray-600">
                                            {{ $preferences_data['lokasi_kategori'] ?? 'N/A' }}</p>
                                    </div>
                                    <div
                                        class="text-center p-3 bg-gradient-to-br from-teal-50 to-cyan-50 rounded-lg border border-teal-200">
                                        <p class="text-lg font-bold text-teal-600">
                                            {{ $analysis['preference_matches']['payment_type'] }}/{{ $analysis['preference_matches']['total_analyzed'] ?? count($recommendations) }}
                                        </p>
                                        <p class="text-sm text-teal-700 font-medium">Kecocokan Tipe Magang</p>
                                        <p class="text-xs text-gray-600">
                                            @php
                                                $jenisDisplay = $preferences_data['jenis_magang'] ?? 'N/A';
                                                if (strtolower($jenisDisplay) === 'berbayar') {
                                                    $jenisDisplay = 'Dibayar (Paid)';
                                                }
                                            @endphp
                                            {{ $jenisDisplay }}
                                        </p>
                                    </div>
                                </div>

                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Enhanced Search Section -->
        <div class="mb-6">
            <div class="max-w-4xl mx-auto">
                <form action="{{ route('mahasiswa.hasil-pencarian') }}" method="GET" class="space-y-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="query"
                            placeholder="Cari berdasarkan nama perusahaan, posisi, atau lokasi..."
                            class="w-full pl-12 pr-32 py-4 text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-lg"
                            autocomplete="off" />
                        <button type="submit"
                            class="absolute inset-y-0 right-0 px-6 py-2 m-2 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Cari
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Recommendations Title -->
        <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg mb-3.5">
            <h2 class="font-extrabold text-indigo-800 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
                Rekomendasi Tempat Magang
            </h2>
        </div>

        <!-- Recommendations List -->
        <div class="p-4 bg-gradient-to-r from-gray-50 to-slate-50 border border-gray-200 rounded-lg">
            <div class="space-y-2">
                @foreach ($top_internship_recommendation as $internship)
                    <div onclick="window.location='{{ route('mahasiswa.detail-lowongan-magang',  $internship->lowonganMagang->id) }}'"
                        role="button"
                        class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm border border-gray-200 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:border-blue-300 transition-all duration-200 hover:cursor-pointer hover:shadow-md">
                        <div class="flex items-center space-x-4">
                            <span
                                class="flex items-center justify-center w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-full text-sm font-bold shadow-sm">
                                {{ $loop->iteration }}
                            </span>
                            <div>
                                <p class="font-semibold text-gray-800 text-lg">
                                    {{ $internship->lowonganMagang->pekerjaan->nama }}
                                </p>
                                <p class="text-gray-600 font-medium">
                                    {{ $internship->lowonganMagang->perusahaan->nama  }}
                                </p>
                                <div class="flex flex-wrap gap-2 text-xs mt-2">
                                    <span
                                        class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full border border-blue-200">
                                        {{ ucfirst($internship->lowonganMagang->jenis_magang) }}
                                    </span>
                                    <span
                                        class="bg-green-100 text-green-700 px-3 py-1 rounded-full border border-green-200">
                                        {{ $internship->lowonganMagang->perusahaan->bidangIndustri->nama }}
                                    </span>
                                    <span
                                        class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full border border-yellow-200">
                                        {{ $internship->lowonganMagang->lokasi_magang->kategori_lokasi }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div
                                class="bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 px-3 py-2 rounded-lg border border-blue-200 font-medium">
                                Rank #{{ $loop->iteration }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="mb-8 space-y-4">
            <div class="p-6 bg-gradient-to-r from-gray-50 to-slate-50 border border-gray-300 rounded-lg">
                <h3 class="font-bold text-gray-700 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-gray-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    ⚠️ Tidak ada rekomendasi tersedia
                </h3>

                <div class="space-y-4">
                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                        <p class="text-gray-700 font-medium mb-2">Rekomendasi tidak tersedia karena:</p>
                        <ul class="text-gray-600 text-sm ml-4 list-disc space-y-1">
                            <li>Belum ada data lowongan magang yang sesuai</li>
                            <li>Preferensi pengguna mungkin belum diatur</li>
                            <li>Sistem rekomendasi sedang memproses data</li>
                        </ul>
                    </div>

                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-blue-800 font-semibold mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                            💡 Untuk mendapatkan rekomendasi:
                        </p>
                        <ol class="text-blue-700 ml-4 list-decimal space-y-1">
                            <li>Atur preferensi magang Anda</li>
                            <li>Pastikan profil Anda sudah lengkap</li>
                            <li>Coba cari lowongan secara manual</li>
                            <li>Hubungi admin jika masalah berlanjut</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
