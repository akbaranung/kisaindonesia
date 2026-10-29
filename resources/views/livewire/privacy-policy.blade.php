<div
    class="w-full min-h-screen bg-slate-50 dark:bg-slate-950 max-w-2xl mx-auto border-x border-slate-100 dark:border-slate-800 pb-28 transition-colors duration-200">
    <header
        class="sticky top-0 z-30 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-100 dark:border-slate-800 px-4 py-3.5 flex items-center justify-between gap-3 transition-colors">
        <div class="flex items-center gap-3">
            <a href="{{ url('/') }}" wire:navigate
                class="p-1.5 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-100 transition rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="font-black text-base text-slate-900 dark:text-slate-100 tracking-tight">Kebijakan Privasi
                </h1>
            </div>
        </div>

        {{-- Tombol Toggle Dark/Light Mode --}}
        <button onclick="toggleTheme()" type="button" aria-label="Toggle Dark Mode"
            class="p-2 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-100 transition rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
            <i class="fa-solid fa-moon hidden dark:block text-amber-300 text-sm"></i>
            <i class="fa-solid fa-sun block dark:hidden text-amber-500 text-sm"></i>
        </button>
    </header>

    <!-- Kartu Konten Utama -->
    <div
        class="m-2 bg-white dark:bg-slate-800 shadow-xl rounded-2xl p-3 border border-slate-100 dark:border-slate-700/60">

        <!-- Header Dokumen -->
        <div class="border-b border-slate-100 dark:border-slate-700/80 pb-6 mb-8">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-50 dark:bg-brand-950/50 text-brand-600 dark:text-brand-400 text-xs font-bold mb-4 border border-brand-100 dark:border-brand-800/40">
                <i class="fa-solid fa-shield-halved"></i> Legal & Compliance
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mb-3">
                Kebijakan Privasi Kisa Indonesia
            </h1>
            <div
                class="flex flex-col sm:flex-row sm:items-center gap-2 text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                <span><strong>Tanggal Berlaku:</strong> 29 Oktober 2026</span>
                <span class="hidden sm:inline">•</span>
                <span><strong>Terakhir Diperbarui:</strong> 29 Oktober 2026</span>
            </div>
        </div>

        <!-- Isi Kebijakan Privasi (Prosa/Typography Profesional) -->
        <div class="space-y-8 text-slate-600 dark:text-slate-300 text-sm sm:text-base leading-relaxed">

            <!-- 1. Pendahuluan -->
            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                    <span class="text-brand-600 dark:text-brand-400">1.</span> Pendahuluan
                </h2>
                <p class="mb-3">
                    Selamat datang di <strong>Kisa Indonesia</strong>, layanan penerbitan dan pemfokusan cerita
                    digital yang tersedia melalui situs web <a href="https://kisaindonesia.id"
                        class="text-brand-600 dark:text-brand-400 font-semibold hover:underline">https://kisaindonesia.id</a>
                    dan aplikasi mobile Kisa Indonesia (selanjutnya disebut sebagai <em>“Layanan”</em>).
                </p>
                <p class="mb-3">
                    Kisa Indonesia berkomitmen penuh untuk menjaga privasi, keamanan, dan pelindungan data pribadi
                    pengguna. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan,
                    melindungi, dan mengungkapkan data pribadi ketika pengguna mengakses situs web atau menggunakan
                    aplikasi Kisa Indonesia.
                </p>
                <p class="mb-3">
                    Kebijakan ini disusun dengan memperhatikan peraturan perundang-undangan yang berlaku di
                    Indonesia, termasuk <strong>Undang-Undang Nomor 27 Tahun 2022 tentang Pelindungan Data Pribadi
                        (UU PDP)</strong>.
                </p>
                <p>
                    Dengan menggunakan Layanan, pengguna menyatakan telah membaca, memahami, dan menyetujui
                    Kebijakan Privasi ini. Apabila pemrosesan data memerlukan persetujuan eksplisit (seperti
                    penandatanganan Perjanjian Kerja Sama atau akses fitur perangkat), Kisa Indonesia akan meminta
                    persetujuan pengguna sesuai dengan ketentuan yang berlaku.
                </p>
            </section>

            <!-- 2. Data Pribadi yang Kami Kumpulkan -->
            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                    <span class="text-brand-600 dark:text-brand-400">2.</span> Data Pribadi yang Kami Kumpulkan
                </h2>
                <p class="mb-3">Kami mengumpulkan data pribadi yang diperlukan untuk menyediakan dan
                    mengoperasikan Layanan secara optimal, aman, dan sesuai dengan fitur yang digunakan pengguna:
                </p>

                <div class="space-y-3 pl-2">
                    <div>
                        <strong class="text-slate-900 dark:text-slate-100 block mb-1">2.1. Data Akun dan
                            Identitas</strong>
                        <p>Data yang berkaitan dengan pendaftaran dan pengelolaan akun, seperti nama lengkap, nama
                            pengguna (<em>username</em>), nama pena (<em>pen name</em>), serta alamat email yang
                            diberikan pengguna saat membuat atau memperbarui profil.</p>
                    </div>
                    <div>
                        <strong class="text-slate-900 dark:text-slate-100 block mb-1">2.2. Alamat Email dan
                            Informasi Kontak</strong>
                        <p>Kami mengumpulkan alamat email dan informasi kontak untuk keperluan pendaftaran,
                            verifikasi akun, komunikasi transaksional, notifikasi pembaruan sistem, pengiriman
                            salinan dokumen Perjanjian Kerja Sama (PKS), serta penanganan dukungan atau keluhan
                            pengguna.</p>
                    </div>
                    <div>
                        <strong class="text-slate-900 dark:text-slate-100 block mb-1">2.3. Data Penulis, Legalitas,
                            dan Pembayaran Royalti</strong>
                        <p class="mb-1">Untuk penulis yang mengajukan monetisasi cerita atau terikat dalam kontrak
                            eksklusif/non-eksklusif, kami memproses data legalitas dan keuangan meliputi:</p>
                        <ul class="list-disc pl-5 space-y-1 text-slate-600 dark:text-slate-300">
                            <li>Nomor Induk Kependudukan (NIK/KTP) untuk verifikasi identitas legal dan pembuatan
                                dokumen Perjanjian Kerja Sama (PKS).</li>
                            <li>Alamat IP dan stempel waktu (<em>timestamp</em>) penandatanganan kontrak digital
                                (<em>audit log</em>).</li>
                            <li>Nomor rekening bank, nama pemilik rekening, dan nama bank untuk keperluan pencairan
                                royalti/pembayaran hasil monetisasi.</li>
                            <li>Riwayat transaksi pencairan dan status pembayaran royalti.</li>
                        </ul>
                    </div>
                    <div>
                        <strong class="text-slate-900 dark:text-slate-100 block mb-1">2.4. Data Pembayaran dan
                            Pembelian Koin (Pembaca)</strong>
                        <p>Apabila pembaca melakukan transaksi pembelian koin atau pembukaan bab berbayar melalui
                            Layanan, kami memproses data transaksi meliputi nomor referensi transaksi, nominal,
                            tanggal, dan status pembayaran. Informasi kartu kredit, e-wallet, atau kredensial
                            pembayaran langsung diproses secara aman oleh penyedia gerbang pembayaran (<em>payment
                                gateway</em>) terenkripsi.</p>
                    </div>
                    <div>
                        <strong class="text-slate-900 dark:text-slate-100 block mb-1">2.5. Data Lokasi</strong>
                        <p>Dengan izin pengguna, aplikasi dapat mengakses data lokasi kasar atau presisi untuk
                            keperluan fungsionalitas wilayah, penyesuaian konten lokal, dan keamanan sistem.
                            Pengguna dapat mengatur izin akses lokasi melalui pengaturan perangkat.</p>
                    </div>
                    <div>
                        <strong class="text-slate-900 dark:text-slate-100 block mb-1">2.6. Foto dan Gambar (Kamera &
                            Galeri)</strong>
                        <p>Pengguna dapat memberikan akses kamera atau galeri untuk unggah foto profil, gambar
                            sampul novel (<em>book cover</em>), atau verifikasi identitas penulis. Pengguna
                            disarankan untuk tidak mengunggah foto yang memuat data pribadi orang lain tanpa
                            kewenangan sah.</p>
                    </div>
                    <div>
                        <strong class="text-slate-900 dark:text-slate-100 block mb-1">2.7. Data Perangkat dan
                            Penggunaan (Log Teknis)</strong>
                        <p>Sistem kami mencatat informasi teknis seperti alamat IP, jenis perangkat, sistem operasi,
                            versi peramban (<em>browser</em>), versi aplikasi, catatan kesalahan (<em>error
                                log</em>), serta aktivitas membaca atau pengunggahan draf cerita.</p>
                    </div>
                </div>
            </section>

            <!-- 3. Tujuan Penggunaan Data Pribadi -->
            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                    <span class="text-brand-600 dark:text-brand-400">3.</span> Tujuan Penggunaan Data Pribadi
                </h2>
                <p class="mb-2">Kisa Indonesia menggunakan data pribadi untuk tujuan berikut:</p>
                <ol class="list-decimal pl-5 space-y-1.5 text-slate-600 dark:text-slate-300">
                    <li>Membuat, mengelola, dan memelihara akun pengguna, profil penulis, dan draf cerita.</li>
                    <li>Memverifikasi akun, identitas penulis, dan mengelola hak akses monetisasi cerita.</li>
                    <li>Menyiapkan, memproses, dan menyimpan salinan Perjanjian Kerja Sama (PKS) digital beserta
                        riwayat audit log-nya.</li>
                    <li>Memproses transaksi pembelian koin, pencairan royalti penulis, dan penanganan kendala
                        pembayaran.</li>
                    <li>Memasang batasan usia (<em>Age Confirmation</em>) pada kategori cerita/novel yang
                        membutuhkan penyesuaian audiens.</li>
                    <li>Menyediakan fitur yang membutuhkan akses lokasi, kamera, atau galeri sesuai permintaan dan
                        izin pengguna.</li>
                    <li>Menanggapi pertanyaan, permintaan bantuan, dan keluhan pengguna.</li>
                    <li>Menjaga keamanan sistem, mendeteksi pelanggaran hak cipta/plagiarisme, pencegahan
                        <em>scraping</em> massal, dan aktivitas ilegal.
                    </li>
                    <li>Memperbaiki kualitas, kinerja, dan pengalaman penggunaan Layanan.</li>
                    <li>Memenuhi kewajiban perpajakan, hukum, serta menindaklanjuti permintaan yang sah dari
                        instansi berwenang.</li>
                </ol>
            </section>

            <!-- 4. Dasar Pemrosesan Data Pribadi -->
            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                    <span class="text-brand-600 dark:text-brand-400">4.</span> Dasar Pemrosesan Data Pribadi
                </h2>
                <p class="mb-2">Kisa Indonesia memproses data pribadi berdasarkan dasar pemrosesan yang sah sesuai
                    UU PDP, meliputi:</p>
                <ul class="list-disc pl-5 space-y-1.5 text-slate-600 dark:text-slate-300">
                    <li><strong>Persetujuan Pengguna:</strong> Saat mendaftar akun, memberikan izin akses perangkat,
                        atau menyetujui Kebijakan Privasi ini.</li>
                    <li><strong>Pelaksanaan Perjanjian (Kontrak):</strong> Untuk menjalankan syarat & ketentuan
                        layanan atau dokumen Perjanjian Kerja Sama Penulis (PKS) yang ditandatangani.</li>
                    <li><strong>Pemenuhan Kewajiban Hukum:</strong> Untuk retensi data keuangan, pencatatan pajak
                        royalti, dan kepatuhan regulasi di Indonesia.</li>
                    <li><strong>Kepentingan Sah (<em>Legitimate Interest</em>):</strong> Untuk pemeliharaan keamanan
                        server, pencegahan penipuan/bot, serta peningkatan performa aplikasi.</li>
                </ul>
            </section>

            <!-- 5. Izin Perangkat dan Akses Fitur -->
            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                    <span class="text-brand-600 dark:text-brand-400">5.</span> Izin Perangkat dan Akses Fitur
                </h2>
                <p class="mb-3">
                    Aplikasi mobile dan situs Kisa Indonesia dapat meminta izin perangkat sesuai kebutuhan fitur
                    (Kamera/Galeri untuk unggah sampul buku/foto profil, Penyimpanan untuk cache bacaan, serta
                    Pemberitahuan).
                </p>
                <p>
                    Pengguna dapat mencabut izin akses kapan saja melalui menu Pengaturan pada perangkat. Pencabutan
                    izin tidak secara otomatis menghapus data yang telah diunggah sebelumnya; untuk penghapusan data
                    permanen, pengguna dapat menghubungi kami melalui kontak resmi.
                </p>
            </section>

            <!-- 6. Pembayaran dan Transaksi -->
            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                    <span class="text-brand-600 dark:text-brand-400">6.</span> Pembayaran dan Transaksi
                </h2>
                <p>
                    Seluruh transaksi pembayaran koin maupun pencairan royalti diproses menggunakan infrastruktur
                    terenkripsi melalui penyedia gerbang pembayaran (<em>payment gateway</em>) pihak ketiga yang
                    mematuhi standar keamanan industri perbankan. Kisa Indonesia hanya menyimpan catatan log
                    transaksi resmi untuk keperluan pembukuan dan verifikasi.
                </p>
            </section>

            <!-- 7. Cookie dan Teknologi Serupa -->
            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                    <span class="text-brand-600 dark:text-brand-400">7.</span> Cookie dan Teknologi Serupa
                </h2>
                <p>
                    Website Kisa Indonesia menggunakan <em>cookie</em> dan penyimpanan lokal (<em>local
                        storage</em>) untuk menjaga sesi login, menyimpan preferensi tampilan (mode gelap/terang,
                    ukuran teks bacaan), serta analitik lalu lintas situs. Pengguna dapat mengatur atau menolak
                    cookie melalui pengaturan peramban (<em>browser</em>).
                </p>
            </section>

            <!-- 8. Pembagian Data dengan Pihak Ketiga -->
            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                    <span class="text-brand-600 dark:text-brand-400">8.</span> Pembagian Data dengan Pihak
                    Ketiga
                </h2>
                <p class="mb-2">Kisa Indonesia <strong>tidak pernah menjual</strong> data pribadi pengguna. Data
                    hanya dapat dibagikan secara terbatas kepada:</p>
                <ul class="list-disc pl-5 space-y-1.5 text-slate-600 dark:text-slate-300">
                    <li>Penyedia infrastruktur server cloud, hosting, dan CDN.</li>
                    <li>Penyedia layanan pembayaran (bank/payment gateway) untuk transaksi koin dan royalti.</li>
                    <li>Penyedia layanan email transaksional untuk verifikasi dan pemberitahuan PKS.</li>
                    <li>Pihak berwenang apabila diwajibkan oleh peraturan perundang-undangan atau perintah
                        pengadilan di Indonesia.</li>
                </ul>
            </section>

            <!-- 9. Penyimpanan dan Penghapusan Data -->
            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                    <span class="text-brand-600 dark:text-brand-400">9.</span> Penyimpanan dan Penghapusan Data
                </h2>
                <p class="mb-2">
                    Data pribadi disimpan selama akun aktif atau selama diperlukan untuk pemenuhan hukum. Pengguna
                    berhak mengajukan penghapusan akun dan data pribadi secara permanen (<em>right to be
                        erasure</em>) melalui pengajuan email ke <strong>kisaindonesiaid@gmail.com</strong>.
                </p>
                <p>
                    Setelah verifikasi, data akan dihapus atau dianonimkan dari database aktif, kecuali data
                    tertentu yang wajib diarsip berdasarkan regulasi hukum yang berlaku (seperti audit keuangan).
                </p>
            </section>

            <!-- 10. Keamanan Data Pribadi -->
            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                    <span class="text-brand-600 dark:text-brand-400">10.</span> Keamanan Data Pribadi
                </h2>
                <p>
                    Kami menerapkan standar pengamanan teknis seperti enkripsi kata sandi (<em>hashing</em>),
                    protokol HTTPS/TLS, pembatasan akses basis data, serta pemantauan server berkala. Apabila
                    terjadi kegagalan pelindungan data (<em>data breach</em>), kami akan mengambil tindakan
                    penanganan dan pemberitahuan sesuai ketentuan UU PDP.
                </p>
            </section>

            <!-- 11. Hak Pengguna -->
            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                    <span class="text-brand-600 dark:text-brand-400">11.</span> Hak Pengguna
                </h2>
                <p class="mb-2">Sesuai dengan Undang-Undang Pelindungan Data Pribadi (UU PDP), pengguna berhak
                    untuk:</p>
                <ul class="list-disc pl-5 space-y-1.5 text-slate-600 dark:text-slate-300">
                    <li>Mendapatkan informasi mengenai pemrosesan data pribadi.</li>
                    <li>Mengakses dan memperoleh salinan data pribadi yang tersimpan.</li>
                    <li>Memperbarui atau memperbaiki data pribadi melalui dashboard profil.</li>
                    <li>Meminta penghentian pemrosesan atau penghapusan data pribadi.</li>
                    <li>Menarik kembali persetujuan atau mengajukan keberatan pemrosesan.</li>
                </ul>
            </section>

            <!-- 12. Privasi Anak -->
            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                    <span class="text-brand-600 dark:text-brand-400">12.</span> Privasi Anak
                </h2>
                <p>
                    Layanan ditujukan untuk pengguna berusia 13 tahun ke atas. Untuk konten bernuansa dewasa,
                    platform menyediakan fitur konfirmasi usia (<em>Age Confirmation</em>). Jika Anda mengetahui
                    adanya pengumpulan data anak di bawah umur tanpa izin, silakan hubungi kami untuk segera
                    ditinjau dan dihapus.
                </p>
            </section>

            <!-- 13. Transfer Data Lintas Negara -->
            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                    <span class="text-brand-600 dark:text-brand-400">13.</span> Transfer Data Lintas Negara
                </h2>
                <p>
                    Data pribadi disimpan dan diproses di wilayah Indonesia. Jika terdapat penggunaan layanan cloud
                    atau server global pihak ketiga, transfer data dipastikan memenuhi standar pelindungan data yang
                    setara dengan ketentuan UU PDP.
                </p>
            </section>

            <!-- 14. Perubahan Kebijakan Privasi -->
            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                    <span class="text-brand-600 dark:text-brand-400">14.</span> Perubahan Kebijakan Privasi
                </h2>
                <p>
                    Kebijakan Privasi ini dapat diperbarui sewaktu-waktu mengikuti perkembangan fitur atau regulasi
                    hukum. Versi terbaru akan selalu dipublikasikan melalui halaman ini dengan tanggal pembaruan
                    yang akurat.
                </p>
            </section>

            <!-- 15. Kontak -->
            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-3 flex items-center gap-2">
                    <span class="text-brand-600 dark:text-brand-400">15.</span> Kontak & Pengelola Layanan
                </h2>
                <p class="mb-3">
                    Jika Anda memiliki pertanyaan, permintaan pelaksanaan hak data, atau keluhan terkait Kebijakan
                    Privasi ini, silakan hubungi kami melalui:
                </p>
                <div class="p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl space-y-2 text-xs sm:text-sm font-medium">
                    <p><strong>Nama Layanan:</strong> Kisa Indonesia</p>
                    <p><strong>Situs Web:</strong> <a href="https://kisaindonesia.id"
                            class="text-brand-600 dark:text-brand-400 hover:underline">https://kisaindonesia.id</a>
                    </p>
                    <p><strong>Email Resmi:</strong> <a href="mailto:kisaindonesiaid@gmail.com"
                            class="text-brand-600 dark:text-brand-400 hover:underline">kisaindonesiaid@gmail.com</a>
                    </p>
                </div>
            </section>

        </div>

        <!-- Footer Tombol Kembali -->
        <div
            class="mt-12 pt-6 border-t border-slate-100 dark:border-slate-700/80 flex flex-col sm:flex-row items-center justify-between gap-4">
            <a href="{{ url('/') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition">
                <i class="fa-solid fa-arrow-left"></i> <span>Kembali ke Beranda</span>
            </a>
            <span class="text-xs text-slate-400">© {{ date('Y') }} Kisa Indonesia. All rights reserved.</span>
        </div>

    </div>
</div>
