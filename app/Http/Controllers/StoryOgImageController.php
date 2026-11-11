<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Support\Facades\Storage;

/**
 * Gambar pratinjau 1200x630 untuk og:image (PHP GD murni, tidak perlu composer package).
 *
 * Font (letakkan di public/fonts, ambil dari folder "static" hasil unduhan Google Fonts):
 *   - Inter-Bold.ttf     (wajib)
 *   - Inter-Regular.ttf  (opsional; kalau tidak ada, dipakai Bold)
 *
 * Logo (PNG/JPG/WebP, GD tidak bisa membaca SVG). Taruh di salah satu path pada LOGO_CANDIDATES.
 * Latar gambar gelap, jadi pakai logo versi terang/putih (transparan) kalau ada.
 *
 * Route (WAJIB publik, tanpa middleware auth):
 *   Route::get('/stories/{story:slug}/og.jpg', [StoryOgImageController::class, 'show'])->name('stories.og');
 */
class StoryOgImageController extends Controller
{
    private const W = 1200;
    private const H = 630;

    // Naikkan angka ini setiap desain diubah, supaya gambar lama di cache tidak dipakai lagi.
    private const TEMPLATE_VERSION = 3;

    // Warna primary Kisa: #38CAC8
    private const PRIMARY = [56, 202, 200];

    // Teks di atas warna primary memakai navy gelap (putih di atas #38CAC8 kontrasnya rendah).
    private const ON_PRIMARY = [15, 23, 42];

    // Dicari berurutan di folder public/. Yang pertama ditemukan dipakai.
    private const LOGO_CANDIDATES = [
        'images/logo-white.png',
        'images/logo-light.png',
        'images/logo.png',
        'images/logo.webp',
        'images/logo.jpg',
        'logo.png',
    ];

