<?php
/**
 * navbar.php – Top info bar + sticky navigation
 * Exact match of React App.tsx top bar and nav
 */
?>
<!-- TOP INFO BAR -->
<div class="fixed top-0 left-0 right-0 z-50 bg-[#F5A623] text-white text-xs font-semibold px-6 lg:px-10 py-2 flex flex-wrap items-center justify-start gap-4 sm:gap-8">
  <a href="mailto:contact@prayogbharti.org" class="flex items-center gap-1.5 hover:text-white/80 transition-colors">
    <i data-lucide="mail" width="12" height="12"></i>
    contact@prayogbharti.org
  </a>
  <a href="tel:+919876543210" class="flex items-center gap-1.5 hover:text-white/80 transition-colors">
    <i data-lucide="phone" width="12" height="12"></i>
    +91 98765 43210
  </a>
</div>

<!-- NAV -->
<nav id="main-navbar" class="fixed left-0 right-0 z-40 transition-all duration-300 bg-white border-b-2 border-primary/40 top-[32px] shadow-md">
  <div class="max-w-7xl mx-auto px-6 lg:px-10 flex items-center justify-between h-18 py-4">
    <a href="#home" class="flex items-center gap-3 group">
      <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center">
        <span class="text-white font-bold text-sm" style="font-family: 'Playfair Display', serif;">PB</span>
      </div>
      <div>
        <div class="text-lg font-bold leading-tight text-foreground" style="font-family: 'Playfair Display', serif;">
          Prayogbharti
        </div>
        <div class="text-xs text-muted-foreground tracking-widest uppercase">Foundation</div>
      </div>
    </a>

    <div class="hidden lg:flex items-center gap-8">
      <a href="#home" class="text-sm font-medium text-foreground/70 hover:text-primary transition-colors duration-200">Home</a>
      <a href="#about" class="text-sm font-medium text-foreground/70 hover:text-primary transition-colors duration-200">About</a>
      <a href="#programs" class="text-sm font-medium text-foreground/70 hover:text-primary transition-colors duration-200">Programs</a>
      <a href="#services" class="text-sm font-medium text-foreground/70 hover:text-primary transition-colors duration-200">Services</a>
      <a href="#impact" class="text-sm font-medium text-foreground/70 hover:text-primary transition-colors duration-200">Impact</a>
      <a href="#blog" class="text-sm font-medium text-foreground/70 hover:text-primary transition-colors duration-200">Blog</a>
      <a href="#contact" class="text-sm font-medium text-foreground/70 hover:text-primary transition-colors duration-200">Contact</a>
      
      <a href="#contact" class="ml-4 bg-primary text-primary-foreground text-sm font-semibold px-5 py-2.5 rounded-full hover:bg-accent transition-colors duration-200">
        Get Involved
      </a>
    </div>

    <button class="lg:hidden p-2 text-foreground" id="mobile-menu-btn" aria-label="Toggle menu">
      <i data-lucide="menu" id="mobile-menu-icon" width="22" height="22"></i>
    </button>
  </div>

  <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-border px-6 py-6 flex-col gap-4">
    <a href="#home" class="text-base font-medium text-foreground/80 hover:text-primary transition-colors mobile-link">Home</a>
    <a href="#about" class="text-base font-medium text-foreground/80 hover:text-primary transition-colors mobile-link">About</a>
    <a href="#programs" class="text-base font-medium text-foreground/80 hover:text-primary transition-colors mobile-link">Programs</a>
    <a href="#services" class="text-base font-medium text-foreground/80 hover:text-primary transition-colors mobile-link">Services</a>
    <a href="#impact" class="text-base font-medium text-foreground/80 hover:text-primary transition-colors mobile-link">Impact</a>
    <a href="#blog" class="text-base font-medium text-foreground/80 hover:text-primary transition-colors mobile-link">Blog</a>
    <a href="#contact" class="text-base font-medium text-foreground/80 hover:text-primary transition-colors mobile-link">Contact</a>
    <a href="#contact" class="mt-2 bg-primary text-white text-center font-semibold px-5 py-3 rounded-full mobile-link">
      Get Involved
    </a>
  </div>
</nav>
