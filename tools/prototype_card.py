#!/usr/bin/env python3
"""Prototype the upgraded editorial product-card art so we can eyeball it
before porting the composition to the SVG generator."""
import os, math, random
from PIL import Image, ImageDraw, ImageFont, ImageFilter

CW, CH = 520, 693
GAP = 30

GOLD = (183, 147, 94)
PLUM = (110, 66, 83)
INK = (58, 48, 54)

FREE = "/usr/share/fonts/truetype/freefont"
LIB = "/usr/share/fonts/truetype/liberation"
serif = lambda s: ImageFont.truetype(os.path.join(FREE, "FreeSerif.ttf"), s)
serif_it = lambda s: ImageFont.truetype(os.path.join(FREE, "FreeSerifItalic.ttf"), s)
sans = lambda s: ImageFont.truetype(os.path.join(LIB, "LiberationSans-Regular.ttf"), s)

VARIANTS = [
    {"name": "GULRANG LAWN", "price": "Rs 6,990", "tones": [(243, 233, 219), (231, 205, 203), (176, 122, 134)], "acc": (138, 86, 96)},
    {"name": "NOOR ORGANZA", "price": "Rs 18,500", "tones": [(238, 223, 216), (207, 166, 170), (95, 52, 70)], "acc": (110, 66, 83)},
    {"name": "MEHER KURTA", "price": "Rs 8,490", "tones": [(243, 234, 220), (220, 197, 152), (150, 116, 70)], "acc": (150, 116, 70)},
]


def lerp(a, b, t):
    return tuple(int(a[i] + (b[i] - a[i]) * t) for i in range(3))


def diag_gradient(size, tones):
    w, h = size
    g = Image.new("RGB", (w, h))
    px = g.load()
    for y in range(h):
        for x in range(0, w, 2):
            t = (x / w * 0.55 + y / h * 0.65)
            t = min(1, t)
            if t < 0.5:
                c = lerp(tones[0], tones[1], t / 0.5)
            else:
                c = lerp(tones[1], tones[2], (t - 0.5) / 0.5)
            px[x, y] = c
            if x + 1 < w:
                px[x + 1, y] = c
    return g


def add_grain(img, amount=10):
    w, h = img.size
    noise = Image.effect_noise((w, h), amount).convert("L")
    noise_rgb = Image.merge("RGB", (noise, noise, noise))
    return Image.blend(img, noise_rgb, 0.06)


def vignette(img):
    w, h = img.size
    mask = Image.new("L", (w, h), 0)
    d = ImageDraw.Draw(mask)
    d.ellipse([-w * 0.25, -h * 0.2, w * 1.25, h * 1.2], fill=255)
    mask = mask.filter(ImageFilter.GaussianBlur(80))
    dark = Image.new("RGB", (w, h), (40, 22, 30))
    return Image.composite(img, Image.blend(img, dark, 0.5), mask)


def sprig(d, ox, oy, scale, rot, color, alpha):
    def pt(px, py):
        r = math.radians(rot)
        return (ox + (px * math.cos(r) - py * math.sin(r)) * scale,
                oy + (px * math.sin(r) + py * math.cos(r)) * scale)
    stem = [pt(0, 0), pt(16, -24), pt(38, -32), pt(64, -30)]
    d.line(stem, fill=color + (alpha,), width=2, joint="curve")
    for bx, by in [(18, -16), (32, -26), (48, -28)]:
        d.line([pt(bx * 0.6, by * 0.5), pt(bx, by - 16)], fill=color + (alpha,), width=2)
    for cx, cy, r in [(6, -30, 5), (40, -44, 4), (64, -32, 5)]:
        p = pt(cx, cy)
        d.ellipse([p[0] - r, p[1] - r, p[0] + r, p[1] + r], outline=color + (alpha + 40,), width=2)


def tracked(draw, pos, text, fnt, fill, tracking, center=False):
    widths = [draw.textlength(c, font=fnt) for c in text]
    total = sum(widths) + tracking * (len(text) - 1)
    x, y = pos
    if center:
        x -= total / 2
    for c, wc in zip(text, widths):
        draw.text((x, y), c, font=fnt, fill=fill)
        x += wc + tracking
    return total


def make_card(v):
    img = diag_gradient((CW, CH), v["tones"])
    img = add_grain(img)
    img = vignette(img)

    ov = Image.new("RGBA", (CW, CH), (0, 0, 0, 0))
    d = ImageDraw.Draw(ov)
    acc = v["acc"]

    # Grand arch outline (Mughal-inspired): rounded-top tall arch, inset.
    m = 46
    aw = CW - 2 * m
    top = 70
    # arch as rounded rectangle with big top radius
    d.rounded_rectangle([m, top, CW - m, CH - 60], radius=int(aw / 2), outline=GOLD + (150,), width=2)
    d.rectangle([m, top + int(aw / 2), CW - m, CH - 60], outline=None)
    # redraw straight sides + bottom of arch (since rounded_rectangle rounds bottom too)
    d.line([(m, top + aw / 2), (m, CH - 60)], fill=GOLD + (150,), width=2)
    d.line([(CW - m, top + aw / 2), (CW - m, CH - 60)], fill=GOLD + (150,), width=2)
    d.line([(m, CH - 60), (CW - m, CH - 60)], fill=GOLD + (150,), width=2)

    # botanical sprigs
    sprig(d, m + 24, top + 110, 1.1, 0, acc, 120)
    sprig(d, CW - m - 24, CH - 110, 1.1, 180, acc, 120)

    # faint monogram
    cd = ImageDraw.Draw(ov)
    cd.text((CW / 2, CH / 2 - 30), "C", font=serif(300), fill=acc + (28,), anchor="mm")

    img = Image.alpha_composite(img.convert("RGBA"), ov).convert("RGB")
    d = ImageDraw.Draw(img)

    # type block
    d.text((CW / 2, CH / 2 + 70), "Chelé", font=serif(58), fill=(74, 58, 52), anchor="mm")
    tracked(d, (CW / 2, CH / 2 + 110), v["name"], sans(15), acc, 6, center=True)
    d.line([(CW / 2 - 34, CH / 2 + 150), (CW / 2 + 34, CH / 2 + 150)], fill=GOLD, width=1)
    return img


def main():
    cards = [make_card(v) for v in VARIANTS]
    total_w = CW * 3 + GAP * 2
    canvas = Image.new("RGB", (total_w, CH), (245, 239, 230))
    x = 0
    for c in cards:
        canvas.paste(c, (x, 0))
        x += CW + GAP
    out = os.path.join(os.path.dirname(__file__), "prototype_cards.png")
    canvas.save(out)
    print("Wrote", out, canvas.size)


if __name__ == "__main__":
    main()
