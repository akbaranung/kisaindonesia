<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Perjanjian Kerja Sama - {{ $story->title }} ({{ $contract->signer_name }})</title>
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

        .story-info-box {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 12px;
            margin: 15px 0;
            border-radius: 6px;
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
        Pada hari ini, <strong>{{ \Carbon\Carbon::parse($contract->signed_at)->translatedFormat('l') }}</strong> tanggal
        <strong>{{ \Carbon\Carbon::parse($contract->signed_at)->translatedFormat('d') }}</strong> bulan
        <strong>{{ \Carbon\Carbon::parse($contract->signed_at)->translatedFormat('F') }}</strong> tahun
        <strong>{{ \Carbon\Carbon::parse($contract->signed_at)->translatedFormat('Y') }}</strong>, bertempat di Jakarta,
        telah
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
                <td style="padding: 2px 0;"><strong>{{ $contract->signer_name ?: '________________________' }}</strong>
                </td>
            </tr>
            <tr>
                <td style="padding: 2px 0;">Tempat/Tgl Lahir</td>
                <td>:</td>
                <td>{{ $contract->pob ?: '__________' }},
                    {{ $contract->dob ? \Carbon\Carbon::parse($contract->dob)->translatedFormat('d F Y') : '__________' }}
                </td>
            </tr>
            <tr>
                <td style="padding: 2px 0;">Alamat Domisili</td>
                <td>:</td>
                <td>{{ $contract->address ?: '________________________' }}</td>
            </tr>
            <tr>
                <td style="padding: 2px 0;">No. KTP (NIK)</td>
                <td>:</td>
                <td>{{ $contract->ktp_number ?: '________________________' }}</td>
            </tr>
        </table>
        <p style="font-size: 10pt; color: #444; margin-top: 5px;">
            Bertindak atas nama diri sendiri selaku pencipta dan/atau pemegang hak cipta atas karya yang menjadi objek
            Perjanjian ini, selanjutnya disebut
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
            PIHAK KEDUA kepada PIHAK PERTAMA atas Karya spesifik sebagaimana tercantum dalam tabel berikut:

            <div class="story-info-box">
                <table style="width: 100%; border: none; font-size: 10pt; margin: 0;">
                    <tr>
                        <td style="width: 140px; padding: 3px 0; font-weight: bold;">Judul Karya</td>
                        <td style="width: 10px; padding: 3px 0;">:</td>
                        <td style="padding: 3px 0; font-weight: bold; color: #0284c7;">{{ $story->title }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 0; font-weight: bold;">Genre</td>
                        <td>:</td>
                        <td>{{ $story->genre->name ?? 'Uncategorized' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 0; font-weight: bold;">Jumlah Bab Tersedia</td>
                        <td>:</td>
                        <td>{{ $story->chapters_count ?? $story->chapters()->count() }} Bab</td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 0; font-weight: bold;">Durasi Kontrak</td>
                        <td>:</td>
                        <td><strong>{{ $contract->duration_years ?? 5 }} (lima) Tahun</strong></td>
                    </tr>
                    @if($contract->signed_at)
                    <tr>
                        <td style="padding: 3px 0; font-weight: bold;">Periode Kontrak</td>
                        <td>:</td>
                        <td>
                            @php
                                $start = \Carbon\Carbon::parse($contract->signed_at);
                                $end = $contract->expires_at ? \Carbon\Carbon::parse($contract->expires_at) : (clone $start)->addYears($contract->duration_years ?? 5);
                            @endphp
                            <strong>{{ $start->translatedFormat('d F Y') }} s/d {{ $end->translatedFormat('d F Y') }}</strong>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td style="padding: 3px 0; font-weight: bold;">Versi Kontrak</td>
                        <td>:</td>
                        <td>Versi {{ $contract->contract_version }}</td>
                    </tr>
                </table>
            </div>
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
            oleh PARA PIHAK melalui dokumen perjanjian terpisah.</li>
    </ol>

    <h3>Pasal 4 • Pemberian Lisensi Eksklusif</h3>
    <ol>
        <li>PIHAK KEDUA dengan ini memberikan kepada PIHAK PERTAMA hak lisensi yang bersifat eksklusif untuk
            menerbitkan, memperbanyak, menyimpan, menampilkan, mendistribusikan, mempromosikan, mengomersialisasikan,
            serta memanfaatkan Karya selama jangka waktu Perjanjian.</li>
        <li>Selama Perjanjian ini berlaku, PIHAK KEDUA tidak diperkenankan menerbitkan, mengunggah, mendistribusikan,
            melisensikan, atau mengomersialisasikan Karya kepada pihak lain tanpa persetujuan tertulis dari PIHAK
            PERTAMA.</li>
        <li>Lisensi ini meliputi hak pengelolaan konten secara digital di platform KISA Indonesia ID serta seluruh kanal
            distribusi resminya.</li>
    </ol>

    <h3>Pasal 5 • Tujuan Kerja Sama</h3>
    <ol>
        <li>Mengoptimalkan potensi komersial Karya melalui sistem penerbitan digital profesional.</li>
        <li>PIHAK PERTAMA berkewajiban melakukan pengelolaan editorial, promosi, dan distribusi secara optimal.</li>
        <li>PIHAK KEDUA berkewajiban menjaga orisinalitas naskah dan mendukung aktivitas publikasi.</li>
    </ol>

    <h3>Pasal 6 • Hak dan Kewajiban PIHAK PERTAMA</h3>
    <ol>
        <li>Menerbitkan dan mendistribusikan Karya melalui Platform digital KISA Indonesia ID.</li>
        <li>Menentukan strategi penetapan harga koin/bean, promosi, dan pemasaran Karya.</li>
        <li>Melakukan penyuntingan dan penyesuaian editorial tanpa mengubah substansi cerita.</li>
        <li>Membayarkan royalti kepada PIHAK KEDUA secara tepat waktu dan transparan.</li>
        <li>Menyediakan laporan pendapatan berkala yang dapat diakses oleh PIHAK KEDUA.</li>
    </ol>

    <h3>Pasal 7 • Hak dan Kewajiban PIHAK KEDUA</h3>
    <ol>
        <li>Menjamin bahwa Karya merupakan karya orisinal dan bukan hasil plagiarisme atau pelanggaran hak cipta pihak
            lain.</li>
        <li>Menerima royalti dan bonus sesuai ketentuan dalam Perjanjian ini.</li>
        <li>Menjaga kerahasiaan data internal serta informasi komersial Platform KISA Indonesia ID.</li>
        <li>Menyelesaikan penulisan naskah sesuai jadwal dan standar editorial yang telah ditetapkan.</li>
    </ol>

    <h3>Pasal 8 • Hak dan Kewajiban Bersama</h3>
    <ol>
        <li>Saling menjaga reputasi baik dan integritas PARA PIHAK.</li>
        <li>Menyelesaikan setiap perbedaan pendapat melalui musyawarah untuk mufakat.</li>
        <li>Menghormati hak kekayaan intelektual masing-masing pihak.</li>
    </ol>

    <h3>Pasal 9 • Proses Editorial</h3>
    <ol>
        <li>PIHAK PERTAMA berhak melakukan kurasi dan proses editorial terhadap naskah.</li>
        <li>Apabila diperlukan penyempurnaan, PIHAK PERTAMA akan menyampaikan catatan editorial dan rekomendasi revisi
            secara tertulis.</li>
        <li>PIHAK KEDUA wajib melakukan revisi dalam jangka waktu yang disepakati.</li>
        <li>Proses editorial dilaksanakan dengan tetap menghormati hak moral PIHAK KEDUA tanpa mengubah alur cerita atau
            karakter utama tanpa persetujuan tertulis.</li>
    </ol>

    <h3>Pasal 10 • Kepemilikan Hak Cipta</h3>
    <ol>
        <li>Hak Cipta atas Karya sepenuhnya tetap berada pada PIHAK KEDUA selaku pencipta karya.</li>
        <li>Perjanjian ini adalah perjanjian lisensi eksklusif dan bukan pengalihan kepemilikan Hak Cipta (*copyright
            transfer*).</li>
    </ol>

    <h3>Pasal 11 • Hak Adaptasi dan Pengembangan Karya</h3>
    <ol>
        <li>Pemanfaatan Karya untuk adaptasi (seperti film, serial, komik, atau cetak fisik) akan dibahas melalui
            kesepakatan tersendiri dalam Addendum Perjanjian.</li>
        <li>PIHAK PERTAMA mendapat hak prioritas pertama (*right of first refusal*) untuk mengelola adaptasi Karya
            tersebut.</li>
    </ol>

    <h3>Pasal 12 • Perlindungan Hak Kekayaan Intelektual</h3>
    <ol>
        <li>PIHAK KEDUA menjamin secara hukum bahwa Karya bebas dari sengketa pihak ketiga manapun.</li>
        <li>Apabila di kemudian hari terbukti ada pelanggaran hak cipta oleh PIHAK KEDUA, maka sepenuhnya menjadi
            tanggung jawab PIHAK KEDUA secara hukum perdata maupun pidana.</li>
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
        <li>Jangka waktu Perjanjian ini berlaku selama <strong>5 (lima) tahun</strong> terhitung sejak tanggal Perjanjian ini ditandatangani oleh PARA PIHAK ("Jangka Waktu Kontrak").</li>
        <li>Setelah jangka waktu 5 (lima) tahun sebagaimana dimaksud pada ayat (2) berakhir, Perjanjian dapat diperpanjang atas dasar kesepakatan tertulis PARA PIHAK.</li>
        <li>Apabila tidak terdapat kesepakatan tertulis mengenai perpanjangan sebagaimana dimaksud pada ayat (3), maka Perjanjian berakhir dengan sendirinya demi hukum tanpa mengurangi hak dan kewajiban PARA PIHAK yang telah timbul sebelum berakhirnya Perjanjian.</li>
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
        <li>PIHAK PERTAMA wajib menghentikan publikasi baru atas Karya setelah masa kontrak 5 (lima) tahun habis, kecuali disepakati perpanjangan oleh PARA PIHAK.</li>
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
                    Judul Karya: {{ $story->title }}<br>
                    Durasi: {{ $contract->duration_years ?? 5 }} Tahun<br>
                    Nama Penandatangan: {{ $contract->signer_name }}<br>
                    Waktu: {{ \Carbon\Carbon::parse($contract->signed_at)->format('d/m/Y H:i:s') }} WIB<br>
                    IP Address: {{ $contract->signature_ip }}
                </div>
                <p class="name-underlined">{{ $contract->signer_name }}</p>
                <p style="margin: 0; font-size: 10pt;">NIK: {{ $contract->ktp_number }}</p>
            </td>
        </tr>
    </table>

</body>

</html>
