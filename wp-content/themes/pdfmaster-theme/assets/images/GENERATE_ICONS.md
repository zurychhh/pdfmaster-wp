# Generate Favicon and Apple Touch Icon

## Required Files

1. **favicon.ico** (32x32, 16x16 multi-resolution)
2. **apple-touch-icon.png** (180x180)

## Quick Generation Methods

### Option 1: Online Tool (Fastest)
1. Go to https://realfavicongenerator.net/
2. Upload `pdfspark-logo.svg` (in this directory)
3. Download the generated package
4. Extract `favicon.ico` and `apple-touch-icon.png` to this directory

### Option 2: Using ImageMagick (Command Line)
```bash
# Install ImageMagick first: brew install imagemagick

# Generate apple-touch-icon.png (180x180)
convert pdfspark-logo.svg -resize 180x180 apple-touch-icon.png

# Generate favicon.ico (multi-resolution)
convert pdfspark-logo.svg -resize 32x32 favicon-32.png
convert pdfspark-logo.svg -resize 16x16 favicon-16.png
convert favicon-32.png favicon-16.png favicon.ico
```

### Option 3: Using Figma/Sketch/Illustrator
1. Open `pdfspark-logo.svg` in your design tool
2. Export as PNG at 180x180 → save as `apple-touch-icon.png`
3. Export as ICO at 32x32 → save as `favicon.ico`

## Temporary Workaround

Until proper icons are generated, you can use the SVG as a modern favicon:
- Modern browsers support `<link rel="icon" type="image/svg+xml" href="/path/to/pdfspark-logo.svg">`
- However, ICO is still recommended for broader compatibility

## Open Graph Image

**Required**: `pdfspark-og-image.jpg` (1200x630 pixels)

### Generate OG Image:
1. **Online Tool**: https://www.canva.com/create/open-graph-images/
2. **Figma/Photoshop**: Create 1200x630 JPG with:
   - PDFSpark logo
   - Tagline: "Professional PDF Tools - $0.99 Per Action"
   - Background: Blue gradient (#2563EB)
   - Call to action: "No Subscription Required"
   - Brand colors: Blue #2563EB, Orange #F59E0B

## Current Status

✅ **Production Icons Installed**: All icons replaced with final production assets
✅ **favicon.ico**: 1.0KB (32x32 multi-resolution)
✅ **apple-touch-icon.png**: 1.1KB (180x180)
✅ **pdfspark-og-image.jpg**: 23KB (1200x630 for social sharing)

**Source**: Copied from `/docs/assets-seo/` on 2025-10-30
**Status**: Ready for production deployment
**Priority**: ✅ COMPLETE - No further action needed

## What Happens if Icons Are Missing?

- No errors - browsers will just show default/no icon
- SEO impact: Minimal (not a ranking factor)
- UX impact: Reduced brand recognition in browser tabs/bookmarks
