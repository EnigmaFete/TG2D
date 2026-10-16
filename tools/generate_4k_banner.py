#!/usr/bin/env python3
"""
Generate 4K Ultra-HD version (4096 x 1776) of the Pourquoi TG2D official banner.
Upscales background and re-renders typography in vector-quality TrueType fonts.
"""
import cv2
import numpy as np
from PIL import Image, ImageDraw, ImageFont, ImageFilter

SOURCE_PATH = '/Users/mohamedtazi/.gemini/antigravity-ide/brain/06d978d2-1f80-48a0-82a6-7ea28210a496/.user_uploaded/media_1792157585506.png'
OUTPUT_PATH = 'assets/images/pourquoi-tg2d-banner.png'

SCALE = 4
WIDTH = 1024 * SCALE   # 4096
HEIGHT = 444 * SCALE   # 1776

# Load source image
src_img = Image.open(SOURCE_PATH).convert('RGBA')

# Crop the 6 neon icons from the source image before cleaning
icon_crops = {
    'icon1': src_img.crop((558, 116, 630, 186)),   # buildings
    'icon2': src_img.crop((752, 116, 824, 186)),   # residential tower
    'icon3': src_img.crop((558, 204, 630, 274)),   # casablanca map pin
    'icon4': src_img.crop((752, 204, 824, 274)),   # grand casa map
    'icon5': src_img.crop((558, 292, 630, 362)),   # maroc map
    'icon6': src_img.crop((752, 292, 824, 362))    # crane tce
}

# 1. Upscale the base image to 4096x1776 with Lanczos
base_4k = src_img.resize((WIDTH, HEIGHT), Image.Resampling.LANCZOS)

# 2. Inpaint / clear the blurry text regions on the 4K image
# Convert to numpy for smooth gradient inpainting
arr = np.array(base_4k)

# Left text area: x from 440 to 2160, y from 180 to 1600
# Sample background colors above, to the left, and below
# The background is a vertical dark gradient: near top (8, 22, 36), near bottom (9, 14, 20)
for y in range(180, 1580):
    # interpolate background color from left column x=400 and right boundary x=2180
    c_left = arr[y, 420, :3].astype(float)
    c_right = arr[y, 2200, :3].astype(float)
    for x in range(440, 2180):
        t = (x - 440) / (2180 - 440)
        arr[y, x, :3] = (c_left * (1 - t) + c_right * t).astype(np.uint8)

# Right card text areas (inside the card):
# Card boundaries at 4K: x in [2200, 3800], y in [350, 1540]
# Clean text in Col 1 (x: 2560 to 3040) and Col 2 (x: 3340 to 3760)
card_bg_col1 = arr[520, 2600, :3].astype(float)
card_bg_col2 = arr[520, 3400, :3].astype(float)

# Header of card: '— TG2D EN QUELQUES CHIFFRES' at y: 400 to 480
for y in range(410, 480):
    for x in range(2260, 3100):
        arr[y, x, :3] = arr[y, 2240, :3]

# Row 1 text (y: 500 to 740)
for y in range(500, 740):
    for x in range(2560, 3040):
        arr[y, x, :3] = card_bg_col1.astype(np.uint8)
    for x in range(3340, 3760):
        arr[y, x, :3] = card_bg_col2.astype(np.uint8)

# Row 2 text (y: 840 to 1100)
for y in range(840, 1100):
    for x in range(2560, 3040):
        arr[y, x, :3] = card_bg_col1.astype(np.uint8)
    for x in range(3340, 3760):
        arr[y, x, :3] = card_bg_col2.astype(np.uint8)

# Row 3 text (y: 1190 to 1480)
for y in range(1190, 1480):
    for x in range(2560, 3040):
        arr[y, x, :3] = card_bg_col1.astype(np.uint8)
    for x in range(3340, 3760):
        arr[y, x, :3] = card_bg_col2.astype(np.uint8)

# Convert back to PIL Image
canvas = Image.fromarray(arr)

# Apply gentle blur only to inpainted zones to blend seamlessly
canvas = canvas.filter(ImageFilter.SMOOTH_MORE)

