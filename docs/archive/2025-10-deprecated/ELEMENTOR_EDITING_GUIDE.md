# Elementor Editing Guide — PDFMaster

Goal: Enable you to edit any visible element on the site via Elementor UI in under 2 minutes, without touching code.

Links
- Site: http://localhost:10003/
- WP Admin: http://localhost:10003/wp-admin

Quick Start
- Pages → Home → Edit with Elementor
- Use Navigator (left panel icon or Cmd/Ctrl+I) to jump between labeled sections
- After edits: click Update, hard refresh (Cmd+Shift+R)

Conventions
- Sections and widgets are labeled clearly in Navigator (e.g., "Hero Section", "Primary CTA – Start Converting")
- Custom classes use prefix .pdfm-* (documented at the end)
- For badges/tools use native widgets: Icon List, Icon Box, Price Table

Editing Hero Section
1) Headline
   - Navigator: Hero Section → Hero Headline (Heading widget)
   - Edit text inline; Style tab to adjust color/typography
2) Subheadline
   - Navigator: Hero Section → Hero Subheadline (Text Editor)
3) CTA Buttons
   - Navigator: Hero Section → CTA Buttons Row
   - Primary: "Start Converting – $0.99" (Button)
   - Secondary: "See All Tools & Pricing" (Button)
   - To swap styles: Style → Typography/Colors; spacing under Advanced
4) Trust Badges Row
   - Navigator: Hero Section → Trust Badges Row (Icon List)
   - Add/remove items with +/bin, change icons via Icon picker
   - Horizontal layout: Content → Layout → Inline
5) Tool Icons Row
   - Navigator: Hero Section → Tool Icons Row (3× Icon Box)
   - Each item: icon, title, description – all editable; duplicate to add 4th tool

Editing Value Proposition Cards (4-up)
- Navigator: Value Props Section → 4 columns (Icon Box widgets)
- Edit icon/title/text per card; duplicate/delete as needed

Editing Pricing Section
- If using native Price Table widgets:
  - Navigator: Pricing Section → Price Table
  - Content tab: set price, features, CTA text
- If using HTML table (fallback):
  - Navigator: Pricing Section → Pricing Table (HTML)
  - Edit cell text directly; keep .pdfm-pricing-table class

Editing Trust & Security
- Navigator: Trust Section → 3 columns (Icon Box widgets)
- Edit icons/titles/text; testimonial is a Text Editor block

Editing FAQ
- Navigator: FAQ Section → Accordion widget (or Text Editor)
- Add items, edit questions/answers

Footer
- Navigator: Footer Section → Columns → Text widgets
- Edit links/text; spacing via Advanced tab

Common Tasks
- Change headline: Hero Headline → edit inline → Update
- Add trust badge: Trust Badges Row → + Add Item → set icon/text → Update
- Add tool: Tool Icons Row → Right-click an Icon Box → Duplicate → Edit
- Update price: Pricing Section → (Price Table) → set price/features → Update

Common Pitfalls
- Cache: If styles don’t apply, Elementor → Tools → Regenerate CSS & Data, then hard refresh
- Mobile: Always check responsive tabs (Desktop/Tablet/Mobile) per widget/section

Custom CSS Classes Reference
| Class | Purpose | Where |
|------|---------|-------|
| .pdfm-hero-badges | Layout/styling for trust badges | Hero → Trust Badges Row |
| .pdfm-hero-tools | Layout for tool icon boxes | Hero → Tool Icons Row |
| .pdfm-pricing-table | Styling for pricing table (HTML fallback) | Pricing Section |
| .pdfm-trust-section | Styling for trust/security section | Trust Section |

Notes
- If a widget shows as “HTML” and you prefer full UI control, convert it to native widget (Icon List, Icon Box, Price Table). Follow the Structure Map for recommended types.
- If anything looks off after edit, refresh with Cmd+Shift+R.
