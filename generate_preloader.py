import os
import math
from PIL import Image, ImageDraw, ImageFont, ImageFilter

def create_preloader_gif():
    output_path = 'public/images/preloader.gif'
    logo_path = 'public/images/logoponorogo.png'
    
    if not os.path.exists(logo_path):
        raise FileNotFoundError(f"Logo not found at {logo_path}")
        
    orig_logo = Image.open(logo_path).convert('RGBA')
    
    # Base dimensions
    width, height = 360, 320
    total_frames = 30  # 30 frames for ultra-smooth animation
    duration_per_frame = 55  # ~1.65 seconds per loop
    
    # Fonts
    font_title_path = 'C:/Windows/Fonts/segoeuib.ttf'
    font_sub_path = 'C:/Windows/Fonts/segoeui.ttf'
    
    if not os.path.exists(font_title_path):
        font_title_path = 'C:/Windows/Fonts/arialbd.ttf'
    if not os.path.exists(font_sub_path):
        font_sub_path = 'C:/Windows/Fonts/arial.ttf'
        
    font_title = ImageFont.truetype(font_title_path, 22)
    font_sub = ImageFont.truetype(font_sub_path, 13)
    
    # Target logo base size
    base_logo_w = 95
    base_logo_h = int(base_logo_w * (orig_logo.height / orig_logo.width))  # approx 127px
    
    frames = []
    
    for i in range(total_frames):
        # Create RGB image with pure white background for crisp anti-aliasing
        frame = Image.new('RGB', (width, height), (255, 255, 255))
        draw = ImageDraw.Draw(frame)
        
        # Calculate smooth harmonic pulsation
        progress = i / total_frames
        angle = progress * 2 * math.pi
        
        # Scale breathing & floating
        scale = 1.0 + 0.035 * math.sin(angle)
        y_float = -3.5 * math.sin(angle)
        
        curr_w = int(base_logo_w * scale)
        curr_h = int(base_logo_h * scale)
        
        # Resize logo smoothly with Lanczos
        resized_logo = orig_logo.resize((curr_w, curr_h), Image.Resampling.LANCZOS)
        
        # Paste logo centered
        logo_x = int((width - curr_w) / 2)
        logo_y = int(32 + (base_logo_h - curr_h) / 2 + y_float)
        
        # Create a beautiful soft blurred shadow underneath logo
        shadow_layer = Image.new('RGBA', (width, height), (0, 0, 0, 0))
        s_draw = ImageDraw.Draw(shadow_layer)
        
        shadow_w = int(curr_w * 0.65)
        shadow_h = 10
        shadow_x0 = (width - shadow_w) // 2
        shadow_y0 = int(32 + base_logo_h + 8 - (y_float * 0.3))
        shadow_opacity = int(38 + 12 * math.sin(angle))
        
        s_draw.ellipse(
            [shadow_x0, shadow_y0, shadow_x0 + shadow_w, shadow_y0 + shadow_h], 
            fill=(15, 23, 42, shadow_opacity)
        )
        shadow_blurred = shadow_layer.filter(ImageFilter.GaussianBlur(3))
        frame.paste(shadow_blurred, (0, 0), shadow_blurred)
        
        # Alpha paste logo
        frame.paste(resized_logo, (logo_x, logo_y), resized_logo)
        
        # Title text: "Kecamatan Mlarak"
        title_text = "Kecamatan Mlarak"
        bbox_title = draw.textbbox((0, 0), title_text, font=font_title)
        title_w = bbox_title[2] - bbox_title[0]
        title_x = (width - title_w) // 2
        title_y = 176
        
        # Deep slate-900 color for modern luxury feel
        draw.text((title_x, title_y), title_text, fill=(15, 23, 42), font=font_title)
        
        # Subtitle: "Mohon tunggu ..." with animated dots
        # Cycle through ., .., ..., ...
        dot_step = int((progress * 4)) % 4
        num_dots = (dot_step + 1)
        sub_text = "Mohon tunggu " + ("." * num_dots)
        
        bbox_sub = draw.textbbox((0, 0), sub_text, font=font_sub)
        sub_w = bbox_sub[2] - bbox_sub[0]
        sub_x = (width - sub_w) // 2
        sub_y = 214
        
        draw.text((sub_x, sub_y), sub_text, fill=(100, 116, 139), font=font_sub)
        
        # Modern animated 3-dot pulse accent (Emerald #059669)
        indicator_y = 250
        dot_spacing = 14
        start_x = (width - (2 * dot_spacing)) // 2
        
        for d in range(3):
            # Phase offset for wave effect
            dot_phase = angle - (d * (math.pi / 2.5))
            dot_wave = (math.sin(dot_phase) + 1) / 2  # 0 to 1
            
            # Dot radius & vertical bounce
            r_dot = 2.5 + (1.2 * dot_wave)
            bounce_y = -3.5 * dot_wave
            
            cx = start_x + (d * dot_spacing)
            cy = indicator_y + bounce_y
            
            # Color interpolates between muted slate and vibrant emerald
            r_c = int(148 - (148 - 5) * dot_wave)
            g_c = int(163 + (150 - 163) * dot_wave)
            b_c = int(184 - (184 - 105) * dot_wave)
            
            draw.ellipse([cx - r_dot, cy - r_dot, cx + r_dot, cy + r_dot], fill=(r_c, g_c, b_c))
            
        frames.append(frame)
        
    # Quantize to high quality palette for GIF
    gif_frames = []
    for f in frames:
        # Quantize to adaptive 256 colors
        q_frame = f.convert('P', palette=Image.Palette.ADAPTIVE, colors=256)
        gif_frames.append(q_frame)
        
    gif_frames[0].save(
        output_path,
        save_all=True,
        append_images=gif_frames[1:],
        optimize=True,
        duration=duration_per_frame,
        loop=0
    )
    
    # Save a preview PNG of frame 15
    frames[15].save('public/images/preloader_preview.png')
    print(f"Generated {output_path} successfully ({len(frames)} frames). Size: {os.path.getsize(output_path)} bytes")

if __name__ == '__main__':
    create_preloader_gif()
