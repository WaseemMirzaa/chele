#!/usr/bin/env python3
"""Generate the Chelé theme screenshot (chele/screenshot.png) reflecting the
v2 couture design: rich diagonal gradients, grain, vignette, gradient wordmark,
an arch hero panel with a rotating-seal motif, and premium product cards."""
import os, math
from PIL import Image, ImageDraw, ImageFont, ImageFilter

W, H = 1200, 900
OUT = os.path.join(os.path.dirname(__file__), "..", "chele", "screenshot.png")

CREAM = (246, 241, 232)
SAND = (236, 225, 210)
PLUM = (110, 66, 83)
PLUM_DEEP = (63, 34, 48)
NOIR = (36, 19, 24)
ROSE = (201, 155, 161)
ROSE_SOFT = (231, 210, 207)
ROSE_MIST = (241, 226, 223)
GOLD = (183, 147, 94)
GOLD_LT = (216, 192, 150)
GOLD_DEEP = (154, 120, 72)
NAVY = (40, 56, 75)
INK = (52, 41, 47)
MUTED = (141, 128, 121)

FREE = "/usr/share/fonts/truetype/freefont"
LIB = "/usr/share/fonts/truetype/liberation"
serif = lambda s: ImageFont.truetype(os.path.join(FREE, "FreeSerif.ttf"), s)
serif_it = lambda s: ImageFont.truetype(os.path.join(FREE, "FreeSerifItalic.ttf"), s)
sans = lambda s: ImageFont.truetype(os.path.join(LIB, "LiberationSans-Regular.ttf"), s)


def lerp(a, b, t):
    return tuple(int(a[i] + (b[i] - a[i]) * t) for i in range(3))


def diag_gradient(size, tones):
    w, h = size
    g = Image.new("RGB", (w, h))
    px = g.load()
    for y in range(h):
        for x in range(0, w):
            t = min(1, x / w * 0.55 + y / h * 0.65)
            if t < 0.5:
                c = lerp(tones[0], tones[1], t / 0.5)
            else:
                c = lerp(tones[1], tones[2], (t - 0.5) / 0.5)
            px[x, y] = c
    return g


def add_grain(img, blend=0.05):
    w, h = img.size
    noise = Image.effect_noise((w, h), 12).convert("L")
    return Image.blend(img, Image.merge("RGB", (noise, noise, noise)), blend)


def vignette(img, strength=0.5):
    w, h = img.size
    mask = Image.new("L", (w, h), 0)
    ImageDraw.Draw(mask).ellipse([-w * 0.25, -h * 0.2, w * 1.25, h * 1.2], fill=255)
    mask = mask.filter(ImageFilter.GaussianBlur(90))
    dark = Image.new("RGB", (w, h), (40, 22, 30))
    return Image.composite(img, Image.blend(img, dark, strength), mask)


