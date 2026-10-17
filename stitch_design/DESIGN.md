---
name: TG2D Architectural Precision
colors:
  surface: '#f7fafc'
  surface-dim: '#d7dadc'
  surface-bright: '#f7fafc'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f1f4f6'
  surface-container: '#ebeef0'
  surface-container-high: '#e5e9eb'
  surface-container-highest: '#e0e3e5'
  on-surface: '#181c1e'
  on-surface-variant: '#3c494c'
  inverse-surface: '#2d3133'
  inverse-on-surface: '#eef1f3'
  outline: '#6c797d'
  outline-variant: '#bbc9cd'
  surface-tint: '#006878'
  primary: '#006878'
  on-primary: '#ffffff'
  primary-container: '#08bcd8'
  on-primary-container: '#004752'
  inverse-primary: '#46d7f4'
  secondary: '#50606d'
  on-secondary: '#ffffff'
  secondary-container: '#d0e2f1'
  on-secondary-container: '#546572'
  tertiary: '#52606a'
  on-tertiary: '#ffffff'
  tertiary-container: '#9faeb9'
  on-tertiary-container: '#34424b'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#a8edff'
  primary-fixed-dim: '#46d7f4'
  on-primary-fixed: '#001f25'
  on-primary-fixed-variant: '#004e5b'
  secondary-fixed: '#d3e5f4'
  secondary-fixed-dim: '#b7c9d8'
  on-secondary-fixed: '#0c1d28'
  on-secondary-fixed-variant: '#384955'
  tertiary-fixed: '#d6e4f0'
  tertiary-fixed-dim: '#bac8d4'
  on-tertiary-fixed: '#0f1d25'
  on-tertiary-fixed-variant: '#3b4952'
  background: '#f7fafc'
  on-background: '#181c1e'
  surface-variant: '#e0e3e5'
typography:
  display-xl:
    fontFamily: Manrope
    fontSize: 56px
    fontWeight: '700'
    lineHeight: 64px
    letterSpacing: -0.02em
  display-xl-mobile:
    fontFamily: Manrope
    fontSize: 36px
    fontWeight: '700'
    lineHeight: 44px
    letterSpacing: -0.01em
  headline-lg:
    fontFamily: Manrope
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.015em
  headline-md:
    fontFamily: Manrope
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.01em
  headline-sm:
    fontFamily: Manrope
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  title-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '600'
    lineHeight: 24px
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Inter
    fontSize: 15px
    fontWeight: '400'
    lineHeight: 24px
  body-sm:
    fontFamily: Inter
    fontSize: 13px
    fontWeight: '400'
    lineHeight: 20px
  label-md:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.05em
  label-mono:
    fontFamily: Inter
    fontSize: 11px
    fontWeight: '500'
    lineHeight: 14px
    letterSpacing: 0.08em
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  gutter: 1.5rem
  gutter-mobile: 1rem
  margin: 3rem
  margin-mobile: 1.25rem
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 1rem
  space-lg: 1.5rem
  space-xl: 2.5rem
---

## Brand & Style

This design system expresses the discipline, geometric rigor, and technical authority of sustainable structural engineering and general contracting (Travaux Généraux de Développement Durable). The visual language bridges heavy civil engineering with forward-looking environmental sustainability.

### Core Attributes
- **Architectural Rigor:** Form follows blueprint logic. Elements align along clear grid axes, using measured proportions and ruled hairline dividers reminiscent of technical CAD drawings and structural elevations.
- **Sustainable Pragmatism:** Clean surfaces, uncluttered informational hierarchies, and crisp contrast convey modern eco-responsible construction without rustic or folksy tropes.
- **Engineering Clarity:** High density of actionable data, lucid typographic scales, and deterministic spatial structure inspire confidence among institutional clients, urban developers, and technical partners.

### Design Movement
**Architectural Structuralism mixed with Modern Technical Corporate:** The aesthetic relies on ultra-subtle hairline borders (`#DFE7EB`), flat planar surfaces, disciplined geometric geometry, and selective high-visibility electric cyan accents (`#08BCD8`) derived from construction schematics and digital BIM models.

## Colors

The palette balances technical charcoal depths with bright, luminous structural planes and vivid cyan highlight vectors.

### Primary Roles
- **Brand Cyan (`#08BCD8`):** Serves strictly as the dynamic focal accent. Used for primary CTAs, active states, precision measurement markers, progress indicators, and focal technical lines.
- **Deep Charcoal (`#12232E`):** Used for inverted high-impact sections, footers, blueprint cards, and commanding technical frames.
- **Dark Steel (`#17232D`):** Primary text, header content, and primary iconographic representations, ensuring WCAG AAA legibility against light backgrounds.
- **Secondary Slate (`#5D6B75`):** Subtext, metric units, technical specs, form labels, and inactive icon states.
- **Fine Structural Border (`#DFE7EB`):** Subtle technical rule lines, data tables, structural framing, and panel division.
- **Architectural Gray Canvas (`#F5F8FA`):** The primary page canvas background, reducing eye fatigue compared to pure white while retaining crisp clinical clarity.
- **Crisp White (`#FFFFFF`):** High-elevation surface cards, modals, and input fields.

## Typography

The pairing combines the geometric, architectural rhythm of **Manrope** for display elements with the neutral precision of **Inter** for dense interface and specification copy.

