#!/usr/bin/env python3
"""
Generate high-resolution (2048x1160) official team photo with TG2D branding on safety vests.
Replaces any incorrect or legacy logos on safety vests with the official TG2D cyan vector logo.
"""
import cv2
import numpy as np
from PIL import Image, ImageFilter

def generate():
    # 1. Load original base photo
    src_path = '/Users/mohamedtazi/.gemini/antigravity-ide/brain/06d978d2-1f80-48a0-82a6-7ea28210a496/.user_uploaded/media_1792236263489.jpg'
    base = cv2.imread(src_path)
    if base is None:
        base = cv2.imread('assets/images/tg2d-team-safety-vests.jpg')
    
    h, w, _ = base.shape
    
    # If already 2K, resize down to base 1024x580 for clean inpainting
    if w != 1024:
        base = cv2.resize(base, (1024, 580), interpolation=cv2.INTER_AREA)
        h, w = 580, 1024

    # 2. Inpaint the legacy/incorrect text on left engineer's back vest
    mask = np.zeros((h, w), dtype=np.uint8)
    c = base[405:465, 240:345]
    is_letter = (c[:,:,1] < 155) | (c[:,:,2] < 145) | (c[:,:,0] > 115)
    mask[405:465, 240:345] = cv2.dilate(is_letter.astype(np.uint8), np.ones((5,5), np.uint8), iterations=1) * 255

    # Inpaint middle engineer chest badge
    mid_roi = base[405:440, 408:440]
    gray_mid = cv2.cvtColor(mid_roi, cv2.COLOR_BGR2GRAY)
    mask[405:440, 408:440] = cv2.dilate((gray_mid < 90).astype(np.uint8), np.ones((3,3), np.uint8), iterations=1) * 255

    clean = cv2.inpaint(base, mask, inpaintRadius=5, flags=cv2.INPAINT_TELEA)

    # 3. Upscale clean image to 2048x1160 (Retina 2x resolution)
    SCALE = 2
    w_2k = w * SCALE
    h_2k = h * SCALE
    clean_2k = cv2.resize(clean, (w_2k, h_2k), interpolation=cv2.INTER_LANCZOS4)
    clean_pil = Image.fromarray(cv2.cvtColor(clean_2k, cv2.COLOR_BGR2RGB))

    # 4. Load official logo
    logo = Image.open('assets/images/tg2d-logo.png').convert('RGBA')

    # Prepare logo with subtle dark navy outline for high contrast on fluorescent yellow fabric
    alpha = logo.split()[3]
    outline = alpha.filter(ImageFilter.MaxFilter(7))
    dark_bkg = Image.new('RGBA', logo.size, (10, 24, 42, 220)) # #0a182a
    dark_bkg.putalpha(outline)
    logo_enhanced = Image.alpha_composite(dark_bkg, logo)

    # 5. Composite left engineer back logo (prominent, centered between reflective stripes)
    w_logo = 194
    h_logo = int(w_logo * logo.height / logo.width)
    logo_left = logo_enhanced.resize((w_logo, h_logo), Image.Resampling.LANCZOS)
    logo_left_rot = logo_left.rotate(3.5, expand=True, resample=Image.Resampling.BICUBIC)
    clean_pil.paste(logo_left_rot, (488, 824), logo_left_rot)

    # 6. Composite middle engineer chest logo badge
    w_mid = 70
    h_mid = int(w_mid * logo.height / logo.width)
    logo_mid = logo_enhanced.resize((w_mid, h_mid), Image.Resampling.LANCZOS)
    logo_mid_rot = logo_mid.rotate(2.0, expand=True, resample=Image.Resampling.BICUBIC)
    clean_pil.paste(logo_mid_rot, (818, 826), logo_mid_rot)

    # 7. Composite right engineer chest logo badge
    w_rt = 60
    h_rt = int(w_rt * logo.height / logo.width)
    logo_rt = logo_enhanced.resize((w_rt, h_rt), Image.Resampling.LANCZOS)
    logo_rt_rot = logo_rt.rotate(-2.0, expand=True, resample=Image.Resampling.BICUBIC)
    clean_pil.paste(logo_rt_rot, (1222, 814), logo_rt_rot)

    # Save to both target image paths
    clean_pil.save('assets/images/tg2d-team-safety-vests.jpg', quality=95, optimize=True)
    clean_pil.save('assets/images/chantier-equipe-ingenieurs.jpg', quality=95, optimize=True)
    print(f"Successfully generated official logo image at assets/images/tg2d-team-safety-vests.jpg ({clean_pil.size})")

if __name__ == '__main__':
    generate()
