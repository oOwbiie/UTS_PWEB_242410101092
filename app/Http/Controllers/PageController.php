<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLogin()
    {
        // Kalau sudah login, langsung ke dashboard
        if (session('username')) {
            return redirect()->route('dashboard');
        }

        return view('login', [
            'title' => 'Login — MoodFlow'
        ]);
    }

    /**
     * Proses form login & teruskan data username ke session
     */
    public function processLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|min:3',
            'password' => 'required|min:4',
        ]);

        // Simulasi autentikasi — tidak pakai DB (sesuai scope tugas)
        $demoUsers = [
            'rizky'    => 'rizky123',
            'andi'     => 'andi123',
            'sari'     => 'sari123',
            'demo'     => 'demo1234',
        ];

        $username = strtolower(trim($request->input('username')));
        $password = $request->input('password');

        if (array_key_exists($username, $demoUsers) && $demoUsers[$username] === $password) {
            // Simpan username ke session
            $request->session()->put('username', $username);
            $request->session()->put('login_time', now()->format('d M Y, H:i'));
            return redirect()->route('dashboard')->with('success', "Selamat datang, {$username}!");
        }

        return back()->withErrors(['login' => 'Username atau password salah.'])->withInput();
    }

    /**
     * Logout — hapus session
     */
    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login')->with('info', 'Kamu sudah logout.');
    }

    /**
     * Dashboard — tampilkan statistik & rekomendasi
     */
    public function dashboard(Request $request)
    {
        if (!session('username')) {
            return redirect()->route('login')->withErrors(['login' => 'Silakan login terlebih dahulu.']);
        }

        $username = session('username');

        // Data mood historis (dari session/simulasi)
        $moodHistory = session('mood_data', []);

        // Hitung rata-rata untuk chart
        $chartData = $this->buildChartData($moodHistory);

        // Rekomendasi berdasarkan data terbaru
        $rekomendasi = $this->getRekomendasi($moodHistory);

        // Statistik ringkasan
        $stats = [
            'total_input'       => count($moodHistory),
            'rata_mood'         => $this->average($moodHistory, 'mood'),
            'rata_energi'       => $this->average($moodHistory, 'energi'),
            'hari_produktif'    => $this->countProduktif($moodHistory),
        ];

        return view('dashboard', compact('username', 'chartData', 'rekomendasi', 'stats'));
    }

    /**
     * Halaman Pengelolaan — input & tampilkan data harian
     */
    public function pengelolaan(Request $request)
    {
        if (!session('username')) {
            return redirect()->route('login')->withErrors(['login' => 'Silakan login terlebih dahulu.']);
        }

        $username = session('username');
        $moodData = session('mood_data', []);

        // Daftar pilihan aktivitas
        $daftarAktivitas = [
            'Belajar / Kuliah',
            'Bekerja / Mengerjakan Tugas',
            'Olahraga',
            'Istirahat / Tidur',
            'Nonton / Hiburan',
            'Berkumpul dengan Teman',
            'Meditasi / Refleksi',
            'Membaca',
            'Aktivitas Kreatif',
        ];

        // Pilihan mood dengan label deskriptif
        $daftarMood = [
            5 => '😄 Sangat Bahagia',
            4 => '🙂 Bahagia',
            3 => '😐 Biasa Saja',
            2 => '😕 Kurang Baik',
            1 => '😞 Buruk',
        ];

        // Pilihan energi
        $daftarEnergi = [
            5 => '⚡ Sangat Berenergi',
            4 => '💪 Berenergi',
            3 => '🔋 Cukup',
            2 => '😴 Lelah',
            1 => '🪫 Sangat Lelah',
        ];

        return view('pengelolaan', compact(
            'username',
            'moodData',
            'daftarAktivitas',
            'daftarMood',
            'daftarEnergi'
        ));
    }

    /**
     * Simpan data mood & energi ke session
     */
    public function simpanData(Request $request)
    {
        if (!session('username')) {
            return redirect()->route('login');
        }

        $request->validate([
            'mood'      => 'required|integer|between:1,5',
            'energi'    => 'required|integer|between:1,5',
            'aktivitas' => 'required|string|max:100',
            'catatan'   => 'nullable|string|max:300',
        ]);

        $existing = session('mood_data', []);

        $newEntry = [
            'id'        => count($existing) + 1,
            'tanggal'   => now()->format('d M Y'),
            'waktu'     => now()->format('H:i'),
            'mood'      => (int) $request->input('mood'),
            'energi'    => (int) $request->input('energi'),
            'aktivitas' => $request->input('aktivitas'),
            'catatan'   => $request->input('catatan', '-'),
        ];

        $existing[] = $newEntry;

        // Simpan ke session (maks 30 entri terakhir)
        session(['mood_data' => array_slice($existing, -30)]);

        return redirect()->route('pengelolaan')->with('success', 'Data berhasil disimpan!');
    }

    /**
     * Halaman Profile
     */
    public function profile(Request $request)
    {
        if (!session('username')) {
            return redirect()->route('login')->withErrors(['login' => 'Silakan login terlebih dahulu.']);
        }

        $username = session('username');
        $loginTime = session('login_time', '-');
        $moodHistory = session('mood_data', []);

        // Info profil simulasi berdasarkan username
        $profileData = [
            'rizky' => ['nama_lengkap' => 'Rizky Pratama',   'email' => 'rizky@moodflow.id',  'bio' => 'Mahasiswa yang suka refleksi diri setiap hari.'],
            'andi'  => ['nama_lengkap' => 'Andi Setiawan',   'email' => 'andi@moodflow.id',   'bio' => 'Percaya bahwa produktivitas dimulai dari keseimbangan mood.'],
            'sari'  => ['nama_lengkap' => 'Sari Dewi',       'email' => 'sari@moodflow.id',   'bio' => 'Mental health advocate & journaling enthusiast.'],
            'demo'  => ['nama_lengkap' => 'Demo User',       'email' => 'demo@moodflow.id',   'bio' => 'Akun demo untuk eksplorasi MoodFlow.'],
        ];

        $info = $profileData[$username] ?? [
            'nama_lengkap' => ucfirst($username),
            'email'        => $username . '@moodflow.id',
            'bio'          => 'Pengguna MoodFlow yang rajin mencatat mood harian.',
        ];

        // Statistik untuk profil
        $stats = [
            'total_catatan'  => count($moodHistory),
            'mood_terbaik'   => $this->getBest($moodHistory, 'mood'),
            'energi_terbaik' => $this->getBest($moodHistory, 'energi'),
            'aktivitas_fav'  => $this->getAktivitasFavorit($moodHistory),
            'bergabung'      => 'April 2025',
        ];

        return view('profile', compact('username', 'loginTime', 'info', 'stats', 'moodHistory'));
    }

    // ═══════════════════════════════
    //  Helper Methods (private)
    // ═══════════════════════════════

    private function average(array $data, string $key): float
    {
        if (empty($data)) return 0;
        $total = array_sum(array_column($data, $key));
        return round($total / count($data), 1);
    }

    private function countProduktif(array $data): int
    {
        return count(array_filter($data, fn($d) => $d['mood'] >= 4 && $d['energi'] >= 4));
    }

    private function getBest(array $data, string $key): int
    {
        if (empty($data)) return 0;
        return max(array_column($data, $key));
    }

    private function getAktivitasFavorit(array $data): string
    {
        if (empty($data)) return '-';
        $counts = array_count_values(array_column($data, 'aktivitas'));
        arsort($counts);
        return array_key_first($counts) ?? '-';
    }

    private function buildChartData(array $data): array
    {
        // Ambil 7 entri terakhir untuk grafik
        $slice = array_slice($data, -7);

        return [
            'labels' => array_map(fn($d) => $d['tanggal'] . ' ' . $d['waktu'], $slice),
            'mood'   => array_column($slice, 'mood'),
            'energi' => array_column($slice, 'energi'),
        ];
    }

    private function getRekomendasi(array $data): array
    {
        if (empty($data)) {
            return [
                'icon'  => '✨',
                'judul' => 'Mulai Catat Harimu',
                'teks'  => 'Belum ada data. Isi pengelolaan mood pertamamu untuk mendapat rekomendasi personal!',
                'warna' => 'neutral',
            ];
        }

        $last = end($data);
        $mood   = $last['mood'];
        $energi = $last['energi'];

        if ($mood >= 4 && $energi >= 4) {
            return [
                'icon'  => '🚀',
                'judul' => 'Mode Produktif Aktif!',
                'teks'  => 'Mood & energimu sedang puncak. Ini saat terbaik untuk mengerjakan tugas berat, belajar materi baru, atau fokus pada proyek penting.',
                'warna' => 'green',
            ];
        }

        if ($mood >= 4 && $energi <= 2) {
            return [
                'icon'  => '🧘',
                'judul' => 'Mood Oke, Tapi Istirahat Dulu',
                'teks'  => 'Semangatmu bagus tapi tubuhmu perlu istirahat. Coba power nap 20 menit atau lakukan peregangan ringan sebelum lanjut beraktivitas.',
                'warna' => 'yellow',
            ];
        }

        if ($mood <= 2 && $energi >= 4) {
            return [
                'icon'  => '🏃',
                'judul' => 'Gerak Bisa Mengangkat Mood!',
                'teks'  => 'Energimu tersedia tapi mood sedang turun. Coba olahraga ringan, jalan-jalan, atau aktivitas fisik yang menyenangkan.',
                'warna' => 'blue',
            ];
        }

        if ($mood <= 2 && $energi <= 2) {
            return [
                'icon'  => '💙',
                'judul' => 'Waktu Self-Care',
                'teks'  => 'Mood dan energimu sedang rendah. Prioritaskan istirahat, makan bergizi, dan lakukan hal kecil yang membuatmu senang. Besok pasti lebih baik.',
                'warna' => 'purple',
            ];
        }

        return [
            'icon'  => '🎯',
            'judul' => 'Jaga Keseimbangan',
            'teks'  => 'Kondisimu cukup stabil. Lakukan aktivitas ringan-sedang dan tetap jaga ritme tidur yang konsisten.',
            'warna' => 'neutral',
        ];
    }
}
