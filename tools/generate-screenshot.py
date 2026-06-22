#!/usr/bin/env python3
"""Generate the Chelé WordPress theme screenshot (screenshot.png).

Renders a branded homepage-style composition in the Chelé palette so the
theme presents beautifully in the WordPress theme picker. Output is written
to chele/screenshot.png at the recommended 1200x900 size.
"""
import os
from PIL import Image, ImageDraw, ImageFont

W, H = 1200, 900
OUT = os.path.join(os.path.dirname(__file__), "..", "chele", "screenshot.png")

# Palette
CREAM = (245, 239, 230)
CREAM_SOFT = (251, 247, 240)
SAND = (236, 226, 211)
PLUM = (110, 66, 83)
PLUM_DEEP = (74, 42, 56)
ROSE = (201, 155, 161)
ROSE_SOFT = (231, 210, 207)
ROSE_MIST = (241, 226, 223)
GOLD = (183, 147, 94)
GOLD_SOFT = (216, 192, 150)
NAVY = (40, 56, 75)
INK = (58, 48, 54)
MUTED = (141, 128, 121)

FONT_DIR_FREE = "/usr/share/fonts/truetype/freefont"
FONT_DIR_LIB = "/usr/share/fonts/truetype/liberation"

def font(path, size):
    return ImageFont.truetype(path, size)

serif = lambda s: font(os.path.join(FONT_DIR_FREE, "FreeSerif.ttf"), s)
serif_it = lambda s: font(os.path.join(FONT_DIR_FREE, "FreeSerifItalic.ttf"), s)
sans = lambda s: font(os.path.join(FONT_DIR_LIB, "LiberationSans-Regular.ttf"), s)
sans_bold = lambda s: font(os.path.join(FONT_DIR_LIB, "LiberationSans-Bold.ttf"), s)


def lerp(a, b, t):
    return tuple(int(a[i] + (b[i] - a[i]) * t) for i in range(3))


def vgradient(size, top, bottom):
    w, h = size
    grad = Image.new("RGB", (1, h))
    for y in range(h):
        grad.putpixel((0, y), lerp(top, bottom, y / max(1, h - 1)))
    return grad.resize((w, h))