    public function show(Story $story)
    {
        abort_unless(function_exists('imagettftext'), 500, 'Ekstensi GD dengan FreeType belum aktif.');

        $dir  = storage_path('app/og');
        $file = $dir . '/story-' . $story->id . '-v' . self::TEMPLATE_VERSION . '-' . optional($story->updated_at)->timestamp . '.jpg';

        if (! file_exists($file)) {
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $this->render($story, $file);
        }

        return response()->file($file, [
            'Content-Type'  => 'image/jpeg',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    private function render(Story $story, string $target): void
    {
        $bold    = public_path('fonts/Inter-Bold.ttf');
        $regular = public_path('fonts/Inter-Regular.ttf');

        abort_unless(file_exists($bold), 500, "Font tidak ditemukan: {$bold}");
        if (! file_exists($regular)) {
            $regular = $bold;
        }

        $W = self::W;
        $H = self::H;

        $img = imagecreatetruecolor($W, $H);
        imagealphablending($img, true);

        // 1. Latar belakang: gradasi vertikal navy -> indigo gelap
        for ($y = 0; $y < $H; $y++) {
            $c = $this->mix([15, 23, 42], [13, 52, 66], $y / ($H - 1));
            imageline($img, 0, $y, $W, $y, imagecolorallocate($img, $c[0], $c[1], $c[2]));
        }

        // 2. Cahaya lembut (indigo di kiri atas, amber di kanan bawah)
        $this->glow($img, 250, 120, 560, self::PRIMARY, 126);
        $this->glow($img, 1120, 620, 460, [245, 158, 11], 126);

        // 3. Cover dengan bayangan dan sudut membulat
        $cw = 330;
        $ch = 480;
        $cx = 80;
        $cy = 75;
        $radius = 22;

        $this->shadow($img, $cx, $cy + 18, $cw, $ch, $radius);

        $cover = $this->loadCover($story, $cw, $ch, $radius);
        if ($cover) {
            imagecopy($img, $cover, $cx, $cy, 0, 0, $cw, $ch);
        } else {
            // Tanpa cover: kotak slate dengan huruf awal judul
            $this->roundedRect($img, $cx, $cy, $cx + $cw, $cy + $ch, $radius, imagecolorallocate($img, 30, 41, 59));
            $initial = mb_strtoupper(mb_substr(trim($story->title), 0, 1));
            $iw = $this->textWidth(150, $bold, $initial);
            $this->text($img, 150, $cx + (int) (($cw - $iw) / 2), $cy + 290, self::PRIMARY, $bold, $initial);
        }

        // 4. Kolom teks (kanan)
        $tx = $cx + $cw + 70;     // 480
        $tw = $W - $tx - 80;      // 640

        // Brand: logo Kisa (fallback: badge "K" + teks)
        $this->drawBrand($img, $tx, 66, $bold);

        // Pill: genre + jumlah bab
        $genre    = $story->genre->name ?? 'Fiksi';
        $chapters = $story->chapters()->where('status', 'published')->count();

        $px = $tx;
        $pills = [
            [$genre, [15, 76, 82], [167, 243, 240]],
            [$chapters . ' Bab', [30, 41, 59], [203, 213, 225]],
        ];
        foreach ($pills as [$label, $bg, $fg]) {
            $w = $this->textWidth(18, $bold, $label) + 36;
            $this->roundedRect($img, $px, 138, $px + $w, 176, 19, imagecolorallocate($img, $bg[0], $bg[1], $bg[2]));
            $this->text($img, 18, $px + 18, 163, $fg, $bold, $label);
            $px += $w + 12;
        }

        // Judul (maks 3 baris)
        $y  = 252;
        $lh = 66;
        foreach ($this->wrap((string) $story->title, 56, $bold, $tw, 3) as $line) {
            $this->text($img, 56, $tx, $y, [255, 255, 255], $bold, $line);
            $y += $lh;
        }

        // Penulis
        $y = $y - $lh + 52;
        $author = $story->penName->name ?? 'Penulis';
        $this->text($img, 28, $tx, $y, [251, 191, 36], $bold, $this->wrap('Oleh ' . $author, 28, $bold, $tw, 1)[0]);
        $y += 50;

        // Sinopsis (maks 2 baris, hanya kalau masih muat di atas footer)
        $synopsis = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string) $story->synopsis))));
        $fit = (int) floor((505 - $y) / 34) + 1;
        if ($synopsis !== '' && $fit >= 1) {
            foreach ($this->wrap($synopsis, 22, $regular, $tw, min(2, $fit)) as $line) {
                $this->text($img, 22, $tx, $y, [148, 163, 184], $regular, $line);
                $y += 34;
            }
        }

        // Footer
        imagefilledrectangle($img, $tx, 522, $tx + $tw, 523, imagecolorallocate($img, 51, 65, 85));
        $this->text($img, 20, $tx, 575, [148, 163, 184], $regular, 'Baca selengkapnya di');

        $label = 'kisaindonesia.id';
        $w  = $this->textWidth(20, $bold, $label) + 36;
        $x2 = $tx + $tw;
        $this->roundedRect($img, $x2 - $w, 546, $x2, 590, 12, imagecolorallocate($img, self::PRIMARY[0], self::PRIMARY[1], self::PRIMARY[2]));
        $this->text($img, 20, $x2 - $w + 18, 575, self::ON_PRIMARY, $bold, $label);

        imagejpeg($img, $target, 88);
    }

    /* ---------- Helper gambar ---------- */

    private function mix(array $a, array $b, float $t): array
    {
        return [
            (int) round($a[0] + ($b[0] - $a[0]) * $t),
            (int) round($a[1] + ($b[1] - $a[1]) * $t),
            (int) round($a[2] + ($b[2] - $a[2]) * $t),
        ];
    }

    /** Cahaya radial lembut dari lingkaran transparan bertumpuk. */
    private function glow($img, int $cx, int $cy, int $radius, array $rgb, int $alpha, int $steps = 36): void
    {
        $color = imagecolorallocatealpha($img, $rgb[0], $rgb[1], $rgb[2], $alpha);
        for ($i = 0; $i < $steps; $i++) {
            $r = (int) ($radius * (1 - $i / $steps));
            imagefilledellipse($img, $cx, $cy, $r * 2, $r * 2, $color);
        }
    }

    private function roundedRect($img, int $x1, int $y1, int $x2, int $y2, int $r, int $color): void
    {
        imagefilledrectangle($img, $x1 + $r, $y1, $x2 - $r, $y2, $color);
        imagefilledrectangle($img, $x1, $y1 + $r, $x2, $y2 - $r, $color);
        imagefilledellipse($img, $x1 + $r, $y1 + $r, $r * 2, $r * 2, $color);
        imagefilledellipse($img, $x2 - $r, $y1 + $r, $r * 2, $r * 2, $color);
        imagefilledellipse($img, $x1 + $r, $y2 - $r, $r * 2, $r * 2, $color);
        imagefilledellipse($img, $x2 - $r, $y2 - $r, $r * 2, $r * 2, $color);
    }

    /** Bayangan lembut: gambar kotak hitam, blur di resolusi kecil, lalu gabungkan. */
    private function shadow($img, int $x, int $y, int $w, int $h, int $r): void
    {
        $pad = 70;
        $rx = max(0, $x - $pad);
        $ry = max(0, $y - $pad);
        $rw = min(self::W - $rx, $w + 2 * $pad);
        $rh = min(self::H - $ry, $h + 2 * $pad);

        $tmp = imagecreatetruecolor($rw, $rh);
        imagecopy($tmp, $img, 0, 0, $rx, $ry, $rw, $rh);
        $black = imagecolorallocate($tmp, 0, 0, 0);
        $this->roundedRect($tmp, $x - $rx, $y - $ry, $x - $rx + $w, $y - $ry + $h, $r, $black);

        $small = imagescale($tmp, (int) ($rw / 4), (int) ($rh / 4), IMG_BILINEAR_FIXED);
        for ($i = 0; $i < 25; $i++) {
            imagefilter($small, IMG_FILTER_GAUSSIAN_BLUR);
        }
        $big = imagescale($small, $rw, $rh, IMG_BILINEAR_FIXED);

        imagecopymerge($img, $big, $rx, $ry, 0, 0, $rw, $rh, 75);
    }

    /** Baca cover, crop ke rasio kotak, dan bulatkan sudutnya dengan tepi halus. */
    private function loadCover(Story $story, int $w, int $h, int $r)
    {
        if (! $story->cover_path) {
            return null;
        }

        $path = Storage::disk('public')->path($story->cover_path);
        if (! is_file($path)) {
            return null;
        }

        $src = @imagecreatefromstring((string) file_get_contents($path));
        if (! $src) {
            return null;
        }

        $sw = imagesx($src);
        $sh = imagesy($src);
        $scale = max($w / $sw, $h / $sh);
        $cropW = (int) round($w / $scale);
        $cropH = (int) round($h / $scale);
        $sx = (int) (($sw - $cropW) / 2);
        $sy = (int) (($sh - $cropH) / 2);

        $dst = imagecreatetruecolor($w, $h);
        imagecopyresampled($dst, $src, 0, 0, $sx, $sy, $w, $h, $cropW, $cropH);

        imagealphablending($dst, false);
        $corners = [
            [0, 0, $r, $r],
            [$w - $r, 0, $w - $r, $r],
            [0, $h - $r, $r, $h - $r],
            [$w - $r, $h - $r, $w - $r, $h - $r],
        ];
        foreach ($corners as [$x0, $y0, $ccx, $ccy]) {
            for ($x = $x0; $x < $x0 + $r; $x++) {
                for ($y = $y0; $y < $y0 + $r; $y++) {
                    $d = sqrt(($x - $ccx) ** 2 + ($y - $ccy) ** 2) - $r;
                    if ($d <= -0.5) {
                        continue;
                    }
                    $alpha = $d >= 0.5 ? 127 : (int) round(127 * ($d + 0.5));
                    imagesetpixel($dst, $x, $y, ($alpha << 24) | (imagecolorat($dst, $x, $y) & 0xFFFFFF));
                }
            }
        }

        return $dst;
    }

    /** Gambar logo Kisa. Logo persegi diberi teks "KISA INDONESIA"; logo lebar (wordmark) dipakai apa adanya. */
    private function drawBrand($img, int $x, int $y, string $font): void
    {
        $height = 44;
        $logo = $this->loadLogo($height);

        if ($logo) {
            imagealphablending($img, true);
            imagecopy($img, $logo, $x, $y, 0, 0, imagesx($logo), imagesy($logo));

            // Logo berbentuk ikon (hampir persegi): tambahkan nama brand di sampingnya
            if (imagesx($logo) / imagesy($logo) <= 1.3) {
                $this->text($img, 19, $x + imagesx($logo) + 14, $y + 30, [167, 243, 240], $font, 'KISA INDONESIA');
            }

            return;
        }

        // Fallback bila file logo tidak ditemukan
        $primary = imagecolorallocate($img, self::PRIMARY[0], self::PRIMARY[1], self::PRIMARY[2]);
        $this->roundedRect($img, $x, $y + 7, $x + 30, $y + 37, 8, $primary);
        $this->text($img, 18, $x + 9, $y + 29, self::ON_PRIMARY, $font, 'K');
        $this->text($img, 19, $x + 44, $y + 29, [167, 243, 240], $font, 'KISA INDONESIA');
    }

    private function loadLogo(int $height)
    {
        foreach (self::LOGO_CANDIDATES as $candidate) {
            $path = public_path($candidate);
            if (! is_file($path)) {
                continue;
            }

            $src = @imagecreatefromstring((string) file_get_contents($path));
            if (! $src) {
                continue;
            }

            $w = max(1, (int) round(imagesx($src) * $height / imagesy($src)));

            $dst = imagecreatetruecolor($w, $height);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 0, 0, 0, 127));
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $height, imagesx($src), imagesy($src));

            return $dst;
        }

        return null;
    }

    /* ---------- Helper teks (ukuran dalam px; GD memakai pt, jadi dikali 0.75) ---------- */

    private function text($img, float $px, int $x, int $y, array $rgb, string $font, string $text): void
    {
        $color = imagecolorallocate($img, $rgb[0], $rgb[1], $rgb[2]);
        imagettftext($img, $px * 0.75, 0, $x, $y, $color, $font, $text);
    }

    private function textWidth(float $px, string $font, string $text): int
    {
        $b = imagettfbbox($px * 0.75, 0, $font, $text);

        return (int) abs($b[2] - $b[0]);
    }

    private function wrap(string $text, float $px, string $font, int $maxWidth, int $maxLines): array
    {
        $width = fn(string $t) => $this->textWidth($px, $font, $t);

        $words = preg_split('/\s+/u', trim($text), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $lines = [];
        $line  = '';

        foreach ($words as $word) {
            $try = $line === '' ? $word : $line . ' ' . $word;
            if ($line !== '' && $width($try) > $maxWidth) {
                $lines[] = $line;
                $line = $word;
            } else {
                $line = $try;
            }
        }
        if ($line !== '') {
            $lines[] = $line;
        }

        if (count($lines) > $maxLines) {
            $lines = array_slice($lines, 0, $maxLines);
            $last = rtrim($lines[$maxLines - 1], " .,;:!?");
            while ($last !== '' && $width($last . '…') > $maxWidth) {
                $last = mb_substr($last, 0, -1);
            }
            $lines[$maxLines - 1] = $last . '…';
        }

        return $lines ?: [''];
    }
}
