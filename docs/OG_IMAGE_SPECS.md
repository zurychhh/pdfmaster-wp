# Open Graph Image Specifications for PDFSpark

## Image Requirements

### Dimensions
- **Width:** 1200px
- **Height:** 630px
- **Aspect Ratio:** 1.91:1 (Facebook/Twitter/LinkedIn standard)

### File Specifications
- **Format:** JPG (best compatibility) or PNG
- **File Size:** <200KB (for fast loading)
- **File Name:** `og-image.jpg`
- **Location:** `/wp-content/themes/pdfmaster-theme/assets/images/og-image.jpg`

## Design Guidelines

### Brand Elements
- **Logo:** PDFSpark logo (centered or top-left)
- **Colors:** Use brand blue gradient (#2563EB → #1E3A8A)
- **Typography:** Clear, readable font (min 48px for main text)

### Content to Include
1. **Main Headline:** "PDFSpark" or "Professional PDF Tools"
2. **Tagline:** "$1.99 Per Action - No Subscriptions"
3. **Visual Elements:** PDF icon, tool icons (compress, merge, split, convert)
4. **Trust Signals:** "Bank-Level Encryption" or "2M+ Users Monthly"

### Design Template

```
┌────────────────────────────────────────────┐
│  [PDFSpark Logo]         [PDF Icon]        │
│                                             │
│        Professional PDF Tools               │
│     Compress • Merge • Split • Convert      │
│                                             │
│          $1.99 Per Action                   │
│         No Subscriptions Required           │
│                                             │
│  [✓ Bank-Level Security]  [✓ 2M+ Users]    │
└────────────────────────────────────────────┘
```

## Design Options

### Option 1: Canva Template (Easiest)
1. Go to https://www.canva.com/
2. Create design → Custom size → 1200 × 630 px
3. Use template: "Social Media Header" or start blank
4. Add text and graphics
5. Download as JPG (quality: 85%)

### Option 2: Figma (Professional)
1. Create frame: 1200 × 630 px
2. Use brand colors and typography
3. Export as JPG (2x quality)
4. Optimize with TinyJPG.com if >200KB

### Option 3: Photoshop/GIMP
1. New document: 1200 × 630 px, 72 DPI
2. RGB color mode
3. Design with brand assets
4. Save for web: JPG, 80-85% quality

## Quick Design Tips

### Typography
- **Headline:** 72-96px, bold, centered
- **Subhead:** 36-48px, regular weight
- **Body:** 24-32px minimum

### Color Contrast
- White text on dark blue background (WCAG AAA)
- Avoid busy backgrounds (affects text readability)

### Safe Zones
- Keep important content 100px from edges
- Facebook/LinkedIn may crop differently on mobile

## Testing Checklist

After creating the image:

### File Check
- [ ] Dimensions: 1200 × 630 px exactly
- [ ] File size: <200KB
- [ ] Format: JPG or PNG
- [ ] File name: `og-image.jpg`

### Visual Check
- [ ] Logo clear and recognizable
- [ ] Text readable at small sizes
- [ ] Brand colors used correctly
- [ ] No pixelation or artifacts

### Upload
- [ ] Upload to `/wp-content/themes/pdfmaster-theme/assets/images/`
- [ ] Verify URL: `https://www.pdfspark.app/wp-content/themes/pdfmaster-theme/assets/images/og-image.jpg`

### Testing Tools
1. **Facebook Debugger:** https://developers.facebook.com/tools/debug/
   - Enter: `https://www.pdfspark.app`
   - Click "Scrape Again" to clear cache
   - Verify image displays correctly

2. **Twitter Card Validator:** https://cards-dev.twitter.com/validator
   - Enter: `https://www.pdfspark.app`
   - Verify "Summary Card with Large Image" shows
   - Check image preview

3. **LinkedIn Post Inspector:** https://www.linkedin.com/post-inspector/
   - Enter: `https://www.pdfspark.app`
   - Verify image and text preview

4. **WhatsApp Test:**
   - Send URL to yourself via WhatsApp
   - Check link preview displays image

## Fallback Image

If you need a quick placeholder:

```
Simple text-based design:
- Dark blue background (#1E3A8A)
- White text: "PDFSpark"
- Subtext: "Professional PDF Tools"
- Price: "$1.99 Per Action"
```

## Example Code for Quick HTML Canvas Generation

```html
<!-- Save as og-image-generator.html and open in browser -->
<!DOCTYPE html>
<html>
<head>
    <title>OG Image Generator</title>
</head>
<body>
    <canvas id="canvas" width="1200" height="630"></canvas>
    <button onclick="download()">Download Image</button>

    <script>
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        // Background gradient
        const gradient = ctx.createLinearGradient(0, 0, 0, 630);
        gradient.addColorStop(0, '#2563EB');
        gradient.addColorStop(1, '#1E3A8A');
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, 1200, 630);

        // Text
        ctx.fillStyle = '#FFFFFF';
        ctx.font = 'bold 96px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('PDFSpark', 600, 220);

        ctx.font = '48px Arial';
        ctx.fillText('Professional PDF Tools', 600, 300);

        ctx.font = 'bold 64px Arial';
        ctx.fillText('$1.99 Per Action', 600, 420);

        ctx.font = '36px Arial';
        ctx.fillText('No Subscriptions Required', 600, 480);

        function download() {
            const link = document.createElement('a');
            link.download = 'og-image.jpg';
            link.href = canvas.toDataURL('image/jpeg', 0.85);
            link.click();
        }
    </script>
</body>
</html>
```

## Current Implementation Status

✅ **Code Ready:** OG meta tags implemented in `inc/seo-metadata.php`
⏳ **Pending:** Image file needs to be created and uploaded

## Next Steps

1. Design image (15-30 min)
2. Upload to `/wp-content/themes/pdfmaster-theme/assets/images/og-image.jpg`
3. Test with Facebook Debugger
4. Deploy to production
5. Share on social media to verify

---

**Priority:** High (affects SEO and social sharing)
**Estimated Time:** 30 minutes (design) + 10 minutes (upload & test)