def arch_mask(size, radius):
    """Rounded top corners, squared bottom."""
    w, h = size
    m = Image.new("L", (w, h), 0)
    d = ImageDraw.Draw(m)
    r = min(radius, w // 2, h // 2)
    d.rounded_rectangle([0, 0, w - 1, h - 1], radius=r, fill=255)
    d.rectangle([0, h // 2, w - 1, h - 1], fill=255)
    return m


def panel(base, box, top, bottom, radius=120):
    x0, y0, x1, y1 = box
    w, h = x1 - x0, y1 - y0
    grad = vgradient((w, h), top, bottom)
    mask = arch_mask((w, h), radius)
    base.paste(grad, (x0, y0), mask)
    return mask


def tracked(draw, pos, text, fnt, fill, tracking=0, center=False, anchor_top=True):
    widths = [draw.textlength(ch, font=fnt) for ch in text]
    total = sum(widths) + tracking * (len(text) - 1 if text else 0)
    x, y = pos
    if center:
        x -= total / 2
    asc, desc = fnt.getmetrics()
    yy = y if anchor_top else y - asc
    for ch, wch in zip(text, widths):
        draw.text((x, yy), ch, font=fnt, fill=fill)
        x += wch + tracking
    return total


def sprig(overlay, ox, oy, scale=1.0, rot=0, color=GOLD, alpha=150):
    d = ImageDraw.Draw(overlay)
    import math
    def pt(px, py):
        rad = math.radians(rot)
        return (ox + (px * math.cos(rad) - py * math.sin(rad)) * scale,
                oy + (px * math.sin(rad) + py * math.cos(rad)) * scale)
    stem = [pt(0, 0), pt(14, -22), pt(34, -30), pt(58, -28)]
    d.line(stem, fill=color + (alpha,), width=2, joint="curve")
    for bx, by in [(20, -18), (32, -26), (46, -28)]:
        d.line([pt(bx * 0.6, by * 0.5), pt(bx, by - 14)], fill=color + (alpha,), width=2)
    for cx, cy, r in [(6, -28, 4), (40, -42, 3), (60, -30, 4)]:
        p = pt(cx, cy)
        d.ellipse([p[0] - r, p[1] - r, p[0] + r, p[1] + r], fill=color + (alpha + 30,))


def needle_logo(draw, cx, top):
    """Small 'chelé' wordmark with a needle accent, centred."""
    f = serif(46)
    text = "chelé"
    w = draw.textlength(text, font=f)
    x = cx - w / 2
    draw.text((x, top), text, font=f, fill=NAVY)
    # needle through the area of the second 'l'
    nx = x + draw.textlength("chel", font=f) - 6
    draw.line([(nx, top + 4), (nx, top + 54)], fill=NAVY, width=3)
    draw.arc([nx - 12, top - 2, nx + 4, top + 22], start=200, end=20, fill=NAVY, width=2)


def main():
    base = Image.new("RGB", (W, H), CREAM)
    overlay = Image.new("RGBA", (W, H), (0, 0, 0, 0))

    # Soft rose mist, top-right radial.
    for i in range(60):
        t = i / 60
        r = int(620 * (1 - t))
        col = lerp(CREAM, ROSE_MIST, t)
        od = ImageDraw.Draw(overlay)
        od.ellipse([W - 360 - r, -260 - r // 2, W - 360 + r, -260 + r], fill=col + (6,))
    # Soft warm glow, bottom-left.
    for i in range(50):
        t = i / 50
        r = int(520 * (1 - t))
        col = lerp(CREAM, SAND, t)
        ImageDraw.Draw(overlay).ellipse([-260 - r, H - 120 - r, -260 + r, H - 120 + r], fill=col + (6,))

    base = Image.alpha_composite(base.convert("RGBA"), overlay).convert("RGB")
    draw = ImageDraw.Draw(base)
    overlay = Image.new("RGBA", (W, H), (0, 0, 0, 0))

    # Announcement bar.
    draw.rectangle([0, 0, W, 40], fill=PLUM_DEEP)
    tracked(draw, (W / 2, 13), "MADE WITH LOVE, WORN WITH PRIDE", sans(13), ROSE_SOFT, tracking=5, center=True)

    # Logo.
    needle_logo(draw, W / 2, 66)
    tracked(draw, (W / 2, 124), "EST. 2024  ·  DESIGNER APPAREL", sans(11), MUTED, tracking=4, center=True)
    draw.line([(W / 2 - 70, 150), (W / 2 + 70, 150)], fill=GOLD, width=1)

    # ---- Hero left copy ----
    LX = 90
    tracked(draw, (LX, 220), "ELEGANCE. TRADITION. YOU.", sans(15), GOLD, tracking=6)
    draw.text((LX - 6, 250), "Chelé", font=serif(150), fill=PLUM)
    tracked(draw, (LX, 420), "LADIES & GIRLS DRESSES OF PAKISTAN", sans(16), INK, tracking=5)

    para = ["Celebrating the beauty of Pakistani fashion",
            "with timeless designs, premium fabrics and",
            "flawless, hand-finished details."]
    fy = 470
    for line in para:
        draw.text((LX, fy), line, font=sans(19), fill=(90, 79, 73))
        fy += 32

    # Button.
    bx0, by0, bx1, by1 = LX, 590, LX + 290, 648
    draw.rectangle([bx0, by0, bx1, by1], fill=PLUM)
    tracked(draw, ((bx0 + bx1) / 2, by0 + 21, ), "SHOP THE COLLECTION", sans(14), CREAM, tracking=3, center=True)

    # pillar labels row
    pillars = ["PREMIUM FABRICS", "EXQUISITE DETAILS", "MADE FOR YOU"]
    px = LX
    for i, p in enumerate(pillars):
        wlab = tracked(draw, (px, 690), p, sans(12), MUTED, tracking=3)
        if i < len(pillars) - 1:
            draw.text((px + wlab + 14, 688), "✦", font=sans(11), fill=GOLD)
        px += wlab + 44

    # ---- Hero right visual (arch panel) ----
    HX0, HY0, HX1, HY1 = 720, 210, 1110, 760
    mask = panel(base, (HX0, HY0, HX1, HY1), top=(241, 230, 214), bottom=ROSE_SOFT, radius=170)
    draw = ImageDraw.Draw(base)
    # frame hairline
    fr = Image.new("RGBA", (HX1 - HX0, HY1 - HY0), (0, 0, 0, 0))
    frd = ImageDraw.Draw(fr)
    frd.rounded_rectangle([1, 1, HX1 - HX0 - 2, HY1 - HY0 - 2], radius=168, outline=GOLD + (110,), width=2)
    base.paste(fr, (HX0, HY0), Image.composite(fr.split()[3], Image.new("L", fr.size, 0), mask.resize(fr.size)) if False else fr)

    ov = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    # monogram C
    cd = ImageDraw.Draw(ov)
    cd.text(((HX0 + HX1) / 2, (HY0 + HY1) / 2 - 40), "C", font=serif(360), fill=PLUM + (22,), anchor="mm")
    sprig(ov, HX0 + 70, HY0 + 150, scale=1.4, rot=0, color=PLUM, alpha=90)
    sprig(ov, HX1 - 70, HY1 - 120, scale=1.4, rot=180, color=PLUM, alpha=90)
    base = Image.alpha_composite(base.convert("RGBA"), ov).convert("RGB")
    draw = ImageDraw.Draw(base)

    # panel label
    draw.text(((HX0 + HX1) / 2, 470), "Chelé", font=serif(54), fill=(74, 58, 52), anchor="mm")
    tracked(draw, ((HX0 + HX1) / 2, 512), "SIGNATURE EDIT", sans(15), PLUM, tracking=6, center=True)
    draw.line([((HX0 + HX1) / 2 - 40, 548), ((HX0 + HX1) / 2 + 40, 548)], fill=GOLD, width=1)

    # circular badge
    bcx, bcy, br = HX1 - 28, HY1 - 150, 58
    draw.ellipse([bcx - br, bcy - br, bcx + br, bcy + br], fill=CREAM, outline=GOLD_SOFT, width=2)
    draw.text((bcx, bcy - 16), "since", font=serif_it(26), fill=PLUM, anchor="mm")
    draw.text((bcx, bcy + 16), "2024", font=serif(30), fill=GOLD, anchor="mm")

    # ---- Bottom product cards ----
    cards = [
        ("LAWN", "Gulrang Lawn", "Rs 6,990", (241, 230, 214), (231, 205, 203)),
        ("LUXURY", "Noor Organza", "Rs 18,500", (236, 220, 212), (207, 166, 170)),
        ("FORMAL", "Meher Kurta", "Rs 8,490", (241, 232, 218), (216, 193, 149)),
        ("GIRLS", "Aira Frock", "Rs 4,290", (231, 225, 210), (188, 196, 175)),
    ]
    n = len(cards)
    gap = 26
    cw = (W - 180 - gap * (n - 1)) // n
    ch = 150
    cy0 = 762
    cx = 90
    for coll, name, price, t, b in cards:
        panel(base, (cx, cy0, cx + cw, cy0 + ch), top=t, bottom=b, radius=22)
        cx += cw + gap
    draw = ImageDraw.Draw(base)

    base.save(OUT, "PNG")
    print("Wrote", os.path.normpath(OUT), base.size)


if __name__ == "__main__":
    main()
