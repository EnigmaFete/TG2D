/**
 * TG2D MAROC — Fixed Head Page Interactive Scroll Engine
 * Locks the head page completely in place until the 300-frame animation is 100% finished.
 * Once frame 300 is reached, allows natural downward scrolling through the rest of the website.
 */

document.addEventListener('DOMContentLoaded', () => {
  /* ==========================================================================
     1. CONSTANTS & DOM REFERENCES
     ========================================================================== */
  const TOTAL_FRAMES = 300;
  const FRAME_PATH = (index) => `scroll animation/ezgif-frame-${String(index).padStart(3, '0')}.jpg`;

  const canvas = document.getElementById('heroCanvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d', { alpha: false });
  const heroIntro = document.getElementById('heroIntro');
  const heroLoader = document.getElementById('heroLoader');
  const loaderFill = document.getElementById('loaderFill');
  const hudPhase = document.getElementById('hudPhase');
  const hudFrame = document.getElementById('hudFrame');
  const heroCompleteHint = document.getElementById('heroCompleteHint');
  const header = document.getElementById('header');

  // Images cache
  const images = new Array(TOTAL_FRAMES + 1);
  let loadedCount = 0;
  let currentFrame = 1;
  let targetFrame = 1;
  let lastDrawnFrame = -1;
  let isAnimationFinished = false;

  /* ==========================================================================
     2. RESIZE & HI-DPI CRISP CANVAS RENDERING
     ========================================================================== */
  function resizeCanvas() {
    const dpr = Math.min(window.devicePixelRatio || 1, 2);
    const rect = canvas.getBoundingClientRect();
    
    // Set actual render buffer to match device physical pixels
    canvas.width = Math.round(rect.width * dpr);
    canvas.height = Math.round(rect.height * dpr);

    if (images[Math.round(currentFrame)] && images[Math.round(currentFrame)].complete) {
      drawFrame(Math.round(currentFrame));
    }
  }

  function drawFrame(frameIndex) {
    const img = images[frameIndex];
    if (!img || !img.complete || img.naturalWidth === 0) return;

    const w = canvas.width;
    const h = canvas.height;

    // Enable ultra-crisp bicubic interpolation
    ctx.imageSmoothingEnabled = true;
    ctx.imageSmoothingQuality = 'high';

    // Mathematical Object-fit: Cover algorithm in physical pixels
    const imgAspect = img.naturalWidth / img.naturalHeight;
    const canvasAspect = w / h;

    let renderW, renderH, offsetX, offsetY;

    if (canvasAspect > imgAspect) {
      renderW = w;
      renderH = Math.round(w / imgAspect);
      offsetX = 0;
      offsetY = Math.round((h - renderH) / 2);
    } else {
      renderH = h;
      renderW = Math.round(h * imgAspect);
      offsetX = Math.round((w - renderW) / 2);
      offsetY = 0;
    }

    ctx.fillStyle = '#06070a';
    ctx.fillRect(0, 0, w, h);
    ctx.drawImage(img, offsetX, offsetY, renderW, renderH);
    lastDrawnFrame = frameIndex;
  }

  /* ==========================================================================
     3. PROGRESSIVE FRAME PRELOADER
     ========================================================================== */
  function preloadFrames() {
    const firstImg = new Image();
    firstImg.src = FRAME_PATH(1);
    images[1] = firstImg;

    firstImg.onload = () => {
      loadedCount++;
      resizeCanvas();
      drawFrame(1);
      
      streamRemainingBatches(2, 30);
    };

    function streamRemainingBatches(startIdx, batchSize) {
      const endIdx = Math.min(startIdx + batchSize, TOTAL_FRAMES);

      for (let i = startIdx; i <= endIdx; i++) {
        if (!images[i]) {
          const img = new Image();
          img.src = FRAME_PATH(i);
          img.onload = img.onerror = () => {
            loadedCount++;
            updatePreloaderBar();
          };
          images[i] = img;
        }
      }

      if (endIdx < TOTAL_FRAMES) {
        setTimeout(() => {
          streamRemainingBatches(endIdx + 1, batchSize);
        }, 15);
      }
    }

    function updatePreloaderBar() {
      const pct = Math.min(Math.round((loadedCount / TOTAL_FRAMES) * 100), 100);
      if (loaderFill) loaderFill.style.width = `${pct}%`;

      if (loadedCount >= 40 && heroLoader && !heroLoader.classList.contains('hidden')) {
        heroLoader.classList.add('hidden');
      }
    }
  }

  /* ==========================================================================
     4. RENDER LOOP WITH EXTENDED CINEMATIC LERP
     ========================================================================== */
  function renderLoop() {
    // Smoother, weighted cinematic interpolation (0.12)
    const lerpFactor = 0.12;
    currentFrame += (targetFrame - currentFrame) * lerpFactor;

    if (Math.abs(targetFrame - currentFrame) < 0.05) {
      currentFrame = targetFrame;
    }

    const roundedFrame = Math.max(1, Math.min(TOTAL_FRAMES, Math.round(currentFrame)));

    if (roundedFrame !== lastDrawnFrame && images[roundedFrame] && images[roundedFrame].complete) {
      drawFrame(roundedFrame);
    }

    updateHUD(roundedFrame);
    requestAnimationFrame(renderLoop);
  }

  /* ==========================================================================
     5. HARD FIXED SCROLL LOCK ON HEAD PAGE (CALIBRATED EXTENDED DURATION)
     ========================================================================== */
  function handleWheel(e) {
    const atTop = window.scrollY <= 10;

    if (atTop) {
      // 1. If user is scrolling DOWN and animation is NOT finished yet:
      if (e.deltaY > 0 && targetFrame < TOTAL_FRAMES) {
        e.preventDefault();
        
        // Calibrated speed (0.16) adds ~4 seconds of deliberate, luxurious scrubbing duration
        const speed = 0.16;
        targetFrame = Math.min(TOTAL_FRAMES, targetFrame + Math.abs(e.deltaY) * speed);

        // Hide intro text immediately
        if (heroIntro && targetFrame > 3) {
          heroIntro.classList.add('fade-out');
        }

        if (targetFrame >= TOTAL_FRAMES) {
          targetFrame = TOTAL_FRAMES;
          isAnimationFinished = true;
          if (heroCompleteHint) heroCompleteHint.classList.remove('hidden');
        }
        return;
      }

      // 2. If user is at top and scrolling UP:
      if (e.deltaY < 0 && targetFrame > 1) {
        e.preventDefault();
        const speed = 0.16;
        targetFrame = Math.max(1, targetFrame - Math.abs(e.deltaY) * speed);

        if (targetFrame <= 3 && heroIntro) {
          heroIntro.classList.remove('fade-out');
        }
        if (targetFrame < TOTAL_FRAMES) {
          isAnimationFinished = false;
          if (heroCompleteHint) heroCompleteHint.classList.add('hidden');
        }
        return;
      }
    }
  }

  // Touch Support for Mobile
  let touchStartY = 0;
  function handleTouchStart(e) {
    if (e.touches.length > 0) {
      touchStartY = e.touches[0].clientY;
    }
  }

  function handleTouchMove(e) {
    const atTop = window.scrollY <= 10;
    if (!atTop || e.touches.length === 0) return;

    const currentY = e.touches[0].clientY;
    const deltaY = touchStartY - currentY;

    if (deltaY > 0 && targetFrame < TOTAL_FRAMES) {
      e.preventDefault();
      targetFrame = Math.min(TOTAL_FRAMES, targetFrame + deltaY * 0.22);
      touchStartY = currentY;

      if (heroIntro && targetFrame > 3) heroIntro.classList.add('fade-out');
      if (targetFrame >= TOTAL_FRAMES) {
        targetFrame = TOTAL_FRAMES;
        isAnimationFinished = true;
        if (heroCompleteHint) heroCompleteHint.classList.remove('hidden');
      }
    } else if (deltaY < 0 && targetFrame > 1 && window.scrollY <= 10) {
      e.preventDefault();
      targetFrame = Math.max(1, targetFrame - Math.abs(deltaY) * 0.22);
      touchStartY = currentY;

      if (targetFrame <= 3 && heroIntro) heroIntro.classList.remove('fade-out');
      if (targetFrame < TOTAL_FRAMES) {
        isAnimationFinished = false;
        if (heroCompleteHint) heroCompleteHint.classList.add('hidden');
      }
    }
  }

  window.addEventListener('wheel', handleWheel, { passive: false });
  window.addEventListener('touchstart', handleTouchStart, { passive: true });
  window.addEventListener('touchmove', handleTouchMove, { passive: false });

  // Header and scroll listener for rest of page
  window.addEventListener('scroll', () => {
    if (window.scrollY > 40) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  }, { passive: true });

  window.addEventListener('resize', resizeCanvas);

  function updateHUD(frame) {
    if (hudFrame) {
      hudFrame.textContent = `${String(frame).padStart(3, '0')} / ${TOTAL_FRAMES}`;
    }

    if (hudPhase) {
      if (frame < 100) {
        hudPhase.textContent = '01 / FONDATIONS & TERRASSEMENT';
      } else if (frame < 220) {
        hudPhase.textContent = '02 / STRUCTURE & FAÇADES';
      } else {
        hudPhase.textContent = '03 / LIVRAISON CLÉ EN MAIN';
      }
    }
  }

  /* ==========================================================================
     6. METRIC NUMBER ANIMATION ON VIEW
     ========================================================================== */
  let metricsCounted = false;
  function checkMetricsInView() {
    if (metricsCounted) return;
    const metricsSec = document.querySelector('.metrics-section');
    if (!metricsSec) return;

    const rect = metricsSec.getBoundingClientRect();
    if (rect.top < window.innerHeight * 0.85) {
      metricsCounted = true;
      document.querySelectorAll('.metric-value').forEach(el => {
        const target = parseInt(el.getAttribute('data-target'), 10);
        const duration = 1600;
        const stepTime = 20;
        const totalSteps = duration / stepTime;
        const increment = target / totalSteps;
        let current = 0;

        const timer = setInterval(() => {
          current += increment;
          if (current >= target) {
            el.textContent = target.toLocaleString('fr-FR');
            clearInterval(timer);
          } else {
            el.textContent = Math.floor(current).toLocaleString('fr-FR');
          }
        }, stepTime);
      });
    }
  }

  window.addEventListener('scroll', checkMetricsInView, { passive: true });

  /* ==========================================================================
     7. MOBILE DRAWER & NAV ACTIVE STATE
     ========================================================================== */
  const menuToggle = document.getElementById('menuToggle');
  const mobileDrawer = document.getElementById('mobileDrawer');
  if (menuToggle && mobileDrawer) {
    menuToggle.addEventListener('click', () => {
      mobileDrawer.classList.toggle('open');
    });
    document.querySelectorAll('.mob-link').forEach(link => {
      link.addEventListener('click', () => {
        mobileDrawer.classList.remove('open');
      });
    });
  }

  // Active Nav Links
  const navItems = document.querySelectorAll('.nav-item');
  const sections = ['hero', 'manifesto', 'expertise', 'fleet', 'portfolio', 'contact'];

  window.addEventListener('scroll', () => {
    const scrollPos = window.scrollY + 200;
    sections.forEach((id, idx) => {
      const el = document.getElementById(id);
      if (el) {
        const top = el.offsetTop;
        const height = el.offsetHeight;
        if (scrollPos >= top && scrollPos < top + height) {
          navItems.forEach(item => item.classList.remove('active'));
          if (navItems[idx]) navItems[idx].classList.add('active');
        }
      }
    });
  }, { passive: true });

  /* ==========================================================================
     8. RFP FORM SUBMISSION
     ========================================================================== */
  const rfpForm = document.getElementById('rfpForm');
  const rfpSubmit = document.getElementById('rfpSubmit');
  const rfpAlert = document.getElementById('rfpAlert');

  if (rfpForm) {
    rfpForm.addEventListener('submit', (e) => {
      e.preventDefault();
      if (rfpSubmit) {
        rfpSubmit.disabled = true;
        rfpSubmit.innerHTML = '<span>Traitement en cours...</span>';
      }

      setTimeout(() => {
        if (rfpAlert) rfpAlert.classList.remove('hidden');
        rfpForm.reset();
        if (rfpSubmit) {
          rfpSubmit.disabled = false;
          rfpSubmit.innerHTML = '<span>Dossier Transmis avec Succès ✓</span>';
        }
      }, 700);
    });
  }

  /* ==========================================================================
     9. BOOTSTRAP
     ========================================================================== */
  preloadFrames();
  resizeCanvas();
  requestAnimationFrame(renderLoop);
});
