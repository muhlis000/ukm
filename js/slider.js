document.addEventListener("DOMContentLoaded", () => {
    console.log("🚀 UKM Slider initialized")
  
    // === KONFIGURASI SLIDER ===
    const config = {
      autoplayDelay: 3000, // 4 detik autoplay
      transitionDuration: 600, // 0.6 detik transisi
      visibleSlides: 5, // Jumlah slide yang terlihat
      slideWidth: 350, // Lebar setiap slide
      slideHeight: 450, // Tinggi setiap slide
      slideGap: 30, // Jarak antar slide
    }
  
    // === AMBIL ELEMEN DOM ===
    const slider = document.querySelector("#featured-ukm .slide")
    const items = document.querySelectorAll("#featured-ukm .slide .item")
    const container = document.querySelector("#featured-ukm .slider-container")
    const indicators = document.querySelectorAll("#featured-ukm .indicator-dot")
  
    // === VALIDASI ELEMEN ===
    if (!slider || !items.length) {
      console.error("❌ Slider elements not found")
      return
    }
  
    console.log(`📊 Found ${items.length} UKM items`)
  
    // === VARIABEL SLIDER ===
    let currentIndex = 0
    let isAnimating = false
    let autoplayTimer = null
    let isHoveringImage = false
  
    // === POSISI SLIDE BERDASARKAN INDEX ===
    const slidePositions = [
      { left: 50, scale: 1.0, opacity: 1.0, zIndex: 9 }, // Slide aktif
      { left: 330, scale: 0.9, opacity: 0.8, zIndex: 8 }, // Slide kedua
      { left: 530, scale: 0.8, opacity: 0.6, zIndex: 7 }, // Slide ketiga
      { left: 670, scale: 0.7, opacity: 0.4, zIndex: 6 }, // Slide keempat
      { left: 770, scale: 0.6, opacity: 0.2, zIndex: 5 }, // Slide kelima
    ]
  
    // === FUNGSI UTAMA: UPDATE POSISI SLIDER ===
    function updateSlider() {
      if (isAnimating) return
  
      isAnimating = true
      console.log(`🔄 Moving to slide ${currentIndex + 1}/${items.length}`)
  
      // Update indicators
      indicators.forEach((indicator, index) => {
        indicator.classList.toggle("active", index === currentIndex)
      })
  
      items.forEach((item, index) => {
        // Hitung posisi relatif dari slide aktif
        let position = index - currentIndex
  
        // Handle cycling: jika posisi negatif, pindah ke belakang
        if (position < 0) {
          position = items.length + position
        }
  
        // Tentukan style berdasarkan posisi
        let style
        if (position < slidePositions.length) {
          // Slide yang terlihat
          style = slidePositions[position]
        } else {
          // Slide yang tersembunyi di belakang
          style = { left: 850, scale: 0.5, opacity: 0.1, zIndex: 0 }
        }
  
        // Set data-position attribute for CSS blur effects
        item.setAttribute("data-position", position)
  
        // Terapkan style ke elemen
        Object.assign(item.style, {
          position: "absolute",
          left: style.left + "px",
          transform: `translateX(0) scale(${style.scale})`,
          opacity: style.opacity,
          zIndex: style.zIndex,
          transition: `all ${config.transitionDuration}ms cubic-bezier(0.25, 0.46, 0.45, 0.94)`,
          width: config.slideWidth + "px",
          height: config.slideHeight + "px",
          top: "25px",
        })
      })
  
      // Reset flag animasi setelah transisi selesai
      setTimeout(() => {
        isAnimating = false
      }, config.transitionDuration)
    }
  
    // === NAVIGASI: SLIDE BERIKUTNYA ===
    function nextSlide() {
      currentIndex = (currentIndex + 1) % items.length
      updateSlider()
      resetAutoplay()
    }
  
    // === NAVIGASI: SLIDE SEBELUMNYA ===
    function prevSlide() {
      currentIndex = (currentIndex - 1 + items.length) % items.length
      updateSlider()
      resetAutoplay()
    }
  
    // === AUTOPLAY FUNCTIONS ===
    function startAutoplay() {
      if (autoplayTimer) clearInterval(autoplayTimer)
  
      if (!isHoveringImage) {
        autoplayTimer = setInterval(nextSlide, config.autoplayDelay)
        console.log("▶️ Autoplay started")
      }
    }
  
    function stopAutoplay() {
      if (autoplayTimer) {
        clearInterval(autoplayTimer)
        autoplayTimer = null
        console.log("⏸️ Autoplay stopped")
      }
    }
  
    function resetAutoplay() {
      stopAutoplay()
      if (!isHoveringImage) {
        setTimeout(startAutoplay, 1000)
      }
    }
  
    // === EVENT LISTENERS ===
    items.forEach((item, index) => {
      item.addEventListener("mouseenter", () => {
        isHoveringImage = true
        stopAutoplay()
        console.log(`🖱️ Hovering on slide ${index + 1} - Autoplay paused`)
      })
  
      item.addEventListener("mouseleave", () => {
        isHoveringImage = false
        console.log(`🖱️ Left slide ${index + 1} - Autoplay will resume`)
  
        setTimeout(() => {
          if (!isHoveringImage) {
            startAutoplay()
          }
        }, 500)
      })
  
      item.addEventListener("click", (e) => {
        if (e.target.closest(".btn") || e.target.closest("button")) {
          return
        }
  
        if (index !== currentIndex && !isAnimating) {
          currentIndex = index
          updateSlider()
          resetAutoplay()
          console.log(`🎯 Jumped to slide ${index + 1}`)
        }
      })
  
      item.style.cursor = "pointer"
    })
  
    // === INDICATOR CLICK EVENTS ===
    indicators.forEach((indicator, index) => {
      indicator.addEventListener("click", () => {
        if (index !== currentIndex && !isAnimating) {
          currentIndex = index
          updateSlider()
          resetAutoplay()
          console.log(`🎯 Indicator clicked: slide ${index + 1}`)
        }
      })
    })
  
    // === KEYBOARD NAVIGATION ===
    document.addEventListener("keydown", (e) => {
      if (document.activeElement.tagName !== "INPUT" && document.activeElement.tagName !== "TEXTAREA") {
        if (e.key === "ArrowLeft") {
          prevSlide()
          console.log("⌨️ Keyboard: Previous slide")
        } else if (e.key === "ArrowRight") {
          nextSlide()
          console.log("⌨️ Keyboard: Next slide")
        } else if (e.key === " ") {
          e.preventDefault()
          if (autoplayTimer) {
            stopAutoplay()
            console.log("⌨️ Keyboard: Autoplay paused")
          } else {
            startAutoplay()
            console.log("⌨️ Keyboard: Autoplay resumed")
          }
        }
      }
    })
  
    // === TOUCH/SWIPE SUPPORT ===
    let touchStartX = 0
    let touchEndX = 0
    let touchStartY = 0
    let touchEndY = 0
  
    slider.addEventListener("touchstart", (e) => {
      touchStartX = e.touches[0].clientX
      touchStartY = e.touches[0].clientY
      stopAutoplay()
      console.log("📱 Touch started - Autoplay paused")
    })
  
    slider.addEventListener("touchmove", (e) => {
      const touchCurrentX = e.touches[0].clientX
      const touchCurrentY = e.touches[0].clientY
      const deltaX = Math.abs(touchCurrentX - touchStartX)
      const deltaY = Math.abs(touchCurrentY - touchStartY)
  
      if (deltaX > deltaY) {
        e.preventDefault()
      }
    })
  
    slider.addEventListener("touchend", (e) => {
      touchEndX = e.changedTouches[0].clientX
      touchEndY = e.changedTouches[0].clientY
  
      const swipeDistanceX = touchStartX - touchEndX
      const swipeDistanceY = touchStartY - touchEndY
      const minSwipeDistance = 50
  
      if (Math.abs(swipeDistanceX) > Math.abs(swipeDistanceY) && Math.abs(swipeDistanceX) > minSwipeDistance) {
        if (swipeDistanceX > 0) {
          nextSlide()
          console.log("📱 Swipe left: Next slide")
        } else {
          prevSlide()
          console.log("📱 Swipe right: Previous slide")
        }
      }
  
      setTimeout(() => {
        if (!isHoveringImage) {
          startAutoplay()
        }
      }, 1000)
    })
  
    // === VISIBILITY API ===
    document.addEventListener("visibilitychange", () => {
      if (document.hidden) {
        stopAutoplay()
        console.log("👁️ Tab hidden - Autoplay paused")
      } else {
        if (!isHoveringImage) {
          setTimeout(startAutoplay, 1000)
          console.log("👁️ Tab visible - Autoplay resumed")
        }
      }
    })
  
    // === INISIALISASI ===
    console.log("🎬 Initializing slider...")
    updateSlider()
  
    setTimeout(() => {
      if (!isHoveringImage) {
        startAutoplay()
      }
    }, 2000)
  
    console.log("✅ UKM Slider ready!")
  
    // === GLOBAL FUNCTIONS ===
    window.sliderGoTo = (index) => {
      if (index >= 0 && index < items.length) {
        currentIndex = index
        updateSlider()
        resetAutoplay()
        console.log(`🎯 Jumped to slide ${index + 1}`)
      }
    }
  
    window.sliderToggleAutoplay = () => {
      if (autoplayTimer) {
        stopAutoplay()
        console.log("⏸️ Manual pause")
      } else {
        startAutoplay()
        console.log("▶️ Manual resume")
      }
    }
  })
  
  // === SHARE UKM FUNCTION ===
  function shareUKM(ukmId, ukmName) {
    if (navigator.share) {
      navigator
        .share({
          title: `UKM ${ukmName}`,
          text: `Lihat profil UKM ${ukmName} di website kami!`,
          url: `${window.location.origin}/ukm/detail.php?id=${ukmId}`,
        })
        .then(() => {
          console.log("✅ Share successful")
        })
        .catch((error) => {
          console.log("❌ Share failed:", error)
          fallbackShare(ukmId, ukmName)
        })
    } else {
      fallbackShare(ukmId, ukmName)
    }
  }
  
  function fallbackShare(ukmId, ukmName) {
    const url = `${window.location.origin}/ukm/detail.php?id=${ukmId}`
    navigator.clipboard
      .writeText(url)
      .then(() => {
        alert(`Link UKM ${ukmName} telah disalin ke clipboard!`)
      })
      .catch(() => {
        prompt("Salin link ini:", url)
      })
  }
  
  console.log("📱 UKM Slider script loaded successfully")
  