<?php
$isHome = (basename($_SERVER['SCRIPT_NAME']) === 'index.php');
$homePrefix = $isHome ? '' : 'index.php';
?>
<!-- FOOTER -->
<footer class="bg-foreground text-white py-16">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
      <div>
        <div class="flex items-center gap-3 mb-5">
          <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center">
            <span class="text-white font-bold text-sm" style="font-family: 'Playfair Display', serif;">PB</span>
          </div>
          <div>
            <div class="text-lg font-bold" style="font-family: 'Playfair Display', serif;">Prayogbharti Foundation</div>
            <div class="text-xs text-white/40 tracking-widest uppercase">Est. 2004</div>
          </div>
        </div>
        <p class="text-white/60 text-sm leading-relaxed max-w-sm mb-6">
          A non-profit dedicated to transforming education for underprivileged individuals through scholarships, mentorship, and STEM programs across India.
        </p>
        <div class="flex gap-3">
          <?php foreach (['F' => 'Facebook', 'T' => 'Twitter', 'I' => 'Instagram', 'L' => 'LinkedIn'] as $initial => $social): ?>
            <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center hover:bg-primary transition-colors cursor-pointer" title="<?= $social ?>">
              <span class="text-white/60 text-xs"><?= $initial ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div>
        <div class="text-white/40 text-xs font-bold uppercase tracking-widest mb-5">Contact Us</div>
        <div class="space-y-4 text-white/70 text-sm">
          <div class="flex items-start gap-2">
            <i data-lucide="mail" width="16" height="16" class="text-primary flex-shrink-0 mt-0.5"></i>
            <a href="mailto:info@prayogbharti.org" class="hover:text-primary transition-colors">info@prayogbharti.org</a>
          </div>
          <div class="flex items-start gap-2">
            <i data-lucide="map-pin" width="16" height="16" class="text-primary flex-shrink-0 mt-0.5"></i>
            <span class="leading-relaxed text-xs">Plot No. 255-A & 256, G/F, Shyam Vihar, E-Block Extension, Najafgarh, South West New Delhi – 110043, India</span>
          </div>
          <div class="flex items-start gap-2">
            <i data-lucide="globe" width="16" height="16" class="text-primary flex-shrink-0 mt-0.5"></i>
            <a href="https://www.prayogbharti.org" target="_blank" class="hover:text-primary transition-colors">www.prayogbharti.org</a>
          </div>
        </div>
      </div>

      <div>
        <div class="text-white/40 text-xs font-bold uppercase tracking-widest mb-5">Quick Links</div>
        <div class="space-y-3">
          <a href="<?= $homePrefix ?>#home" class="block text-white/70 text-sm hover:text-primary transition-colors duration-200">Home</a>
          <a href="<?= $homePrefix ?>#about" class="block text-white/70 text-sm hover:text-primary transition-colors duration-200">About</a>
          <a href="<?= $homePrefix ?>#programs" class="block text-white/70 text-sm hover:text-primary transition-colors duration-200">Programs</a>
          <a href="<?= $homePrefix ?>#services" class="block text-white/70 text-sm hover:text-primary transition-colors duration-200">Services</a>
          <a href="<?= $homePrefix ?>#impact" class="block text-white/70 text-sm hover:text-primary transition-colors duration-200">Impact</a>
          <a href="<?= $homePrefix ?>#blog" class="block text-white/70 text-sm hover:text-primary transition-colors duration-200">Blog</a>
          <a href="<?= $homePrefix ?>#contact" class="block text-white/70 text-sm hover:text-primary transition-colors duration-200">Contact</a>
        </div>
      </div>

      <div>
        <div class="text-white/40 text-xs font-bold uppercase tracking-widest mb-5">Our Programs</div>
        <div class="space-y-3">
          <?php foreach (["Scholarships", "STEM Coaching", "Mentorship", "Community Outreach", "Workshops", "Annual Reports"] as $p): ?>
            <a href="<?= $homePrefix ?>#programs" class="block text-white/70 text-sm hover:text-primary transition-colors duration-200"><?= $p ?></a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="border-t border-white/10 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
      <p class="text-white/40 text-xs">
        © 2026 Prayogbharti Foundation. All rights reserved. Non-profit registered in India.
      </p>
      <div class="flex gap-6">
        <a href="#" class="text-white/40 text-xs hover:text-primary transition-colors">Privacy Policy</a>
        <a href="#" class="text-white/40 text-xs hover:text-primary transition-colors">Terms of Use</a>
        <a href="#" class="text-white/40 text-xs hover:text-primary transition-colors">Donation Policy</a>
      </div>
    </div>
  </div>
</footer>

<script src="<?= defined('BASE_URL') ? BASE_URL : '' ?>/assets/js/main.js" defer></script>
<script>
// Belt-and-suspenders: init Lucide icons as soon as the DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) window.lucide.createIcons();
  });
} else {
  if (window.lucide) window.lucide.createIcons();
}
</script>

</body>
</html>