# 3. Paste sharpened, upscaled neon icons back into exact positions
# Positions at 4K:
icon_positions = {
    'icon1': (558 * SCALE, 116 * SCALE),
    'icon2': (752 * SCALE, 116 * SCALE),
    'icon3': (558 * SCALE, 204 * SCALE),
    'icon4': (752 * SCALE, 204 * SCALE),
    'icon5': (558 * SCALE, 292 * SCALE),
    'icon6': (752 * SCALE, 292 * SCALE)
}

for name, pos in icon_positions.items():
    ic = icon_crops[name].resize((ic_w := icon_crops[name].width * SCALE, ic_h := icon_crops[name].height * SCALE), Image.Resampling.LANCZOS)
    # Apply unsharp mask to make neon lines razor-sharp
    ic_sharp = ic.filter(ImageFilter.UnsharpMask(radius=2, percent=150, threshold=3))
    canvas.paste(ic_sharp, pos)

# 4. Re-draw typography using authentic TrueType fonts
draw = ImageDraw.Draw(canvas)

# Fonts
font_bold_title = ImageFont.truetype('/tmp/PlusJakartaSans-Bold.ttf', 104)
font_bold_h4 = ImageFont.truetype('/tmp/PlusJakartaSans-Bold.ttf', 44)
font_body = ImageFont.truetype('/tmp/PlusJakartaSans-Medium.ttf', 35)
font_tag = ImageFont.truetype('/tmp/PlusJakartaSans-Bold.ttf', 38)
font_num = ImageFont.truetype('/tmp/PlusJakartaSans-Bold.ttf', 46)

# Card Fonts
font_card_tag = ImageFont.truetype('/tmp/PlusJakartaSans-Bold.ttf', 36)
font_stat_huge_cyan = ImageFont.truetype('/tmp/PlusJakartaSans-Bold.ttf', 98)
font_stat_huge_white = ImageFont.truetype('/tmp/PlusJakartaSans-Bold.ttf', 62)
font_stat_city = ImageFont.truetype('/tmp/PlusJakartaSans-Bold.ttf', 68)
font_stat_label = ImageFont.truetype('/tmp/PlusJakartaSans-Medium.ttf', 32)

COLOR_CYAN = (0, 194, 255, 255)       # #00C2FF
COLOR_WHITE = (255, 255, 255, 255)   # #FFFFFF
COLOR_MUTED = (160, 185, 210, 255)   # #A0B9D2 soft readable gray-blue

# --- DRAW LEFT SIDE ---
# Tag: — POURQUOI TG2D ?
draw.line([(468, 230), (510, 230)], fill=COLOR_CYAN, width=5)
draw.text((530, 210), "POURQUOI TG2D ?", fill=COLOR_CYAN, font=font_tag)

# Main Title:
# Un partenaire unique pour vos
# projets de construction
draw.text((468, 290), "Un partenaire unique pour vos", fill=COLOR_WHITE, font=font_bold_title)
draw.text((468, 415), "projets de construction", fill=COLOR_WHITE, font=font_bold_title)

# 5 Numbered points:
points = [
    {
        "num": "01",
        "title": "Une approche tous corps d’état",
        "desc": "La coordination des différents corps de métier permet une approche globale du projet et facilite\nle suivi pour le client."
    },
    {
        "num": "02",
        "title": "Une équipe expérimentée",
        "desc": "Une équipe bénéficiant de plus de 20 années d’expérience dans le développement immobilier,\navec une maîtrise approfondie des enjeux de conception et de réalisation."
    },
    {
        "num": "03",
        "title": "Une expérience régionale concrète",
        "desc": "Une expérience concrète développée à Casablanca et dans le Grand Casablanca, notamment à\nDar Bouazza et Bouskoura."
    },
    {
        "num": "04",
        "title": "Une capacité d’intervention nationale",
        "desc": "TG2D dispose d’une organisation permettant d’étudier et d’accompagner des projets dans\ndifférentes régions du Maroc."
    },
    {
        "num": "05",
        "title": "Une vision orientée client",
        "desc": "Nous cherchons à comprendre les objectifs du client avant de définir l’organisation et les\nmoyens nécessaires à la réalisation du projet."
    }
]

y_start = 636
y_step = 212

