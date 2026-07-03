<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= htmlspecialchars($metaDescription ?? 'Empowering underprivileged individuals through accessible education, mentorship, and scholarships in STEM fields to foster personal and professional growth.') ?>">
  <title><?= htmlspecialchars($pageTitle ?? 'Prayogbharti Foundation – Empowering Lives Through Education') ?></title>

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">

  <!-- Google Fonts (same as React app) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Nunito:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">

  <!-- 1. CSS Custom Properties / Design Tokens (MUST be loaded FIRST) -->
  <link rel="stylesheet" href="<?= defined('BASE_URL') ? BASE_URL : '' ?>/assets/css/default_shadcn_theme.css">

  <!-- 2. Pre-built production CSS from the React/Vite app (Tailwind v4 + utilities) -->
  <link rel="stylesheet" href="<?= defined('BASE_URL') ? BASE_URL : '' ?>/assets/css/app.css">

  <!-- 3. Supplemental styles: overrides and PHP-only utilities -->
  <style>
    /* Force CSS vars into actual properties for older browser compatibility */
    :root {
      --primary: #1E6B3C;
      --primary-foreground: #ffffff;
      --background: #ffffff;
      --foreground: #1a2e1a;
      --card: #ffffff;
      --card-foreground: #1a2e1a;
      --secondary: #f0f8f2;
      --muted: #e6f2ea;
      --muted-foreground: #5a7a60;
      --accent: #d4edda;
      --border: rgba(30, 107, 60, 0.15);
      --input-background: #f4faf6;
      --ring: #1E6B3C;
      --radius: 0.625rem;
    }

    /* Smooth bounce animation for scroll arrow */
    @keyframes bounce {
      0%, 100% { transform: translateY(0); animation-timing-function: cubic-bezier(0.8,0,1,1); }
      50% { transform: translateY(-25%); animation-timing-function: cubic-bezier(0,0,0.2,1); }
    }
    .animate-bounce { animation: bounce 1s infinite; }

    /* Mobile menu flex direction */
    #mobile-menu { flex-direction: column; }

    /* Group hover scale utility */
    .group:hover .group-hover\:scale-105 { transform: scale(1.05); }
    .group:hover .group-hover\:text-primary { color: var(--primary); }
    .group:hover .group-hover\:bg-primary { background-color: var(--primary); }
    .group:hover .group-hover\:text-white { color: #ffffff; }
  </style>

  <!-- Lucide Icons (CDN – replaces lucide-react) -->
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

  <!-- Open Graph -->
  <meta property="og:title"       content="<?= htmlspecialchars($pageTitle ?? 'Prayogbharti Foundation') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($metaDescription ?? 'Empowering lives through education, scholarships, mentorship, and STEM programs.') ?>">
  <meta property="og:type"        content="website">
</head>
<body>
