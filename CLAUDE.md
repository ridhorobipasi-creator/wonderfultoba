# Antigravity Rules for sujailaketoba.com

Behavioral guidelines and reference rules derived from Karpathy, Matt Pocock, and Awesome Agent Skills.

## 1. Project Commands
- **Dev Server**: `npm run dev`
- **Build Assets**: `npm run build`
- **Laravel Local Server**: `php artisan serve`
- **Database Migrations**: `php artisan migrate`
- **Clear Caching**: `php artisan config:clear`, `php artisan cache:clear`, `php artisan view:clear`
- **Testing**: `php artisan test`

## 2. Core Behavioral Guidelines (Karpathy Rules)

### Think Before Coding
- **Don't assume, don't hide confusion, surface tradeoffs.**
- Before implementing, state assumptions explicitly. If uncertain or multiple interpretations exist, ask the user.
- If a simpler approach exists, suggest it. Push back when warranted.
- If something is unclear, stop and name the confusion.

### Simplicity First
- **Write the minimum code that solves the problem. Nothing speculative.**
- No features beyond what was asked. No abstractions for single-use code.
- No "flexibility" or "configurability" that wasn't requested.
- If 200 lines can be written in 50, rewrite it.

### Surgical Changes
- **Touch only what you must. Clean up only your own mess.**
- Do not refactor adjacent code, comments, or formatting unless it is broken or explicitly requested.
- Match the existing style and patterns exactly.

## 3. Communication and Alignment (Pocock Rules)

### Interactive Alignment (Grilling)
- For significant changes, run a structured alignment session (grill-me process) by asking clarifying questions to understand user requirements before writing code.
- Align on design style, functionality, and constraints beforehand.

### Ubiquitous Language & Context
- Speak using the project's domain language.
- Document domain concepts and jargon clearly to keep communication concise.

## 4. Technology Stack & Quality Standards
- **Backend**: Laravel 11 (MVC architecture, Blade templates, Webpack/Vite asset compilation).
- **Frontend**: Tailwind CSS, Alpine.js for interactive client-side logic.
- **Design Aesthetics**: Modern, premium, minimalist web design. Use rich aesthetics, sleek typography, harmonious color palettes, subtle transitions/micro-animations. Responsive layout is mandatory.
- **SEO & Performance**: Use semantic HTML, unique descriptive IDs, appropriate meta tags, and optimize for fast page loads.