- **Headlines (Manrope):** Clean modern sans-serif with geometric roundings and rigid verticals. Titles emphasize structural solidity. Display weights use 600 or 700 with slight negative letter tracking for cohesive density.
- **Body & Controls (Inter):** Highly legible neutral grotesque engineered for screens. Inter delivers clear distinction between numerals and metric symbols (m², kN, kW/h, kg/CO₂).
- **Technical Overlines & Micro-Labels:** Formatted in uppercase `label-md` or `label-mono` with wide tracking (`0.05em` to `0.08em`) to mimic engineering blueprints and schematics.

## Layout & Spacing

Layouts adhere to an explicit, modular 12-column grid system reminiscent of CAD drawings and construction plans. 

- **Grid Discipline:** Desktop uses 12 columns with `1.5rem` (24px) gutters and `3rem` (48px) safe outer margins. Tablet collapses to 8 columns with 16px gutters; mobile switches to 4 columns with 16px gutters and 20px outer margins.
- **Rhythm & Alignments:** Components align strictly to grid lines. Dividing lines must span full column widths or component boundaries with no floating disconnected separators.
- **Information Density:** Spacing favors compact, structured efficiency over whimsical floating space. Complex project data, timeline schedules, and sustainability metrics sit within modular panels bounded by hairline borders.

## Elevation & Depth

To sustain an architectural blueprint aesthetic, elevation is communicated through **tonal planar layering and crisp structural outlines** rather than diffuse drop shadows.

- **Zero Exaggerated Shadows:** Avoid heavy blurs, colored glows, and floating 3D shadows. Depth must feel planar and orthogonal.
- **Surface Layering:**
  - Base: `#F5F8FA` (Canvas)
  - Layer 1: `#FFFFFF` (Panels, Cards, Form modules) bordered with 1px solid `#DFE7EB`.
  - Contrast Layer: `#12232E` (Technical command blocks, engineering KPI callouts, inverted project highlights).
- **Subtle Ambient Depth:** When an element must hover (e.g., dropdowns, floating toolbars, elevated modals), use an ultra-crisp, restrained shadow: `0 4px 12px rgba(18, 35, 46, 0.06), 0 1px 2px rgba(18, 35, 46, 0.04)` combined with a 1px `#DFE7EB` border.
- **Cyan Accent Lines:** Active states and focus hierarchies are reinforced with 2px primary cyan (`#08BCD8`) structural edge indicators on the left or top border of an active container.

## Shapes

The shape system is strictly architectural: crisp, measured, and stable.

- **Corner Radii:** Set to Soft (`roundedness: 1`), with standard component corners capped at `0.25rem` (4px). Containers, modals, and large cards use `0.375rem` (6px) maximum.
- **No Pill Shapes:** Full circular capsules, rounded pill buttons, and soft floating bubbles are explicitly prohibited. Buttons, tags, and chips maintain clean rectangular discipline with slight corner easing to avoid visual harshness while preserving engineering rigor.
- **Line Accents:** Structural dividers, progress gauges, and visual framing lines remain crisp 1px or 2px flat rectangles.

## Components

### Buttons
- **Primary:** Solid `#08BCD8` background, `#12232E` high-contrast bold text, 4px border radius. Padding: `10px 20px`. Hover: darken cyan to `#06A7C0`. Active: `#0593A9`.
- **Secondary / Outline:** Crisp `#FFFFFF` surface, 1px solid `#DFE7EB` border, `#17232D` text. Hover: border shifts to `#08BCD8`, text to `#08BCD8`.
- **Dark Structural:** Solid `#12232E` background, `#FFFFFF` text, subtle 1px border `#17232D`. Hover: `#17232D` with top cyan border accent.

### Chips & Badges
- **Status Badges:** Rectangular with 3px radius, `4px 10px` padding, `11px` uppercase tracking. Background `#F5F8FA` with 1px border `#DFE7EB`.
- **Sustainability / Active Metric Chip:** Subtle cyan tint (`rgba(8, 188, 216, 0.08)`), text `#08BCD8`, 1px solid `rgba(8, 188, 216, 0.3)`.

### Cards & Modular Containers
- Pure `#FFFFFF` surface enclosed in a 1px solid `#DFE7EB` perimeter. Zero heavy shadow.
- Header zones within cards are demarcated by a 1px horizontal rule (`#DFE7EB`) running edge-to-edge.
- In dark mode or inverted sections, cards use `#12232E` with a 1px `#233544` border.

### Input Fields & Controls
- **Inputs:** Crisp white fill, 1px `#DFE7EB` border, 4px radius, `12px 14px` padding. Typography in Inter 14px `#17232D`. Placeholder `#5D6B75`. Focus state: 1px solid `#08BCD8` with an inner or subtle glow `0 0 0 1px #08BCD8`.
- **Checkboxes & Radios:** Sharp square (checkbox: 2px radius) and circle (radio) with 1.5px `#DFE7EB` border. Checked state fills with `#08BCD8` containing a white check mark.

### Lists & Technical Data Tables
- Flush rows with 1px bottom border `#DFE7EB`. Hover rows shift subtly to `#F5F8FA`.
- Header cells: `11px` uppercase Inter, `#5D6B75`, letter-spacing `0.06em`, accompanied by subtle sort indicator arrows.

### Technical Blueprint Callouts
- Specialized containers featuring a 2px vertical cyan left-border stripe (`#08BCD8`), light gray surface (`#F5F8FA`), and monospaced or structured metric formatting for engineering certifications, carbon footprints, and structural load metrics.