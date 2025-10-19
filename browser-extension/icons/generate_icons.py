"""
BookShare Extension Icon Generator
Generates PNG icons from base64 data
"""

import base64
from pathlib import Path

# Simple placeholder icons (solid colors with text)
# These are minimal PNGs that will work as placeholders

def create_placeholder_icon(size, output_path):
    """Create a simple placeholder PNG icon"""
    
    # Create a minimal PNG with ImageMagick command
    import subprocess
    
    try:
        # Try using ImageMagick if available
        cmd = [
            'magick',
            '-size', f'{size}x{size}',
            'xc:#667eea',
            '-font', 'Arial',
            '-pointsize', str(size // 2),
            '-fill', 'white',
            '-gravity', 'center',
            '-annotate', '+0+0', 'B',
            output_path
        ]
        subprocess.run(cmd, check=True)
        print(f"✓ Created {output_path} using ImageMagick")
        return True
    except (subprocess.CalledProcessError, FileNotFoundError):
        print(f"✗ ImageMagick not found. Please use the HTML generator instead.")
        return False

def main():
    print("BookShare Icon Generator")
    print("=" * 50)
    print()
    
    # Icon sizes needed
    sizes = [16, 48, 128]
    icons_dir = Path(__file__).parent
    
    print(f"Icons directory: {icons_dir}")
    print()
    
    success_count = 0
    for size in sizes:
        output_path = icons_dir / f"icon{size}.png"
        if create_placeholder_icon(size, str(output_path)):
            success_count += 1
    
    print()
    if success_count == 0:
        print("⚠️  Automatic generation failed.")
        print()
        print("Please use one of these methods instead:")
        print()
        print("Method 1 (EASIEST): Open icon-generator.html in your browser")
        print(f"  File: {icons_dir / 'icon-generator.html'}")
        print()
        print("Method 2: Use online converter")
        print("  1. Go to: https://cloudconvert.com/svg-to-png")
        print(f"  2. Upload: {icons_dir / 'icon128.svg'}")
        print("  3. Convert to 128x128, 48x48, 16x16")
        print("  4. Save as icon128.png, icon48.png, icon16.png")
        print()
    else:
        print(f"✓ Successfully created {success_count}/3 icons!")

if __name__ == '__main__':
    main()
