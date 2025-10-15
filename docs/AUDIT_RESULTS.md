 # Elementor Audit Results — PDFMaster (Home Page)
 
 Date: 2025-10-10
 Auditor: Droid (Factory.ai)
 Scope: Home page top-to-bottom, identify implementation and editability
 
 Summary
 - Most elements are native Elementor widgets (Heading, Text, Button, Icon Box, Price Table)
 - Two HTML widgets detected on page (need conversion or documentation)
 - Trust badges and Tool icons should be maintained as native widgets for full UI control
 
 Widget Inventory (detected in rendered HTML)
 - Icon Box: 10
 - Text Editor: 9
 - Heading: 4
 - Button: 3
 - Price Table: 2
 - Icon List: 2
 - HTML: 2
 
 Table — Element by Element
 
 | Element | Current Implementation | Editable in UI? | Action Needed |
 |--------|------------------------|-----------------|---------------|
 | Hero headline | Heading widget | ✅ Yes | None |
 | Hero subheadline | Text Editor | ✅ Yes | None |
 | CTA buttons (2) | Button widgets | ✅ Yes | Ensure labels in Navigator |
 | Trust badges | Icon List OR Shortcode (environment-dependent) | ✅ Yes as Icon List / ❌ as Shortcode | Ensure Icon List; remove shortcode fallback |
 | Tool icons (3) | Icon Box widgets (at least 1 detected) | ✅ Yes | Ensure all 3 are Icon Box and grouped |
 | Value props (4) | Icon Box widgets | ✅ Yes | None |
 | Pricing table | Price Table (or HTML fallback) | ✅ Yes as Price Table / ⚠️ HTML | Prefer Price Table; if HTML, document class .pdfm-pricing-table |
 | Comparison box | Text Editor | ✅ Yes | None |
 | Trust & Security (3 cols) | Icon Box widgets | ✅ Yes | None |
 | Testimonial | Text Editor | ✅ Yes | None |
 | FAQ | Accordion or Text Editor | ✅ Yes | None |
 | Footer columns | Text Editor | ✅ Yes | Label columns |
 
 Navigator Labeling — Required
 - Rename all sections/widgets to descriptive names (see Structure Map)
 
 CSS Classes / Data Attributes
 - Apply .pdfm-* classes where relevant: .pdfm-hero-badges, .pdfm-hero-tools, .pdfm-pricing-table, .pdfm-trust-section
 - Optionally set data-pdfm-element="..." for quick identification
 
 Conversion Plan (Phased)
 1) Trust Badges → Icon List (Inline layout), label "Trust Badges Row"
 2) Tool Icons → 3× Icon Box in one Inner Section, label items clearly
 3) Pricing Table → Prefer Price Table widgets; if complex features required, keep HTML + document edits
 
 Testing Protocol
 - After each conversion: Update → hard refresh (Cmd+Shift+R) → check desktop/tablet/mobile
 
 Rollback
 - Keep Elementor Export (Tools → Export) of Home page before each conversion batch
 
 Notes
 - If any element resists conversion without losing design parity, keep as HTML and expand documentation instead of forcing conversion.