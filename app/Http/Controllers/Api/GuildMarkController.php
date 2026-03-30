<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game\Guild;
use Illuminate\Http\Response;

class GuildMarkController extends Controller
{
    /**
     * MuOnline standard 16-color palette (Windows CGA palette order).
     * Each entry is [R, G, B]. Index 0 is rendered as transparent (white background).
     */
    private const PALETTE = [
        [0,   0,   0  ], // 0  black (transparent)
        [0,   0,   128], // 1  dark blue
        [0,   128, 0  ], // 2  dark green
        [0,   128, 128], // 3  dark cyan
        [128, 0,   0  ], // 4  dark red
        [128, 0,   128], // 5  dark magenta
        [128, 128, 0  ], // 6  dark yellow/brown
        [192, 192, 192], // 7  light gray
        [128, 128, 128], // 8  dark gray
        [0,   0,   255], // 9  blue
        [0,   255, 0  ], // 10 green
        [0,   255, 255], // 11 cyan
        [255, 0,   0  ], // 12 red
        [255, 0,   255], // 13 magenta
        [255, 255, 0  ], // 14 yellow
        [255, 255, 255], // 15 white
    ];

    /** Each cell is 8×8 px; the mark is an 8×8 grid of cells → 64×64 output. */
    private const CELL   = 8;
    private const GRID   = 8;
    private const OUTPUT = self::CELL * self::GRID; // 64

    /**
     * GET /api/guild-mark/{name}
     *
     * Returns the guild mark as a PNG image (64×64 px).
     * Responds with a 1×1 white PNG when the guild or mark is missing.
     */
    public function show(string $name): Response
    {
        $guild = Guild::select('G_Mark')->where('G_Name', $name)->first();

        if (!$guild || !$guild->G_Mark) {
            return $this->emptyPng();
        }

        $raw = $guild->G_Mark;

        // SQL Server may return the binary column as a hex string (e.g. "0x1A2B…").
        if (is_string($raw) && str_starts_with($raw, '0x')) {
            $raw = hex2bin(substr($raw, 2));
        }

        // G_Mark must be exactly 32 bytes (8×8 pixels, 4 bits/pixel).
        if (strlen($raw) !== 32) {
            return $this->emptyPng();
        }

        $image = imagecreatetruecolor(self::OUTPUT, self::OUTPUT);

        // White background (shown for transparent color-index 0).
        $bg = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $bg);

        // Decode pixels: each byte holds 2 pixels (low nibble = left pixel).
        $pixels = [];
        foreach (str_split($raw) as $byte) {
            $b        = ord($byte);
            $pixels[] = $b & 0x0F;        // low nibble → left pixel
            $pixels[] = ($b >> 4) & 0x0F; // high nibble → right pixel
        }
        // $pixels now has 64 entries (8 rows × 8 columns).

        foreach ($pixels as $i => $colorIndex) {
            if ($colorIndex === 0) {
                continue; // transparent — leave white background
            }

            [$r, $g, $b] = self::PALETTE[$colorIndex];
            $color = imagecolorallocate($image, $r, $g, $b);

            $col = $i % self::GRID;
            $row = intdiv($i, self::GRID);

            // Scale each pixel to a CELL×CELL block.
            $x = $col * self::CELL;
            $y = $row * self::CELL;
            imagefilledrectangle($image, $x, $y, $x + self::CELL - 1, $y + self::CELL - 1, $color);
        }

        ob_start();
        imagepng($image);
        $png = ob_get_clean();
        imagedestroy($image);

        return response($png, 200, ['Content-Type' => 'image/png']);
    }

    private function emptyPng(): Response
    {
        // Minimal 1×1 white PNG.
        $image = imagecreatetruecolor(1, 1);
        imagecolorallocate($image, 255, 255, 255);
        ob_start();
        imagepng($image);
        $png = ob_get_clean();
        imagedestroy($image);

        return response($png, 200, ['Content-Type' => 'image/png']);
    }
}