for i, pt in enumerate(points):
    cur_y = y_start + i * y_step
    
    # Cyan number
    draw.text((468, cur_y), pt["num"], fill=COLOR_CYAN, font=font_num)
    
    # Glowing cyan dot
    dot_x = 590
    dot_y = cur_y + 24
    draw.ellipse([(dot_x - 7, dot_y - 7), (dot_x + 7, dot_y + 7)], fill=COLOR_CYAN)
    
    # White title
    draw.text((640, cur_y), pt["title"], fill=COLOR_WHITE, font=font_bold_h4)
    
    # Description lines
    lines = pt["desc"].split('\n')
    desc_y = cur_y + 58
    for line in lines:
        draw.text((640, desc_y), line, fill=COLOR_MUTED, font=font_body)
        desc_y += 46


# --- DRAW RIGHT CARD ---
# Card Tag: — TG2D EN QUELQUES CHIFFRES
draw.line([(2295, 442), (2335, 442)], fill=COLOR_CYAN, width=5)
draw.text((2355, 424), "TG2D EN QUELQUES CHIFFRES", fill=COLOR_CYAN, font=font_card_tag)

# Col 1 x-offset: 2560
# Col 2 x-offset: 3350

# Cell 1: +3 ans / d’expérience dans le secteur de la construction
draw.text((2560, 500), "+3", fill=COLOR_CYAN, font=font_stat_huge_cyan)
# Measure "+3" width
t3_bbox = font_stat_huge_cyan.getbbox("+3")
ans1_x = 2560 + (t3_bbox[2] - t3_bbox[0]) + 16
draw.text((ans1_x, 528), "ans", fill=COLOR_WHITE, font=font_stat_huge_white)
draw.text((2560, 620), "d’expérience dans le secteur\nde la construction", fill=COLOR_MUTED, font=font_stat_label)

# Cell 2: +20 ans / d’expérience dans le développement immobilier
draw.text((3350, 500), "+20", fill=COLOR_CYAN, font=font_stat_huge_cyan)
t20_bbox = font_stat_huge_cyan.getbbox("+20")
ans2_x = 3350 + (t20_bbox[2] - t20_bbox[0]) + 16
draw.text((ans2_x, 528), "ans", fill=COLOR_WHITE, font=font_stat_huge_white)
draw.text((3350, 620), "d’expérience dans le\ndéveloppement immobilier", fill=COLOR_MUTED, font=font_stat_label)

# Cell 3: Casablanca / notre implantation historique
draw.text((2560, 850), "Casablanca", fill=COLOR_WHITE, font=font_stat_city)
draw.text((2560, 940), "notre implantation\nhistorique", fill=COLOR_MUTED, font=font_stat_label)
draw.line([(2560, 1045), (2620, 1045)], fill=COLOR_CYAN, width=4)

# Cell 4: Grand Casa / une région majeure de notre expérience
draw.text((3350, 850), "Grand Casa", fill=COLOR_WHITE, font=font_stat_city)
draw.text((3350, 940), "une région majeure de\nnotre expérience", fill=COLOR_MUTED, font=font_stat_label)
draw.line([(3350, 1045), (3410, 1045)], fill=COLOR_CYAN, width=4)

# Cell 5: Maroc / capacité d’intervention à l’échelle nationale
draw.text((2560, 1200), "Maroc", fill=COLOR_WHITE, font=font_stat_city)
draw.text((2560, 1290), "capacité d’intervention\nà l’échelle nationale", fill=COLOR_MUTED, font=font_stat_label)
draw.line([(2560, 1395), (2620, 1395)], fill=COLOR_CYAN, width=4)

# Cell 6: T.C.E. / une approche globale de la construction
draw.text((3350, 1200), "T.C.E.", fill=COLOR_CYAN, font=font_stat_city)
draw.text((3350, 1290), "une approche globale\nde la construction", fill=COLOR_MUTED, font=font_stat_label)
draw.line([(3350, 1395), (3410, 1395)], fill=COLOR_CYAN, width=4)

# Save as optimized PNG
canvas.save(OUTPUT_PATH, optimize=True)
print(f"Successfully generated 4K Ultra-HD banner at {OUTPUT_PATH} ({WIDTH}x{HEIGHT})")
