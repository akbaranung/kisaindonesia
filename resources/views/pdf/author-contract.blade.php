<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Perjanjian Kerja Sama - {{ $user->name }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #111;
            margin: 20mm 20mm;
            text-align: justify;
        }

        h3 {
            text-align: center;
            margin-top: 25px;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-size: 12pt;
        }

        .document-title {
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 13pt;
            margin-bottom: 0;
        }

        .document-subtitle {
            text-align: center;
            font-size: 10pt;
            margin-top: 5px;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .party-box {
            margin: 15px 0;
            padding-left: 15px;
            border-left: 3px solid #333;
        }

        ol,
        ul {
            margin-top: 5px;
            margin-bottom: 10px;
            padding-left: 20px;
        }

        li {
            margin-bottom: 6px;
            text-align: justify;
        }

        .signature-table {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }

        .signature-table td {
            vertical-align: top;
            text-align: center;
            padding: 10px;
        }

        .signature-box {
            border: 1px dashed #555;
            padding: 12px;
            font-size: 9pt;
            background: #fafafa;
            text-align: left;
            margin: 15px auto;
            width: 85%;
            line-height: 1.4;
        }

        .name-underlined {
            text-decoration: underline;
            font-weight: bold;
        }

        @media print {
            body {
                margin: 15mm 15mm;
            }
        }
    </style>
</head>

<body onload="window.print()">

    @php
        setlocale(LC_TIME, 'id_ID');
        \Carbon\Carbon::setLocale('id');
    @endphp

    <div class="document-title">PERJANJIAN KERJA SAMA PENERBITAN DAN LISENSI EKSKLUSIF KARYA</div>
    <div class="document-subtitle">KISA INDONESIA ID</div>
    <hr style="border: 1px solid #000; margin-bottom: 20px;">

    <p>
        Pada hari ini, <strong>{{ \Carbon\Carbon::parse($signedAt)->translatedFormat('l') }}</strong> tanggal
        <strong>{{ \Carbon\Carbon::parse($signedAt)->translatedFormat('d') }}</strong> bulan
        <strong>{{ \Carbon\Carbon::parse($signedAt)->translatedFormat('F') }}</strong> tahun
        <strong>{{ \Carbon\Carbon::parse($signedAt)->translatedFormat('Y') }}</strong>, bertempat di Jakarta, telah
        dibuat dan disepakati Perjanjian Kerja Sama Penerbitan dan Lisensi Eksklusif Karya ("Perjanjian") oleh dan
        antara:
    </p>

    <div class="party-box">
        <p><strong>PIHAK PERTAMA</strong></p>
        <p><strong>KISA Indonesia ID</strong>, berkedudukan di Gedung Graha Dirgantara, Jalan Protokol Halim
            Perdanakusuma No. 8, Jakarta Timur, dalam hal ini diwakili secara sah oleh <strong>Citra Friska
                Nabila</strong> selaku Direktur Editorial KISA Indonesia ID, selanjutnya disebut sebagai
            <strong>PIHAK PERTAMA</strong>.
        </p>
    </div>

    <div class="party-box">
        <p><strong>PIHAK KEDUA</strong></p>
        <table style="width: 100%; border: none; margin: 0;">
            <tr>
                <td style="width: 150px; padding: 2px 0;">Nama Lengkap</td>
                <td style="width: 10px; padding: 2px 0;">:</td>
                <td style="padding: 2px 0;"><strong>{{ $user->name ?: '________________________' }}</strong></td>
            </tr>
            <tr>
                <td style="padding: 2px 0;">Tempat/Tgl Lahir</td>
                <td>:</td>
                <td>{{ $user->pob ?: '__________' }},
                    {{ $user->dob ? \Carbon\Carbon::parse($user->dob)->translatedFormat('d F Y') : '__________' }}</td>
            </tr>
            <tr>
                <td style="padding: 2px 0;">Alamat Domisili</td>
                <td>:</td>
                <td>{{ $user->address ?: '________________________' }}</td>
            </tr>
            <tr>
                <td style="padding: 2px 0;">No. KTP (NIK)</td>
                <td>:</td>
                <td>{{ $user->ktp_number ?: '________________________' }}</td>
            </tr>
        </table>
        <p style="font-size: 10pt; color: #444; margin-top: 5px;">
            Bertindak atas nama diri sendiri selaku pencipta dan/atau pemegang hak cipta atas karya, selanjutnya disebut
            sebagai <strong>PIHAK KEDUA</strong>.
        </p>
    </div>

    <p>
        PARA PIHAK sepakat untuk mengikatkan diri dalam Perjanjian Kerja Sama Penerbitan dan Lisensi Eksklusif Karya
        dengan syarat dan ketentuan sebagaimana diatur dalam pasal-pasal berikut:
    </p>

    <hr style="border: 0.5px solid #ccc; margin: 20px 0;">

    <h3>Pasal 1 • Definisi</h3>
    <p>Dalam Perjanjian ini, kecuali apabila konteks menentukan lain, istilah-istilah di bawah ini mempunyai pengertian
        sebagai berikut:</p>
    <ol>
        <li><strong>KISA Indonesia ID</strong> adalah platform digital yang bergerak di bidang penerbitan digital,
            distribusi karya sastra, novel daring, cerita bersambung, serta layanan literasi digital.</li>
        <li><strong>Platform</strong> adalah seluruh media digital milik atau yang dikelola oleh KISA, termasuk
            aplikasi, situs web, dashboard, media sosial, serta media digital lainnya yang digunakan untuk menerbitkan,
            memasarkan, mendistribusikan, dan mengomersialisasikan Karya.</li>
        <li><strong>Karya</strong> adalah novel, cerita bersambung, cerita pendek, puisi, atau naskah tulis lainnya yang
            diciptakan oleh Penulis.</li>
        <li><strong>Penulis</strong> adalah orang perseorangan yang bertindak sebagai pencipta dan/atau pemegang hak
            cipta atas karya yang menjadi objek Perjanjian ini.</li>
        <li><strong>Lisensi Eksklusif</strong> adalah pemberian hak secara eksklusif oleh Penulis kepada KISA untuk
            menerbitkan, memperbanyak, mendistribusikan, mempromosikan, dan mengomersialisasikan Karya tanpa mengalihkan
            kepemilikan Hak Cipta kepada KISA.</li>
        <li><strong>Hak Cipta</strong> adalah hak eksklusif Penulis sebagaimana diatur dalam peraturan
            perundang-undangan mengenai hak cipta yang berlaku di Republik Indonesia.</li>
        <li><strong>Hak Ekonomi</strong> adalah hak untuk memperoleh manfaat ekonomi atas Karya sebagaimana diatur dalam
            Perjanjian ini.</li>
        <li><strong>Hak Moral</strong> adalah hak yang melekat secara abadi pada diri Penulis sebagai pencipta Karya
            sesuai dengan ketentuan peraturan perundang-undangan.</li>
        <li><strong>Royalti</strong> adalah sejumlah imbalan yang menjadi hak Penulis atas pemanfaatan komersial Karya
            oleh KISA berdasarkan mekanisme dan perhitungan sebagaimana diatur dalam Perjanjian ini.</li>
        <li><strong>Pendapatan Bersih</strong> adalah seluruh pendapatan yang diterima KISA dari komersialisasi Karya
            setelah dikurangi pajak, biaya administrasi, biaya transaksi, biaya distribusi, potongan platform pihak
            ketiga, serta biaya lain yang secara langsung berkaitan dengan transaksi tersebut.</li>
        <li><strong>Informasi Rahasia</strong> adalah seluruh informasi yang tidak tersedia untuk umum, termasuk data
            Penulis, data pengguna, laporan pendapatan, strategi bisnis, data pemasaran, dokumen internal, maupun
            informasi lain yang oleh salah satu pihak dinyatakan sebagai rahasia.</li>
        <li><strong>Hari Kerja</strong> adalah hari Senin sampai dengan Jumat, tidak termasuk hari libur nasional yang
            ditetapkan oleh Pemerintah Republik Indonesia.</li>
        <li><strong>Keadaan Kahar (Force Majeure)</strong> adalah setiap keadaan di luar kemampuan dan kendali Para
            Pihak yang mengakibatkan sebagian atau seluruh kewajiban dalam Perjanjian ini tidak dapat dilaksanakan,
            termasuk namun tidak terbatas pada bencana alam, perang, kerusuhan, kebakaran, epidemi, pandemi, atau
            gangguan sistem nasional.</li>
        <li><strong>Addendum</strong> adalah perubahan atau penambahan terhadap Perjanjian ini yang dibuat secara
            tertulis, disepakati, dan ditandatangani oleh Para Pihak.</li>
    </ol>

    <h3>Pasal 2 • Penafsiran</h3>
    <ol>
        <li>Seluruh istilah yang digunakan dalam Perjanjian ini wajib ditafsirkan sesuai dengan ketentuan peraturan
            perundang-undangan Republik Indonesia.</li>
        <li>Judul Bab, Pasal, dan Ayat dalam Perjanjian ini dibuat untuk memudahkan pembacaan dan tidak memengaruhi
            penafsiran isi Perjanjian.</li>
        <li>Apabila terdapat ketentuan dalam Perjanjian ini yang dinyatakan tidak sah atau tidak dapat dilaksanakan
            berdasarkan putusan pengadilan atau ketentuan hukum yang berlaku, ketentuan tersebut tidak memengaruhi
            keabsahan ketentuan lainnya.</li>
        <li>Setiap perubahan terhadap Perjanjian ini hanya sah apabila dibuat secara tertulis dan ditandatangani oleh
            Para Pihak.</li>
        <li>Hal-hal yang belum diatur dalam Perjanjian ini akan ditetapkan kemudian berdasarkan kesepakatan tertulis
            Para Pihak melalui Addendum.</li>
    </ol>

    <h3>Pasal 3 • Objek Perjanjian</h3>
    <ol>
        <li>PIHAK PERTAMA dan PIHAK KEDUA sepakat bahwa objek Perjanjian ini adalah pemberian Lisensi Eksklusif oleh
            PIHAK KEDUA kepada PIHAK PERTAMA atas Karya sebagaimana tercantum dalam Lampiran Perjanjian ini.
        </li>
        <li>Karya sebagaimana dimaksud pada ayat (1) meliputi novel, cerita bersambung, cerita pendek, puisi, atau
            bentuk karya tulis lainnya yang disepakati PARA PIHAK.</li>
        <li>Identitas Karya yang menjadi objek Perjanjian paling sedikit memuat:
            <ol type="a">
                <li>Judul Karya;</li>
                <li>Nama Penulis atau nama pena;</li>
                <li>Genre;</li>
                <li>Sinopsis;</li>
                <li>Status Karya;</li>
                <li>Jumlah bab yang telah tersedia pada saat penandatanganan Perjanjian; dan</li>
                <li>Informasi lain yang dianggap perlu oleh PARA PIHAK.</li>
            </ol>
        </li>
        <li>Identitas Karya sebagaimana dimaksud pada ayat (3) dituangkan dalam Lampiran yang merupakan bagian tidak
            terpisahkan dari Perjanjian ini.</li>
        <li>Dalam hal PIHAK KEDUA bermaksud menambahkan Karya lain, penambahan tersebut wajib disepakati secara tertulis
            oleh PARA PIHAK.</li>
    </ol>

    <h3>Pasal 4 • Pemberian Lisensi Eksklusif</h3>
    <ol>
        <li>PIHAK KEDUA dengan ini memberikan kepada PIHAK PERTAMA hak lisensi yang bersifat eksklusif untuk
            menerbitkan, memperbanyak, menyimpan, menampilkan, mendistribusikan, mempromosikan, mengomersialisasikan,
            serta memanfaatkan Karya selama jangka waktu Perjanjian.</li>
        <li>Selama Perjanjian ini berlaku, PIHAK KEDUA tidak diperkenankan menerbitkan, mengunggah, mendistribusikan,
            melisensikan, atau mengomersialisasikan Karya kepada pihak lain tanpa persetujuan tertulis dari PIHAK
            PERTAMA.</li>
        <li>Lisensi eksklusif sebagaimana dimaksud pada ayat (1) meliputi penerbitan melalui aplikasi KISA Indonesia ID,
            situs web resmi, media sosial resmi, maupun media digital lain yang ditunjuk oleh PIHAK PERTAMA.
        </li>
        <li>Pemberian Lisensi Eksklusif ini tidak mengakibatkan beralihnya Hak Cipta atas Karya dari PIHAK KEDUA kepada
            PIHAK PERTAMA.</li>
        <li>PIHAK PERTAMA berhak menggunakan judul Karya, sampul, sinopsis, kutipan, ilustrasi, serta materi promosi
            lainnya yang berkaitan dengan Karya untuk kepentingan pemasaran.</li>
    </ol>

    <h3>Pasal 5 • Tujuan Kerja Sama</h3>
    <ol>
        <li>Kerja sama ini bertujuan untuk mengembangkan potensi komersial Karya melalui sistem penerbitan digital yang
            dikelola PIHAK PERTAMA dengan tetap menghormati hak moral dan ekonomi PIHAK KEDUA.</li>
        <li>PIHAK PERTAMA berkewajiban mengelola Karya secara profesional melalui proses editorial, publikasi, promosi,
            distribusi, dan monetisasi.</li>
        <li>PIHAK KEDUA berkewajiban mendukung pelaksanaan kerja sama dengan menyediakan Karya berkualitas dan memenuhi
            ketentuan Perjanjian.</li>
        <li>Hubungan hukum yang timbul dari Perjanjian ini merupakan hubungan kemitraan, sehingga tidak menimbulkan
            hubungan kerja di bidang ketenagakerjaan.</li>
    </ol>

    <h3>Pasal 6 • Hak dan Kewajiban PIHAK PERTAMA</h3>
    <ol>
        <li>PIHAK PERTAMA berhak memperoleh Lisensi Eksklusif atas Karya untuk diterbitkan, dipublikasikan,
            didistribusikan, dan dimonetisasi.</li>
        <li>PIHAK PERTAMA berhak melakukan seleksi, penyuntingan, tata letak, desain sampul, dan penyesuaian teknis
            dengan tetap menghormati substansi serta hak moral PIHAK KEDUA.</li>
        <li>PIHAK PERTAMA berhak menentukan waktu penerbitan, jadwal publikasi, strategi promosi, dan bentuk monetisasi
            Karya.</li>
        <li>PIHAK PERTAMA berhak menggunakan materi promosi, sinopsis, dan nama pena Penulis untuk kepentingan
            pemasaran.</li>
        <li>PIHAK PERTAMA berkewajiban menerbitkan dan mengelola Karya secara profesional sesuai standar operasional
            KISA Indonesia ID.</li>
        <li>PIHAK PERTAMA berkewajiban menjaga kerahasiaan data pribadi PIHAK KEDUA.</li>
        <li>PIHAK PERTAMA berkewajiban memberikan laporan pendapatan dan membayarkan royalti sesuai mekanisme
            Perjanjian.</li>
    </ol>

    <h3>Pasal 7 • Hak dan Kewajiban PIHAK KEDUA</h3>
    <ol>
        <li>PIHAK KEDUA berhak tetap diakui sebagai pencipta dan pemegang Hak Cipta atas Karya.</li>
        <li>PIHAK KEDUA berhak memperoleh royalti atau kompensasi sesuai ketentuan Perjanjian.</li>
        <li>PIHAK KEDUA berhak memperoleh informasi mengenai perkembangan publikasi dan monetisasi Karya.</li>
        <li>PIHAK KEDUA berkewajiban menyerahkan Karya yang merupakan hasil ciptaan sendiri, asli, serta tidak melanggar
            hak kekayaan intelektual pihak lain.</li>
        <li>PIHAK KEDUA berkewajiban menyelesaikan Karya sesuai jadwal dan target yang disepakati.</li>
        <li>Selama Perjanjian berlaku, PIHAK KEDUA dilarang melisensikan Karya kepada pihak lain tanpa izin tertulis
            PIHAK PERTAMA.</li>
        <li>PIHAK KEDUA bertanggung jawab penuh atas keaslian Karya dan membebaskan PIHAK PERTAMA dari tuntutan pihak
            ketiga.</li>
    </ol>

    <h3>Pasal 8 • Hak dan Kewajiban Bersama</h3>
    <ol>
        <li>PARA PIHAK sepakat melaksanakan Perjanjian dengan itikad baik, profesional, dan transparan.</li>
        <li>PARA PIHAK wajib menjaga kerahasiaan seluruh informasi yang diperoleh selama pelaksanaan kerja sama.</li>
        <li>Setiap perubahan ruang lingkup kerja sama wajib didasarkan pada kesepakatan tertulis.</li>
        <li>Penyelesaian perselisihan diutamakan melalui musyawarah untuk mufakat.</li>
    </ol>

    <h3>Pasal 9 • Proses Editorial</h3>
    <ol>
        <li>PIHAK PERTAMA berhak melakukan evaluasi terhadap Karya sesuai standar editorial KISA Indonesia ID.
        </li>
        <li>Apabila diperlukan penyempurnaan, PIHAK PERTAMA akan menyampaikan catatan editorial dan rekomendasi revisi
            secara tertulis.</li>
        <li>PIHAK KEDUA wajib melakukan revisi dalam jangka waktu yang disepakati.</li>
        <li>Proses editorial dilaksanakan dengan tetap menghormati hak moral PIHAK KEDUA tanpa mengubah alur cerita atau
            karakter utama tanpa persetujuan tertulis.</li>
    </ol>

    <h3>Pasal 10 • Kepemilikan Hak Cipta</h3>
    <ol>
        <li>Karya yang diobjekkan dalam Perjanjian ini murni hasil ciptaan asli PIHAK KEDUA.</li>
        <li>Hak Cipta atas Karya tetap sepenuhnya menjadi milik PIHAK KEDUA.</li>
        <li>Penandatanganan Perjanjian tidak mengalihkan kepemilikan Hak Cipta kepada PIHAK PERTAMA.</li>
    </ol>

    <h3>Pasal 11 • Hak Adaptasi dan Pengembangan Karya</h3>
    <ol>
        <li>Rencana adaptasi Karya ke media lain (film, serial, audiobook, dll.) wajib dikomunikasikan kepada PIHAK
            KEDUA.</li>
        <li>PIHAK PERTAMA memiliki hak utama (*first right of refusal*) untuk melakukan negosiasi atas pemanfaatan hak
            adaptasi Karya selama Perjanjian berlaku.</li>
    </ol>

    <h3>Pasal 12 • Perlindungan Hak Kekayaan Intelektual</h3>
    <ol>
        <li>Apabila terjadi sengketa hak kekayaan intelektual atas Karya, PARA PIHAK sepakat untuk saling bekerja
            sama.</li>
        <li>PIHAK KEDUA bertanggung jawab penuh atas gugatan pihak ketiga terkait keaslian ciptaan.</li>
        <li>PIHAK PERTAMA berhak menangguhkan publikasi Karya sementara waktu apabila timbul sengketa hukum.
        </li>
    </ol>

    <h3>Pasal 13 • Royalti dan Bonus Tamat</h3>
    <ol>
        <li>PIHAK KEDUA berhak atas royalti dari setiap pemanfaatan komersial Karya di Platform KISA Indonesia ID.</li>
        <li>Skema pembelian menggunakan sistem Kisa Bean sesuai kurs/rate yang berlaku saat pencairan.</li>
        <li>Pembayaran royalti disertai laporan rekonsiliasi yang dikirimkan secara transparan via email.</li>
        <li>PIHAK PERTAMA dapat memberikan <strong>Bonus Tamat</strong> maksimal Rp500.000,00 bagi penulis yang
            merampungkan karyanya sesuai ketentuan:
            <ol type="a">
                <li>Selesai maksimal 3 bulan sejak tanggal rilis bab pertama;</li>
                <li>Terdiri dari 30 s.d. 60 bab;</li>
                <li>Setiap bab berisi 1.000 s.d. 1.500 kata;</li>
                <li>Lulus kurasi editorial dan layak terbit.</li>
            </ol>
        </li>
        <li>Pemanfaatan hak komersial di luar platform digital (seperti film, cetak fisik, dll.) diatur melalui addendum
            terpisah.</li>
    </ol>

    <h3>Pasal 14 • Jangka Waktu Perjanjian</h3>
    <ol>
        <li>Perjanjian ini berlaku sejak tanggal ditandatangani oleh PARA PIHAK.</li>
        <li>Jangka waktu Perjanjian ini berlangsung selama PIHAK KEDUA menggunakan platform KISA Indonesia ID.
        </li>
        <li>Perjanjian dapat diperpanjang atas dasar kesepakatan tertulis PARA PIHAK.</li>
    </ol>

    <h3>Pasal 15 • Pengakhiran Perjanjian</h3>
    <ol>
        <li>Perjanjian dapat diakhiri sebelum waktunya apabila:
            <ol type="a">
                <li>Ada kesepakatan tertulis PARA PIHAK;</li>
                <li>Salah satu pihak melakukan wanprestasi;</li>
                <li>Terjadi Keadaan Kahar (*Force Majeure*).</li>
            </ol>
        </li>
        <li>Pemberitahuan pengakhiran wajib disampaikan secara tertulis minimal 30 Hari Kerja sebelumnya.</li>
    </ol>

    <h3>Pasal 16 • Akibat Berakhirnya Perjanjian</h3>
    <ol>
        <li>Berakhirnya Perjanjian tidak menghapus hak dan kewajiban finansial yang telah timbul sebelumnya.
        </li>
        <li>PIHAK PERTAMA wajib menghentikan publikasi baru atas Karya setelah masa kontrak habis.</li>
        <li>Hak Cipta sepenuhnya tetap berada di tangan PIHAK KEDUA.</li>
    </ol>

    <h3>Pasal 17 • Ketentuan Penutup</h3>
    <ol>
        <li>Segala perubahan atau hal-hal yang belum diatur dalam Perjanjian ini akan ditetapkan melalui Addendum.</li>
        <li>Perjanjian ini dibuat dalam keadaan sadar, tanpa ada unsur paksaan dari pihak mana pun.</li>
    </ol>

    <p style="margin-top: 25px; text-align: justify;">
        Demikian Perjanjian Kerja Sama Penerbitan dan Lisensi Eksklusif Karya ini dibuat dan ditandatangani oleh PARA
        PIHAK dalam keadaan sadar, sehat jasmani dan rohani, serta dengan itikad baik untuk dipatuhi dan dilaksanakan
        sebagaimana mestinya.
    </p>

    {{-- Tabel Tanda Tangan --}}
    <table class="signature-table">
        <tr>
            <td style="width: 50%;">
                <p><strong>PIHAK PERTAMA</strong><br>KISA INDONESIA ID</p>
                <br><br><br><br>
                <p class="name-underlined">CITRA FRISKA NABILA</p>
                <p style="margin: 0; font-size: 10pt;">Direktur Editorial</p>
            </td>
            <td style="width: 50%;">
                <p><strong>PIHAK KEDUA</strong><br>PENULIS</p>
                <div class="signature-box">
                    <strong>[TANDA TANGAN DIGITAL TERVERIFIKASI]</strong><br>
                    Nama: {{ $user->name }}<br>
                    Waktu: {{ \Carbon\Carbon::parse($signedAt)->format('d/m/Y H:i:s') }} WIB<br>
                    IP: {{ $ip }}
                </div>
                <p class="name-underlined">{{ $user->name }}</p>
                <p style="margin: 0; font-size: 10pt;">NIK: {{ $user->ktp_number }}</p>
            </td>
        </tr>
    </table>

</body>

</html>