def arch_mask(size, radius):
    w, h = size
    m = Image.new("L", (w, h), 0)
    d = ImageDraw.Draw(m)
    r = min(radius, w // 2)
    d.rounded_rectangle([0, 0, w - 1, h - 1], radius=r, fill=255)
    d.rectangle([0, h // 2, w - 1, h - 1], fill=255)
    return m


def tracked(draw, pos, text, fnt, fill, tracking=0, center=False):
    widths = [draw.textlength(c, font=fnt) for c in text]
    total = sum(widths) + tracking * (len(text) - 1 if text else 0)
    x, y = pos
    if center:
        x -= total / 2
    for c, wc in zip(text, widths):
        draw.text((x, y), c, font=fnt, fill=fill)
        x += wc + tracking
    return total


def gradient_text(base, pos, text, fnt, colors, anchor="lm"):
    """Draw text filled with a horizontal gradient."""
    tmp = Image.new("L", base.size, 0)
    ImageDraw.Draw(tmp).text(pos, text, font=fnt, fill=255, anchor=anchor)
    bbox = tmp.getbbox()
    if not bbox:
        return
    gw = bbox[2] - bbox[0]
    grad = Image.new("RGB", (max(1, gw), 1))
    for x in range(gw):
        t = x / max(1, gw - 1)
        if t < 0.5:
            c = lerp(colors[0], colors[1], t / 0.5)
        else:
            c = lerp(colors[1], colors[2], (t - 0.5) / 0.5)
        grad.putpixel((x, 0), c)
    grad = grad.resize((gw, bbox[3] - bbox[1]))
    full = Image.new("RGB", base.size)
    full.paste(grad, (bbox[0], bbox[1]))
    base.paste(full, (0, 0), tmp)


def sprig(d, ox, oy, scale, rot, color, alpha):
    def pt(px, py):
        r = math.radians(rot)
        return (ox + (px * math.cos(r) - py * math.sin(r)) * scale,
                oy + (px * math.sin(r) + py * math.cos(r)) * scale)
    d.line([pt(0, 0), pt(16, -24), pt(40, -32), pt(66, -30)], fill=color + (alpha,), width=2, joint="curve")
    for cx, cy, r in [(6, -52, 5), (52, -60, 4), (82, -44, 5)]:
        p = pt(cx, cy)
        d.ellipse([p[0] - r, p[1] - r, p[0] + r, p[1] + r], outline=GOLD + (alpha + 30,), width=2)


def needle_logo(draw, cx, top):
    f = serif(46)
    w = draw.textlength("chelé", font=f)
    x = cx - w / 2
    draw.text((x, top), "chelé", font=f, fill=NAVY)
    nx = x + draw.textlength("chel", font=f) - 6
    draw.line([(nx, top + 4), (nx, top + 54)], fill=NAVY, width=3)
    draw.arc([nx - 12, top - 2, nx + 4, top + 22], start=200, end=20, fill=NAVY, width=2)


def panel(box, tones, radius=170):
    x0, y0, x1, y1 = box
    w, h = x1 - x0, y1 - y0
    img = diag_gradient((w, h), tones)
    img = add_grain(img)
    img = vignette(img)
    return img, arch_mask((w, h), radius)


def card(size, tones, name, price):
    w, h = size
    img = diag_gradient((w, h), tones)
    img = add_grain(img)
    img = vignette(img, 0.45)
    d = ImageDraw.Draw(img, "RGBA")
    # arch outline
    m = 30
    d.rounded_rectangle([m, 46, w - m, h - 36], radius=int((w - 2 * m) / 2), outline=GOLD + (150,), width=2)
    d.line([(m, 46 + (w - 2 * m) / 2), (m, h - 36)], fill=GOLD + (150,), width=2)
    d.line([(w - m, 46 + (w - 2 * m) / 2), (w - m, h - 36)], fill=GOLD + (150,), width=2)
    d.line([(m, h - 36), (w - m, h - 36)], fill=GOLD + (150,), width=2)
    d.text((w / 2, h * 0.52), "Chelé", font=serif(34), fill=(251, 247, 240), anchor="mm")
    tracked(d, (w / 2, h * 0.52 + 26), name, sans(10), GOLD_LT, 4, center=True)
    return img


def main():
    base = Image.new("RGB", (W, H), CREAM)

    # soft radial tints
    ov = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    od = ImageDraw.Draw(ov)
    for i in range(60):
        t = i / 60
        r = int(640 * (1 - t))
        od.ellipse([W - 340 - r, -240 - r, W - 340 + r, -240 + r], fill=lerp(CREAM, ROSE_MIST, t) + (6,))
    base = Image.alpha_composite(base.convert("RGBA"), ov).convert("RGB")
    base = add_grain(base, 0.035)
    draw = ImageDraw.Draw(base)

    # announcement bar
    draw.rectangle([0, 0, W, 40], fill=NOIR)
    tracked(draw, (W / 2, 14), "COMPLIMENTARY DELIVERY ACROSS PAKISTAN  ✦  WORLDWIDE SHIPPING", sans(11), GOLD_LT, 4, center=True)

    # logo
    needle_logo(draw, W / 2, 64)
    tracked(draw, (W / 2, 122), "EST. 2024  ·  DESIGNER APPAREL", sans(10), MUTED, 4, center=True)
    draw.line([(W / 2 - 64, 146), (W / 2 + 64, 146)], fill=GOLD, width=1)

    # hero copy
    LX = 92
    draw.line([(LX, 224), (LX + 26, 224)], fill=GOLD, width=1)
    tracked(draw, (LX + 38, 217), "ELEGANCE. TRADITION. YOU.", sans(13), GOLD_DEEP, 5)
    gradient_text(base, (LX - 6, 250), "Chelé", serif(150), [(122, 74, 92), (154, 106, 120), (110, 66, 83)], anchor="lt")
    draw = ImageDraw.Draw(base)
    tracked(draw, (LX, 426), "LADIES & GIRLS DRESSES OF PAKISTAN", sans(15), INK, 5)

    for i, line in enumerate(["Celebrating the beauty of Pakistani fashion",
                              "with timeless designs, premium fabrics and",
                              "flawless, hand-finished details."]):
        draw.text((LX, 470 + i * 31), line, font=sans(18), fill=(91, 79, 73))

    # buttons
    draw.rectangle([LX, 588, LX + 260, 644], fill=PLUM)
    tracked(draw, ((LX + LX + 260) / 2, 608), "SHOP THE COLLECTION", sans(13), CREAM, 3, center=True)
    draw.rectangle([LX + 276, 588, LX + 276 + 150, 644], outline=PLUM, width=1)
    tracked(draw, (LX + 276 + 75, 608), "OUR STORY", sans(13), PLUM, 3, center=True)

    # meta
    mx = LX
    for i, p in enumerate(["PREMIUM FABRICS", "HAND-FINISHED", "MADE IN PAKISTAN"]):
        w = tracked(draw, (mx, 690), p, sans(11), MUTED, 3)
        if i < 2:
            draw.text((mx + w + 12, 688), "✦", font=sans(10), fill=GOLD)
        mx += w + 40

    # hero arch panel
    HX0, HY0, HX1, HY1 = 716, 196, 1112, 772
    pim, pmask = panel((HX0, HY0, HX1, HY1), [(238, 223, 216), (207, 166, 170), (95, 52, 70)], radius=180)
    base.paste(pim, (HX0, HY0), pmask)
    draw = ImageDraw.Draw(base, "RGBA")
    pw = HX1 - HX0
    # arch outline + sprigs + monogram + label
    over = Image.new("RGBA", (pw, HY1 - HY0), (0, 0, 0, 0))
    od = ImageDraw.Draw(over)
    od.rounded_rectangle([26, 60, pw - 26, HY1 - HY0 - 30], radius=int((pw - 52) / 2), outline=GOLD + (150,), width=2)
    od.line([(26, 60 + (pw - 52) / 2), (26, HY1 - HY0 - 30)], fill=GOLD + (150,), width=2)
    od.line([(pw - 26, 60 + (pw - 52) / 2), (pw - 26, HY1 - HY0 - 30)], fill=GOLD + (150,), width=2)
    sprig(od, 70, 150, 1.2, 0, PLUM, 130)
    sprig(od, pw - 70, HY1 - HY0 - 120, 1.2, 180, PLUM, 130)
    od.text((pw / 2, (HY1 - HY0) / 2 - 30), "C", font=serif(330), fill=(255, 255, 255, 30), anchor="mm")
    base.paste(over, (HX0, HY0), over)
    draw = ImageDraw.Draw(base)
    cx = (HX0 + HX1) / 2
    draw.text((cx, 470), "Chelé", font=serif(52), fill=(251, 247, 240), anchor="mm")
    tracked(draw, (cx, 500), "SIGNATURE EDIT", sans(14), GOLD_LT, 6, center=True)
    draw.line([(cx - 36, 536), (cx + 36, 536)], fill=GOLD_LT, width=1)

    # since badge (bottom-left of panel)
    bcx, bcy, br = HX0 + 14, HY1 - 150, 56
    draw.ellipse([bcx - br, bcy - br, bcx + br, bcy + br], fill=CREAM, outline=GOLD_LT, width=2)
    draw.text((bcx, bcy - 15), "since", font=serif_it(24), fill=PLUM, anchor="mm")
    draw.text((bcx, bcy + 15), "2024", font=serif(28), fill=GOLD_DEEP, anchor="mm")

    # rotating seal (top-right of panel)
    scx, scy, sr = HX1 - 8, HY0 + 4, 56
    draw.ellipse([scx - sr, scy - sr, scx + sr, scy + sr], fill=CREAM, outline=GOLD + (0,), width=0)
    draw.ellipse([scx - sr + 8, scy - sr + 8, scx + sr - 8, scy + sr - 8], outline=GOLD, width=1)
    seal_font = sans(9)
    seal_text = "CHELÉ · LADIES & GIRLS · EST 2024 · "
    for i, ch in enumerate(seal_text):
        ang = -90 + i * (360 / len(seal_text))
        rad = math.radians(ang)
        rr = sr - 16
        ch_img = Image.new("RGBA", (16, 16), (0, 0, 0, 0))
        ImageDraw.Draw(ch_img).text((8, 8), ch, font=seal_font, fill=PLUM, anchor="mm")
        ch_img = ch_img.rotate(-ang - 90, expand=True)
        px = int(scx + rr * math.cos(rad) - ch_img.width / 2)
        py = int(scy + rr * math.sin(rad) - ch_img.height / 2)
        base.paste(ch_img, (px, py), ch_img)
    draw = ImageDraw.Draw(base)
    draw.ellipse([scx - 3, scy - 3, scx + 3, scy + 3], fill=GOLD)

    # bottom product cards
    cards = [
        ("GULRANG LAWN", "Rs 6,990", [(243, 233, 219), (231, 205, 203), (176, 122, 134)]),
        ("NOOR ORGANZA", "Rs 18,500", [(238, 223, 216), (207, 166, 170), (95, 52, 70)]),
        ("MEHER KURTA", "Rs 8,490", [(243, 234, 220), (220, 197, 152), (150, 116, 70)]),
        ("AIRA FROCK", "Rs 4,290", [(233, 227, 210), (194, 203, 180), (118, 131, 106)]),
    ]
    n = len(cards)
    gap = 24
    cw = (W - 184 - gap * (n - 1)) // n
    ch = 150
    cx0 = 92
    for name, price, tones in cards:
        c = card((cw, ch), tones, name, price)
        base.paste(c, (cx0, 782))
        cx0 += cw + gap

    base.save(OUT, "PNG")
    print("Wrote", os.path.normpath(OUT), base.size)


if __name__ == "__main__":
    main()
