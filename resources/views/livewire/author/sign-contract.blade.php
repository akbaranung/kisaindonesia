<div
    class="max-w-3xl mx-auto p-4 sm:p-6 bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-800 my-6">

    {{-- Header Judul Kontrak --}}
    <div class="text-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
        <span class="text-[10px] font-black uppercase tracking-wider text-brand-600 dark:text-brand-400"></span>
        <h1 class="text-base sm:text-lg font-black text-slate-900 dark:text-slate-100 mt-1">
            PERJANJIAN KERJA SAMA PENERBITAN DAN LISENSI EKSKLUSIF KARYA
        </h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            Penandatanganan Master Agreement Digital Penulis (Cukup dilakukan 1 kali)
        </p>
    </div>

    {{-- KOTAK DOKUMEN SURAT PERJANJIAN (SCROLLABLE & READ-ONLY) --}}
    <div
        class="h-[420px] overflow-y-auto p-4 sm:p-6 bg-slate-50 dark:bg-slate-950/60 rounded-2xl border border-slate-200 dark:border-slate-800 text-xs text-slate-700 dark:text-slate-300 space-y-4 leading-relaxed font-sans text-justify select-none overscroll-contain">

        <p class="text-center font-black text-slate-900 dark:text-slate-100">
            PERJANJIAN KERJA SAMA PENERBITAN DAN LISENSI EKSKLUSIF KARYA
        </p>
        @php
            setlocale(LC_TIME, 'id_ID');
            \Carbon\Carbon::setLocale('id');
        @endphp
        <p>
            Pada hari ini, <span class="font-bold underline">{{ now()->translatedFormat('l') }}</span> tanggal <span
                class="font-bold underline">{{ now()->translatedFormat('d') }}</span> bulan <span
                class="font-bold underline">{{ now()->translatedFormat('F') }}</span> tahun <span
                class="font-bold underline">{{ now()->translatedFormat('Y') }}</span>, bertempat di <span
                class="font-bold underline">Jakarta</span>, telah dibuat dan disepakati Perjanjian Kerja Sama Penerbitan
            dan Lisensi Eksklusif Karya ("Perjanjian") oleh dan antara:
        </p>

        <div class="pl-3 border-l-2 border-brand-500 space-y-1">
            <p class="font-bold text-slate-900 dark:text-slate-100">PIHAK PERTAMA</p>
            <p><strong>KISA Indonesia ID</strong>, berkedudukan di Gedung Graha Dirgantara, Jalan Protokol Halim
                Perdanakusuma No. 8, Jakarta Timur, dalam hal ini diwakili secara sah oleh <strong>Citra Friska
                    Nabila</strong> selaku Direktur Editorial KISA Indonesia ID, selanjutnya disebut sebagai
                <strong>PIHAK PERTAMA</strong>.
            </p>
        </div>

        <div class="pl-3 border-l-2 border-amber-500 space-y-1">
            <p class="font-bold text-slate-900 dark:text-slate-100">PIHAK KEDUA</p>
            <p><strong>Nama Lengkap:</strong> {{ $fullName ?: '________________________' }}</p>
            <p><strong>Tempat/Tgl Lahir:</strong> {{ $pob ?: '__________' }},
                {{ $dob ? \Carbon\Carbon::parse($dob)->translatedFormat('d F Y') : '__________' }}</p>
            <p><strong>Alamat Domisili:</strong> {{ $address ?: '________________________' }}</p>
            <p><strong>No. KTP (NIK):</strong> {{ $ktpNumber ?: '________________________' }}</p>
            <p class="text-[11px] opacity-80">Bertindak atas nama diri sendiri selaku pencipta dan/atau pemegang hak
                cipta atas karya, selanjutnya disebut sebagai <strong>PIHAK KEDUA</strong>.</p>
        </div>

        <p>
            PARA PIHAK sepakat untuk mengikatkan diri dalam Perjanjian Kerja Sama Penerbitan dan Lisensi Eksklusif Karya
            dengan syarat dan ketentuan sebagaimana diatur dalam pasal-pasal Perjanjian ini.
        </p>

        <hr class="border-slate-200 dark:border-slate-800">

        <h3 class="font-black text-slate-900 dark:text-slate-100">Pasal 1 • Definisi</h3>
        <p>Dalam Perjanjian Kerja Sama Penrbitan dan Lisensi Eksklusif ini, kecuali apabila konteks menentukan lain,
            istilah-istilah di bawah ini mempunyai pengertian sebagai berikut:</p>
        <div class="pl-5">
            <ol class="list-decimal">
                <li><strong>KISA Indonesia ID</strong> adalah platform digital yang bergerak di bidang penerbitan
                    digital,
                    distribusi karya sastra, novel daring, cerita bersambung, serta layanan literasi digital.</li>
                <li><strong>Lisensi Eksklusif</strong> adalah pemberian hak secara eksklusif oleh Penulis kepada KISA
                    untuk
                    menerbitkan, memperbanyak, mendistribusikan, mempromosikan, mengomersialisasikan Karya tanpa
                    mengalihkan
                    kepemilikan Hak Cipta kepada KISA.</li>
                <li><strong>Penulis</strong> adalah orang perseorangan yang bertindak sebagai pencipta dan/atau pemegang
                    hak
                    cipta atas karya yang menjadi objek Perjanjian ini serta memberikan lisensi eksklusif kepada KISA
                    sesuai
                    dengan ketentuan dalam Perjanjian ini.</li>
                <li>Para Pihak adalah KISA selaku PIHAK PERTAMA dan Penulis selaku PIHAK KEDUA yang secara bersama-sama
                    meningkatkan diri dalam Perjanjuan ini.</li>
                <li>Karya adalah novel, cerita bersambung, cerita pendek, puisi, naskah, meskipun</li>
                <li>Platform adalah seluruh media digital milik atau yang dikelola oleh KISA, termasuk aplikasi, situs
                    web, dashboard, media sosial, serta media digital lainnya yang digunakan untuk menerbitkan,
                    memasarkan, mendistribusikam, dan mengomersialisasikan Karya.</li>
                <li>Lisensi Eksklusif adalah pemberian hak secara eksklusif oleh Penulis kepada KISA untuk menerbitkan,
                    memperbanyak, mendistribusikan, mempromosikan, mengomersialisasikan, dan memanfaatkan Karya sesuai
                    dengan ketentuan dalam Perjanjian ini, tanpa mengalihkan kepemilikan hak cipta kepada KISA.</li>
                <li>Hak Cipta adalah hak eksklusif Penulis sebagaimana diatur dalam peraturan perundang-undangan
                    mengenai hak cipata yang berlaku di Republik Indonesia.</li>
                <li>Hak Ekonomi adalah hak untuk memperoleh manfaat ekonomi atas Karya sebagaimana diatur dalam
                    Perjanjian ini.</li>
                <li>Hak Moral adalah hak yang melekat secara abadi pada diri Penulis sebagai pencipta Karya sesuai
                    dengan ketentuan peraturan perundang-undangan.</li>
                <li>Royalti adalah sejumlah imbalan yang menjadi hak Penulis atas pemanfaatan komersial Karya oleh KISA
                    berdasarkan mekanisme dan perhitungan sebagaimana diatur dalam Perjanjian ini.</li>
                <li>Pendapatan Bersih adalah seluruh pendapatan yang diterima KISA dari komersialisasi Karya setelah
                    dikurangi pajak, biaya administrasi, biaya transaksi, biaya distribusi, potongan platform pihak
                    ketiga, serta biaya lain yang secara langsung berkaitan dengan transaksi tersebut.</li>

                <li>Informasi Rahasia adalah seluruh informasi yang tidak tersedia untuk umum, termasuk data Penulis,
                    data pengguna, laporan pendapatan, strategi bisnis, data pemasaran, dokumen internal,
                    source code, maupun informasi lain yang oleh salah satu pihak dinyatakan sebagai rahasia.</li>
                </li>
                <li>Hari Kerja adalah hari Senin sampai dengan Jumat, tidak termasuk hari libur nasional yang ditetapkan
                    oleh Pemerintah Republik Indonesia.</li>
                </li>
                <li>Keadaan Kahar (Force Majeure) adalah setiap keadaan di luar kemampuan dan kendali Para Pihak
                    yang mengakibatkan sebagian atau seluruh kewajiban dalam Perjanjian ini tidak dapat dilaksanakan,
                    termasuk namun tidak terbatas pada bencana alam, perang, kerusuhan, kebakaran, epidemi, pandemi,
                    gangguan sistem nasional, atau kebijakan pemerintah.
                </li>
                <li>Addendum adalah perubahan atau penambahan terhadap Perjanjian ini yang dibuat secara tertulis,
                    disepakati, dan ditandatangani oleh Para Pihak.
                </li>
            </ol>
        </div>

        <h3 class="font-black text-slate-900 dark:text-slate-100">Pasal 2 • Penafsiran</h3>
        <div class="pl-5">
            <ul class="list-decimal">
                <li>Seluruh istilah yang digunakan dalam Perjanjian ini wajib ditafsirkan sesuai dengan ketentuan
                    peraturan perundang-undangan Republik Indonesia.</li>
                <li>Judul Bab, Pasal, dan Ayat dalam PErjanjian ini dibuat untuk memudahkan pembacaan dan tidak
                    memengaruhi penafsiran isi Perjanjian.</li>
                <li>Apabila terdapat ketentuan dalam Perjanjian ini yang dinyatakan tidak sah atau tidak daapt
                    dilaksanakan berdasarkan putusan pengadilan atau ketentuan hukum yang berlaku, ketentuan tersebut
                    tidak memengaruhi keabsahan ketentuan lainnya dalam Perjanjian ini.</li>
                <li>Setiap perubahan terhadap Perjanjuan ini hanya sah apabila secra tertulis dan ditandatangani oleh
                    Para Pihak.</li>
                <li>Hal-hal yang belum diatur dalam Perjanjian ini akan ditetapkan kemudian berdasarkan kesepakatan
                    tertulis Para Pihak melalui Addendum yang merupakan bagian yang tidak terpisahkan dari Perjanjian
                    ini.</li>
            </ul>
        </div>

        <h3 class="font-black text-slate-900 dark:text-slate-100">Pasal 3 • Objek Perjanjian</h3>
        <div class="pl-5">
            <ul class="list-decimal">
                <li>PIHAK PERTAMA dan PIHAK KEDUA sepakat bahwa objek Perjanjian ini adalah pemberian Lisensi Eksklusif
                    oleh PIHAK KEDUA kepada PIHAK PERTAMA atas Karya sebagaimana tercantum dalam Lampiran Perjanjian ini
                    untuk diterbitkan, dipublikasikan, didistribusikan, dipromosikan, dan dikomersialisasikan melalui
                    Platform KISA Indonesia ID.</li>
                <li>Karya sebagaimana dimaksud pada ayat (1) melipuiti novel, cerita bersambung, cerita pendek, puisi,
                    atau bentuk karya tulis lainnya yang telah disepakati oleh PARA PIHAK sebagai objek Perjanjian.</li>
                <li>Identitas Karya yang menjadi objek Perjanjian paling sedikit memuat:</li>
                <div>
                    <ol style="list-style-type: lower-alpha;" class="ml-6">
                        <li>Judul Karya;</li>
                        <li>Nama Penulis atau nama pena;</li>
                        <li>Genre;</li>
                        <li>Sinopsis;</li>
                        <li>Status Karya;</li>
                        <li>Jumlah bab yang telah tersedia pada saat penandatanganan Perjanjian; dan</li>
                        <li>Informasi lain yang dianggap perlu oleh PARA PIHAK.</li>
                    </ol>
                </div>
                <li>Identitas Karya sebagaimana dimaksud pada ayat (3) dituangkan dalam Lampiran yang merupakan bagian
                    yang tidak terpisahkan dari Perjanjian ini.</li>
                <li>Dalam hal PIHAK KEDUA bermaksud menambahkan Karya lain ke dalam kerja sama, penambahan tersebut
                    wajib disepakati secara tertulis oleh PARA PIHAK melalui addendum atau lampiran baru.</li>

            </ul>
        </div>

        <h3 class="font-black text-slate-900 dark:text-slate-100">Pasal 4 • Pemberian Lisensi Eksklusif</h3>
        <div class="pl-5">
            <ul class="list-decimal">
                <li>PIHAK PEDUA dengan ini memberikan kepada PIHAK PERTAMA hak lisensi yang bersifat eksklusif untuk
                    menerbitkan, memperbanyak, menyimpan, menampilkan, mendistribusikan, mempromosikan,
                    mengomersialisasikan, serta memanfaatkan Karya selama jangka waktu Perjanjian.</li>
                <li>Selama Perjanjian ini berlaku, PIHAK KEDUA tidak diperkenakan menerbitkan, mengunggah,
                    mendistribusikan, melisensikan, atau mengomersialisasikan Karya yang menjadi objek Perjanjian kepada
                    pihak lain tanpa persetujuan tertulis terlebih dahulu dari PIHAK PERTAMA.</li>
                <li>Lisensi eksklusif sebagaimana dimaksud pada ayat (1) meliputi penerbitan melalui aplikasi KISA
                    Indonesia ID, situs web resmi KISA Indonesia ID, media sosial resmi KISA Indonesia ID, maupun media
                    digital lain yang dimiliki atau ditunjuk oleh PIHAK PERTAMA.</li>
                <li>Pemberian Lisensi Eksklusif berdasarkan Perjanjian ini tidak mengakibatkan beralihnya Hak Cipta atas
                    Karya dari PIHAK KEDUA kepada PIHAK PERTAMA, kecuali ditentukan lain dalam Perjanjian atau addendum
                    yang disepakati PARA PIHAK.
                </li>
                <li>PIHAK PERTAMA berhak menggunakan judul Karya, sampul, sinopsis, kutipan, ilustrasi, serta materi
                    promosi lainnya yang berkaitan dengan Karya untuk kepentingan promosi dan pemasaran selama tidak
                    bertentangan dengan ketentuan peraturan perundang-undangan.</li>
            </ul>
        </div>
        <h3 class="font-black text-slate-900 dark:text-slate-100">Pasal 5 • Tujuan Kerja Sama</h3>
        <div class="pl-5">
            <ul class="list-decimal">
                <li>Kerja sama ini bertujuan untuk mengembangkan potensi komersial Karya melalui sistem penerbitan
                    digital yang dikelola oleh PIHAK PERTAMA dengan tetap menghormati hak moral dan hak ekonomi PIHAK
                    KEDUA sebagai pencipta.</li>
                <li>PIHAK PERTAMA berkewajiban mengelola Karya secara profesional melalui proses editorial, publikasi,
                    promosi, distribusi, dan monetisasi sesuai dengan kebijakan internal KISA Indonesia ID.
                </li>
                <li>PIHAK KEDUA berkewajiban mendukung pelaksanaan kerja sama dengan menyediakan Karya yang berkualitas,
                    memenuhi ketentuan Perjanjian, serta berpartisipasi dalam kegiatan promosi apabila diperlukan
                    berdasarkan kesepakatan PARA PIHAK.</li>
                <li>PARA PIHAK sepakat bahwa hubungan hukum yang timbul dari Perjanjian ini merupakan hubungan kemitraan
                    dalam bidang penerbitan digital dan pemberian lisensi eksklusif, sehingga tidak menimbulkan hubungan
                    kerja sebagaimana dimaksud dalam ketentuan peraturan perundang-undangan di bidang ketenagakerjaan.
                </li>
            </ul>
        </div>

        <h3 class="font-black text-slate-900 dark:text-slate-100">Pasal 6 • Hak dan Kewajiban PIHAK PERTAMA</h3>
        <div class="pl-5">
            <ul class="list-decimal">
                <li>PIHAK PERTAMA berhak memperoleh Lisensi Eksklusif atar Karya sebagaimana diatur dalam Perjanjian ini
                    untuk diterbitkan, dipublikasikan, didistribusikan, dipromosikan, dan dimonetisasi selama jangka
                    waktu Perjanjian.</li>
                <li>PIHAK PERTAMA berhak melakukan proses seleksi, penyuntingan, penyusunan tata letak, desain sampul,
                    koreksi, dan penyesuaian teknis terhadap Karya, dengan tetap menghormati substansi, alur cerita, dan
                    hak moral PIHAK KEDUA sebagai pencipta.
                </li>
                <li>PIHAK PERTAMA berhak menentukan waktu penerbitan, jadwal publikasi, strategi promosi, metode
                    distribusi, serta bentuk monetisasi KArya sesuai dengan kebijakan internal KISA Indonesia ID.</li>
                <li>PIHAK PERTAMA berhak menggunakan judul Karya, nama pena Penulis, sinopsis, ilustrasi, kutipan,
                    sampul, serta materi promosi lainnya untuk kepentingan pemasaran dan promosi Karya selama tidak
                    bertentangan dengan ketentuan peraturan perundang-undangan.
                </li>
                <li>PIHAK PERTAMA berkewajiban menerbitkan dan mengelola KArya secara profesional sesuai dengan standar
                    operasional yang berlaku pada KISA Indonesia ID.</li>
                <li>PIHAK PERTAMA berkewajiban menjaga kerahasiaan data pribadi PIHAK KEDUA serta informasi lain yang
                    bersifat rahasia sesuai dengan ketentuan Perjanjian ini.</li>
                <li>PIHAK PERTAMA berkewajiban memberikan laporan pendapatan dan membayarkan royalti kepada PIHAK KEDUA
                    sesuai dengan mekanisme yang diatur dalam Perjanjian ini.</li>
                <li>PIHAK PERTAMA berkewajiban mendaga reputasi Karya dan tidak melakukan perubahan yang mengubah
                    substansi cerita tanpa persetujuan PIHAK KEDUA.</li>
            </ul>
        </div>

        <h3 class="font-black text-slate-900 dark:text-slate-100">Pasal 7 • Hak dan Kewajiban PIHAK KEDUA</h3>
        <div class="pl-5">
            <ul class="list-decimal">
                <li>PIHAK KEDUA berhak tetap diakui sebagai pencipta dan pemegang Hak Cipta atas Karya sesuai dengan
                    ketentuan peraturan perundang-undangan yang berlaku.
                </li>
                <li>PIHAK KEDUA berhak memperoleh royalti atau bentuk kompensasi lainnya sesuai dengan ketentuan dalam
                    Perjanjian ini.
                </li>
                <li>PIHAK KEDUA berhak memperoleh informasi mengenai perkembangan publikasi, promosi, dan monetisasi
                    Karya melalui mekanisme yang ditetapkan oleh PIHAK PERTAMA.</li>
                <li>PIHAK KEDUA berkewajiban menyerahkan Karya yang merupakan hasil ciptaan sendiri, asli, serta tidak
                    melanggar hak cipta, hak kekayaan intelektual, atau hak pihak lain.
                </li>
                <li>PIHAK KEDUA berkewajiban menyelesaikan Karya sesuai dengan jadwal, target penulisan, atau ketentuan
                    lain yang disepakati oleh PARA PIHAK.</li>
                <li>Selama Perjanjian ini berlaku, PIHAK KEDUA tidak diperkenankan menerbitkan, mendistribusikan,
                    melisensikan, atau mengomersialisasikan Karya yang menjadi objek Perjanjian melalui pihak lain tanpa
                    persetujuan tertulis dari PIHAK PERTAMA.
                </li>
                <li>PIHAK KEDUA berkewajiban memberikan informasi yang benar mengenai kepemilikan Hak Cipta atas Karya
                    serta bertanggung jawab penuh apabila di kemudian hari terdapat tuntutan dari pihak ketiga atas
                    Karya tersebut.
                </li>
                <li>PIHAK KEDUA berkewajiban menjaga nama baik KISA Indonesia ID dan tidak melakukan tindakan yang dapat
                    merugikan reputasi maupun kepentingan bisnis PIHAK PERTAMA selama Perjanjian ini masih berlaku.</li>
            </ul>
        </div>

        <h3 class="font-black text-slate-900 dark:text-slate-100">Pasal 8 • Hak dan Kewajiban Bersama</h3>
        <div class="pl-5">
            <ul class="list-decimal">
                <li>PARA PIHAK sepakat untuk melaksanakan Perjanjian ini dengan itikad baik, profesional, transparan,
                    serta saling menghormati hak dan kewajiban masing-masing.

                </li>
                <li>PARA PIHAK wajib menjaga kerahasiaan seluruh informasi yang diperoleh selama pelaksanaan Perjanjian,
                    kecuali diwajibkan oleh ketentuan peraturan perundang-undangan atau berdasarkan persetujuan tertulis
                    dari pihak lainnya.
                </li>
                <li>PARA PIHAK sepakat untuk saling memberikan informasi yang diperlukan guna mendukung pelaksanaan
                    Perjanjian secara efektif.
                </li>
                <li>Setiap perubahan terhadap ruang lingkup kerja sama, hak, kewajiban, maupun ketentuan lainnya hanya
                    dapat dilakukan berdasarkan kesepakatan tertulis yang ditandatangani oleh PARA PIHAK.
                </li>
                <li>PARA PIHAK sepakat untuk mengutamakan penyelesaian setiap perbedaan pendapat atau perselisihan
                    melalui musyawarah untuk mencapai mufakat sebelum menempuh upaya hukum sesuai dengan ketentuan
                    Perjanjian ini.
                </li>

            </ul>
        </div>

        <h3 class="font-black text-slate-900 dark:text-slate-100">Pasal 9 • Proses Editiroal</h3>
        <div class="pl-5">
            <ul class="list-decimal">
                <li>Dalam pelaksanaan proses editorial, PIHAK PERTAMA berhak melakukan evaluasi terhadap Karya yang
                    diserahkan oleh PIHAK KEDUA sesuai dengan standar editorial KISA Indonesia ID.
                </li>
                <li>Apabila berdasarkan hasil evaluasi ditemukan bagian Karya yang memerlukan penyempurnaan, PIHAK
                    PERTAMA akan menghubungi PIHAK KEDUA dan menyampaikan daftar catatan editorial, saran perbaikan,
                    serta rekomendasi revisi secara tertulis.
                </li>
                <li>PIHAK KEDUA berkewajiban melakukan revisi atas Karya berdasarkan catatan editorial sebagaimana
                    dimaksud pada ayat (4) dalam jangka waktu yang disepakati oleh PARA PIHAK.
                </li>
                <li>Setelah PIHAK KEDUA menyerahkan hasil revisi, PIHAK PERTAMA akan melakukan peninjauan kembali untuk
                    menentukan apakah Karya telah memenuhi standar editorial KISA Indonesia ID.
                </li>
                <li>Dalam hal hasil revisi masih belum memenuhi standar editorial, PIHAK PERTAMA berhak meminta revisi
                    tambahan kepada PIHAK KEDUA sampai Karya dinyatakan layak untuk diterbitkan.
                </li>
                <li>Seluruh proses editorial dilaksanakan dengan tetap menghormati hak moral PIHAK KEDUA sebagai
                    pencipta Karya. PIHAK PERTAMA tidak diperkenankan mengubah substansi cerita, alur, karakter utama,
                    atau pesan yang terkandung dalam Karya tanpa persetujuan tertulis dari PIHAK KEDUA.
                </li>
            </ul>
        </div>

        <h3 class="font-black text-slate-900 dark:text-slate-100">Pasal 10 • Kepemilikan Hak Cipta</h3>
        <div class="pl-5">
            <ul class="list-decimal">
                <li>PIHAK KEDUA menyatakan dan menjamin bahwa Karya yang menjadi objek Perjanjian ini merupakan hasil
                    ciptaan asli PIHAK KEDUA serta tidak melanggar hak cipta, hak kekayaan intelektual, maupun hak pihak
                    lain.
                </li>
                <li>Hak Cipta atas Karya tetap menjadi milik PIHAK KEDUA sebagai pencipta sesuai dengan ketentuan
                    peraturan perundang-undangan yang berlaku di Republik Indonesia.
                </li>
                <li>Penandatanganan Perjanjian ini tidak mengakibatkan beralihnya kepemilikan Hak Cipta dari PIHAK KEDUA
                    kepada PIHAK PERTAMA, kecuali disepakati lain secara tertulis oleh PARA PIHAK.
                </li>
            </ul>
        </div>

        <h3 class="font-black text-slate-900 dark:text-slate-100">Pasal 11 • Lisensi Eksklusif</h3>
        <div class="pl-5">
            <ul class="list-decimal">
                <li>PIHAK KEDUA memberikan kepada PIHAK PERTAMA lisensi yang bersifat eksklusif untuk menerbitkan,
                    memperbanyak, menyimpan, menampilkan, mendistribusikan, memasarkan, mempromosikan, dan
                    mengomersialisasikan Karya selama jangka waktu Perjanjian.
                </li>
                <li>Lisensi eksklusif sebagaimana dimaksud pada ayat (1) berlaku untuk seluruh media digital yang
                    dimiliki, dikelola, atau bekerja sama dengan PIHAK PERTAMA.
                </li>
                <li>Selama Perjanjian ini berlaku, PIHAK KEDUA tidak diperkenankan menerbitkan, melisensikan, atau
                    mengomersialisasikan Karya yang sama kepada pihak lain tanpa persetujuan tertulis dari PIHAK
                    PERTAMA.
                </li>
                <li>PIHAK PERTAMA berhak menggunakan judul Karya, nama pena, sinopsis, ilustrasi, sampul, kutipan, serta
                    materi promosi lainnya yang berkaitan dengan Karya untuk kepentingan promosi dan pemasaran.
                </li>
            </ul>
        </div>

        <h3 class="font-black text-slate-900 dark:text-slate-100">Pasal 12 • Hak Adaptasi dan Pengembangan Karya</h3>
        <div class="pl-5">
            <ul class="list-decimal">
                <li>Setiap rencana adaptasi Karya ke dalam bentuk media lain, termasuk namun tidak terbatas pada film,
                    serial, audiobook, drama, permainan digital, maupun bentuk adaptasi lainnya, wajib diberitahukan
                    kepada PIHAK KEDUA.
                </li>
                <li>Pelaksanaan kerja sama adaptasi sebagaimana dimaksud pada ayat (1) akan diatur lebih lanjut melalui
                    perjanjian atau addendum tersendiri yang disepakati oleh PARA PIHAK.
                </li>
                <li>PIHAK PERTAMA memiliki hak untuk menjadi pihak yang pertama melakukan negosiasi atas pemanfaatan hak
                    adaptasi Karya selama Perjanjian ini masih berlaku.
                </li>
            </ul>
        </div>

        <h3 class="font-black text-slate-900 dark:text-slate-100">Pasal 13 • Perlindungan Hak Kekayaan Intelektual</h3>
        <div class="pl-5">
            <ul class="list-decimal">
                <li>Dalam hal terdapat dugaan pelanggaran Hak Kekayaan Intelektual terhadap Karya, PARA PIHAK sepakat
                    untuk saling bekerja sama dalam melakukan upaya penyelesaian sesuai dengan ketentuan hukum yang
                    berlaku.
                </li>
                <li>PIHAK KEDUA bertanggung jawab atas segala tuntutan dari pihak ketiga yang timbul akibat terbuktinya
                    pelanggaran hak cipta atau penggunaan materi yang bukan merupakan hak PIHAK KEDUA.
                </li>
                <li>PIHAK PERTAMA berhak menghentikan sementara publikasi Karya sampai permasalahan sebagaimana dimaksud
                    pada ayat (1) memperoleh penyelesaian.
                </li>
            </ul>
        </div>

        <h3 class="font-black text-slate-900 dark:text-slate-100">Pasal 14 • Royalti dan Pemanfaatan Hak Kekayaan
            Intelektual</h3>
        <div class="pl-5">
            <ul class="list-decimal">
                <li>PIHAK KEDUA berhak memperoleh royalti atas setiap pemanfaatan komersial Karya yang diterbitkan
                    melalui Platform KISA Indonesia ID sesuai dengan ketentuan dalam Perjanjian ini.
                </li>
                <li>PARA PIHAK sepakat menerapkan skema pembelian Kisa Bean mengikuti rate yang berlaku saat pencairan.
                </li>
                <li>Pembayaran royalti sebagaimana dimaksud pada ayat (2) dilakukan sesuai dengan jadwal pembayaran yang
                    ditetapkan oleh PIHAK PERTAMA.
                </li>
                <li>Setiap pembayaran royalti sebagaimana dimaksud pada ayat (3) akan disertai dengan laporan
                    perhitungan pendapatan (rekonsiliasi royalti) yang memuat paling sedikit jumlah pendapatan, dasar
                    perhitungan, potongan yang dikenakan, serta jumlah royalti yang menjadi hak PIHAK KEDUA. Laporan
                    tersebut disampaikan oleh PIHAK PERTAMA secara transparan melalui alamat surat elektronik (email)
                    yang didaftarkan oleh PIHAK KEDUA.
                </li>
                <li>Sebagai bentuk apresiasi atas penyelesaian Karya, PIHAK PERTAMA dapat memberikan Bonus Tamat kepada
                    PIHAK KEDUA dengan nilai paling tinggi sebesar Rp500.000,00 (lima ratus ribu rupiah) untuk setiap
                    Karya yang memenuhi seluruh persyaratan sebagai berikut:
                    <div>
                        <ol style="list-style-type: lower-alpha;" class="ml-6">
                            <li>Karya diselesaikan dalam jangka waktu paling lama 3 (tiga) bulan sejak tanggal publikasi
                                bab pertama atau sejak tanggal yang ditetapkan oleh PIHAK PERTAMA.</li>
                            <li>Karya terdiri atas paling sedikit 30 (tiga puluh) bab dan paling banyak 60 (enam puluh)
                                bab.</li>
                            <li>Setiap bab memiliki jumlah kata paling sedikit 1.000 (seribu) kata dan paling banyak
                                1.500 (seribu lima ratus) kata.</li>
                            <li>Karya telah melalui proses editorial dan dinyatakan selesai serta layak diterbitkan oleh
                                PIHAK PERTAMA.</li>
                            <li>PIHAK KEDUA tidak melakukan pelanggaran terhadap ketentuan dalam Perjanjian ini.</li>
                        </ol>
                    </div>
                </li>
                <li>Besaran Bonus Tamat yang diterima oleh PIHAK KEDUA ditentukan oleh PIHAK PERTAMA berdasarkan hasil
                    evaluasi terhadap kualitas Karya, meliputi namun tidak terbatas pada kualitas penulisan, konsistensi
                    pembaruan, kepatuhan terhadap standar editorial, orisinalitas, serta performa Karya pada Platform
                    KISA Indonesia ID, dengan jumlah maksimum sebesar Rp500.000,00 (lima ratus ribu rupiah).</li>
                <li>Bonus Tamat sebagaimana dimaksud pada ayat (5) dan ayat (6) merupakan insentif di luar pembayaran
                    royalti dan hanya diberikan 1 (satu) kali untuk setiap Karya yang memenuhi persyaratan.</li>
                <li>Hak pemanfaatan Karya dalam bentuk lisensi film, serial televisi, serial digital, web series,
                    audiobook, permainan digital, penerjemahan, penerbitan dalam bentuk cetak, distribusi internasional,
                    maupun bentuk pemanfaatan komersial lainnya tidak termasuk dalam skema pembagian royalti sebagaimana
                    dimaksud pada ayat (2).</li>
                <li>Setiap pemanfaatan Hak Kekayaan Intelektual sebagaimana dimaksud pada ayat (8) akan diatur lebih
                    lanjut dalam perjanjian atau addendum tersendiri yang dibuat dan ditandatangani oleh PARA PIHAK
                    sebelum hak tersebut dimanfaatkan secara komersial.</li>
                <li>Sepanjang belum terdapat perjanjian atau addendum sebagaimana dimaksud pada ayat (9), PIHAK PERTAMA
                    tidak berhak mengalihkan, melisensikan, atau melakukan eksploitasi komersial atas hak-hak tersebut
                    kepada pihak ketiga.</li>
                <li>PARA PIHAK sepakat bahwa setiap perjanjian lanjutan mengenai pemanfaatan Hak Kekayaan Intelektual
                    akan memuat paling sedikit ruang lingkup lisensi, jangka waktu, wilayah lisensi, besaran kompensasi
                    atau royalti, mekanisme pembayaran, serta hak dan kewajiban masing-masing pihak.</li>
            </ul>
        </div>

        <h3 class="font-black text-slate-900 dark:text-slate-100">Pasal 15 • Jangka Waktu Perjanjian</h3>
        <div class="pl-5">
            <ul class="list-decimal">
                <li>Perjanjian ini mulai berlaku sejak tanggal ditandatangani oleh PARA PIHAK.
                </li>
                <li>Jangka waktu Perjanjian ini adalah selama PIHAK KEDUA menggunakan platform KISA Indonesia ID.
                </li>
                <li>PIHAK PERTAMA berhak menghentikan sementara publikasi Karya sampai permasalahan sebagaimana dimaksud
                    pada ayat (1) memperoleh penyelesaian.
                </li>
                <li>Setelah jangka waktu sebagaimana dimaksud pada ayat (2) berakhir, Perjanjian dapat diperpanjang
                    berdasarkan kesepakatan tertulis PARA PIHAK.</li>
                <li>Apabila tidak terdapat kesepakatan mengenai perpanjangan sebagaimana dimaksud pada ayat (3), maka
                    Perjanjian berakhir dengan sendirinya tanpa mengurangi hak dan kewajiban PARA PIHAK yang telah
                    timbul sebelum berakhirnya Perjanjian.</li>
            </ul>
        </div>

        <h3 class="font-black text-slate-900 dark:text-slate-100">Pasal 16 • Pengakhiran Perjanjian</h3>
        <div class="pl-5">
            <ul class="list-decimal">
                <li>Perjanjian ini dapat diakhiri sebelum berakhirnya jangka waktu apabila:
                    <div>
                        <ol style="list-style-type: lower-alpha;" class="ml-6">
                            <li>PARA PIHAK sepakat secara tertulis untuk mengakhiri Perjanjian.</li>
                            <li>Salah satu pihak melakukan wanprestasi sebagaimana diatur dalam Perjanjian ini.</li>
                            <li>Terjadi Keadaan Kahar (Force Majeure) yang menyebabkan Perjanjian tidak dapat
                                dilaksanakan dalam jangka waktu yang wajar.</li>
                            <li>Terdapat putusan pengadilan yang telah berkekuatan hukum tetap yang mengharuskan
                                Perjanjian ini diakhiri.</li>
                        </ol>
                    </div>
                </li>
                <li>Pihak yang bermaksud mengakhiri Perjanjian wajib menyampaikan pemberitahuan tertulis kepada pihak
                    lainnya paling lambat 30 (tiga puluh) Hari Kerja sebelum tanggal pengakhiran yang dikehendaki,
                    kecuali ditentukan lain dalam Perjanjian ini.
                </li>
            </ul>
        </div>

        <h3 class="font-black text-slate-900 dark:text-slate-100">Pasal 17 • Akibat Berakhirnya Perjanjian</h3>
        <div class="pl-5">
            <ul class="list-decimal">
                <li>Berakhirnya Perjanjian tidak menghapus hak dan kewajiban PARA PIHAK yang telah timbul sebelum
                    tanggal berakhirnya Perjanjian.
                </li>
                <li>Setelah Perjanjian berakhir, PIHAK PERTAMA wajib menghentikan penerbitan baru atas Karya, kecuali
                    PARA PIHAK menyepakati lain secara tertulis.
                </li>
                <li>Hak Cipta atas Karya tetap berada pada PIHAK KEDUA sesuai dengan ketentuan Perjanjian dan peraturan
                    perundang-undangan yang berlaku.
                </li>
                <li>Seluruh kewajiban pembayaran royalti yang telah menjadi hak PIHAK KEDUA sebelum berakhirnya
                    Perjanjian tetap wajib diselesaikan oleh PIHAK PERTAMA sesuai dengan ketentuan Perjanjian ini.
                </li>
                <li>Ketentuan mengenai kerahasiaan, hak kekayaan intelektual, penyelesaian perselisihan, dan kewajiban
                    lain yang menurut sifatnya tetap berlaku setelah berakhirnya Perjanjian, tetap mengikat PARA PIHAK
                    meskipun Perjanjian ini telah berakhir.
                </li>
            </ul>
        </div>

        <h3 class="font-black text-slate-900 dark:text-slate-100">Pasal 18 • Ketentuan Penutup</h3>
        <div class="pl-5">
            <ul class="list-decimal">
                <li>Perjanjian ini mulai berlaku sejak tanggal ditandatangani oleh PARA PIHAK dan mengikat secara hukum
                    sampai berakhir sesuai dengan ketentuan dalam Perjanjian ini.
                </li>
                <li>Setiap perubahan, penambahan, atau pengurangan terhadap ketentuan dalam Perjanjian ini hanya sah
                    apabila dibuat secara tertulis dan ditandatangani oleh PARA PIHAK dalam bentuk addendum yang menjadi
                    satu kesatuan dan bagian yang tidak terpisahkan dari Perjanjian ini.
                </li>
                <li>Apabila di kemudian hari terdapat ketentuan dalam Perjanjian ini yang dinyatakan tidak sah, batal,
                    atau tidak dapat dilaksanakan berdasarkan putusan pengadilan yang telah berkekuatan hukum tetap atau
                    ketentuan peraturan perundang-undangan yang berlaku, maka ketentuan tersebut tidak mengurangi
                    keabsahan dan keberlakuan ketentuan lainnya dalam Perjanjian ini.
                </li>
                <li>Segala hal yang belum diatur dalam Perjanjian ini akan diselesaikan berdasarkan musyawarah untuk
                    mufakat dan apabila diperlukan akan dituangkan dalam addendum yang ditandatangani oleh PARA PIHAK.
                </li>

            </ul>
        </div>

        <p>Dengan ditandatanganinya Perjanjian ini, PARA PIHAK menyatakan telah membaca, memahami, dan menyetujui
            seluruh isi Perjanjian tanpa adanya paksaan, tekanan, penipuan, maupun pengaruh dari pihak mana pun.</p>
        <p>Demikian Perjanjian Kerja Sama Penerbitan dan Lisensi Eksklusif Karya ini dibuat dan ditandatangani oleh PARA
            PIHAK dalam keadaan sadar, sehat jasmani dan rohani, serta dengan itikad baik untuk dipatuhi dan
            dilaksanakan sebagaimana mestinya.</p>

    </div>

    {{-- ISIAN IDENTITAS PENULIS --}}
    <form wire:submit.prevent="submitContract" class="mt-6 space-y-4">
        <h3 class="text-xs font-black text-slate-900 dark:text-slate-100 uppercase tracking-wide">
            Lengkapi Data Identitas Tanda Tangan
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap (Sesuai
                    KTP)</label>
                <input type="text" wire:model.live="fullName"
                    class="w-full p-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500">
                @error('fullName')
                    <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Nomor KTP (16 Digit
                    NIK)</label>
                <input type="text" wire:model.live="ktpNumber" maxlength="16" placeholder="3209..."
                    class="w-full p-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500">
                @error('ktpNumber')
                    <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Tempat Lahir</label>
                <input type="text" wire:model.live="pob" placeholder="Kota Lahir"
                    class="w-full p-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500">
                @error('pob')
                    <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Tanggal
                    Lahir</label>
                <input type="date" wire:model.live="dob"
                    class="w-full p-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500">
                @error('dob')
                    <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div>
            <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Alamat Domisili Lengkap
                (Sesuai KTP)</label>
            <textarea wire:model.live="address" rows="2" placeholder="Jl. Raya No..., RT/RW, Kel/Kec, Kota..."
                class="w-full p-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500"></textarea>
            @error('address')
                <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        {{-- Checkbox Persetujuan --}}
        <div
            class="p-3 bg-brand-50 dark:bg-brand-950/40 rounded-2xl border border-brand-100 dark:border-brand-900/50 flex items-start gap-2.5">
            <input type="checkbox" wire:model="agree" id="agree"
                class="mt-1 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
            <label for="agree"
                class="text-xs text-slate-700 dark:text-slate-300 font-medium leading-relaxed select-none">
                Saya menyatakan bahwa seluruh data di atas adalah BENAR. Saya telah membaca, memahami, dan menyetujui
                seluruh isi <strong>Perjanjian Kerja Sama Penerbitan dan Lisensi Eksklusif Karya KISA Indonesia
                    ID</strong> ini secara sadar tanpa paksaan dari pihak mana pun.
            </label>
        </div>
        @error('agree')
            <span class="text-[10px] text-rose-500 block">{{ $message }}</span>
        @enderror

        {{-- Tombol Submit --}}
        <button type="submit"
            class="w-full py-3.5 bg-slate-900 dark:bg-brand-600 hover:bg-slate-800 dark:hover:bg-brand-500 text-white font-black text-xs rounded-xl shadow-md transition transform active:scale-95">
            Tanda Tangani Kontrak Sekarang
        </button>
    </form>
</div>
