<?php
// Include all PHP logic
require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <title>🔋 Quản lý pin Feliz</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            'sans': ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
          },
          colors: {
            primary: {
              50: '#eff6ff',
              100: '#dbeafe',
              200: '#bfdbfe',
              300: '#93c5fd',
              400: '#60a5fa',
              500: '#3b82f6',
              600: '#2563eb',
              700: '#1d4ed8',
            },
            success: {
              400: '#4ade80',
              500: '#22c55e',
              600: '#16a34a',
            },
            warning: {
              400: '#fbbf24',
              500: '#f59e0b',
              600: '#d97706',
            },
            danger: {
              400: '#f87171',
              500: '#ef4444',
              600: '#dc2626',
            }
          },
          animation: {
            'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            'gradient': 'gradient 8s ease infinite',
          },
          keyframes: {
            gradient: {
              '0%, 100%': { backgroundPosition: '0% 50%' },
              '50%': { backgroundPosition: '100% 50%' },
            }
          }
        }
      }
    }
  </script>
  <style>
    /* Custom scrollbar */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    
    /* Glass effect */
    .glass {
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
    }
    .dark .glass {
      background: rgba(30, 41, 59, 0.85);
    }
    
    /* Gradient background */
    .bg-gradient-animated {
      background: linear-gradient(-45deg, #667eea, #764ba2, #6B8DD6, #8E37D7);
      background-size: 400% 400%;
      animation: gradient 15s ease infinite;
    }
    
    /* Battery fill animation */
    .battery-fill-animate {
      transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Input focus ring */
    input:focus {
      outline: none;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
    }
    
    /* Accordion animation */
    .accordion-content {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .accordion-content.open {
      max-height: 1000px;
    }
    
    /* Button press effect */
    .btn-press:active {
      transform: scale(0.98);
    }
    
    /* Glow effects */
    .glow-success { box-shadow: 0 0 20px rgba(34, 197, 94, 0.4); }
    .glow-warning { box-shadow: 0 0 20px rgba(245, 158, 11, 0.4); }
    .glow-danger { box-shadow: 0 0 20px rgba(239, 68, 68, 0.4); }
  </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100 font-sans antialiased">
  
  <!-- Decorative background elements -->
  <div class="fixed inset-0 overflow-hidden pointer-events-none">
    <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary-400/20 rounded-full blur-3xl"></div>
    <div class="absolute top-1/2 -left-40 w-80 h-80 bg-purple-400/20 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-40 right-1/4 w-80 h-80 bg-pink-400/20 rounded-full blur-3xl"></div>
  </div>

  <div class="relative z-10 max-w-lg mx-auto px-4 py-6 md:py-10">
    


    <!-- Battery Status Card -->
    <div class="glass rounded-3xl p-6 md:p-8 shadow-xl shadow-slate-200/50 mb-6 border border-white/50 ">
      
      <!-- Battery Percentage Display -->
      <div class="text-center mb-6">
        <div class="relative inline-block">
          <span class="text-6xl md:text-7xl font-extrabold text-slate-800 tracking-tight">
            <?php echo $batteryPercentage; ?>
          </span>
          <span class="text-2xl md:text-3xl font-semibold text-slate-500 ">%</span>
        </div>
        

      
      <!-- Battery Bar -->
      <div class="relative h-4 md:h-5 bg-slate-200 rounded-full overflow-hidden mb-6">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-300/50 to-transparent"></div>
        <div 
          class="battery-fill-animate h-full rounded-full relative <?php 
            if ($batteryPercentage <= 20) echo 'bg-gradient-to-r from-danger-500 to-danger-400 glow-danger';
            elseif ($batteryPercentage <= 35) echo 'bg-gradient-to-r from-warning-500 to-warning-400 glow-warning';
            else echo 'bg-gradient-to-r from-success-500 to-success-400 glow-success';
          ?>" 
          style="width: <?php echo $batteryPercentage; ?>%;">
          <div class="absolute inset-0 bg-gradient-to-b from-white/30 to-transparent rounded-full"></div>
        </div>
      </div>
      

    </div>

    <!-- Update Form Card -->
    <div class="glass rounded-3xl p-6 md:p-8 shadow-xl shadow-slate-200/50 mb-6 border border-white/50 ">
      <form method="post">
        <h2 class="text-lg font-bold text-slate-800 mb-5 flex items-center gap-2">
          <span class="w-8 h-8 rounded-xl bg-primary-500/10 flex items-center justify-center">⚡</span>
          Cập Nhật & Sạc Đầy
        </h2>
        
        <div class="space-y-4">
          <!-- Ah Now Input -->
          <div>
            <label for="ah_now" class="block text-sm font-medium text-slate-600 mb-2">
              Số Ah tổng đã đi
            </label>
            <input 
              type="number" 
              step="0.1" 
              id="ah_now" 
              name="ah_now" 
              value="<?php echo $ahNow; ?>"
              class="w-full px-4 py-3 bg-slate-100/80 border border-slate-200 rounded-xl text-slate-800 font-medium transition-all duration-200 hover:border-primary-400 focus:border-primary-500"
            >
          </div>
          
          <!-- Distance Input -->
          <div>
            <label for="distance" class="block text-sm font-medium text-slate-600 mb-2">
              Quãng đường vừa đi (km)
            </label>
            <input 
              type="number" 
              step="0.1" 
              id="distance" 
              name="distance" 
              placeholder="Nhập để tính hiệu suất..."
              class="w-full px-4 py-3 bg-slate-100/80 border border-slate-200 rounded-xl text-slate-800 font-medium placeholder:text-slate-400 transition-all duration-200 hover:border-primary-400 focus:border-primary-500"
            >
          </div>
          
          <!-- Ah 70 Input -->
          <div>
            <label for="ah_70" class="block text-sm font-medium text-slate-600 mb-2">
              Mốc 0%
            </label>
            <input 
              type="number" 
              step="0.1" 
              id="ah_70" 
              name="ah_70" 
              value="<?php echo $ah70; ?>"
              class="w-full px-4 py-3 bg-slate-100/80 border border-slate-200 rounded-xl text-slate-800 font-medium transition-all duration-200 hover:border-primary-400 focus:border-primary-500"
            >
          </div>
        </div>
        
        <!-- Buttons -->
        <div class="flex gap-3 mt-6">
          <button 
            type="submit" 
            name="update_main" 
            class="btn-press flex-1 py-3.5 px-6 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/30 transition-all duration-200"
          >
            Cập nhật
          </button>
          <button 
            type="submit" 
            name="add_70" 
            onclick="return confirm('Xác nhận sạc đầy và gia hạn thêm 70Ah?')"
            class="btn-press flex-1 py-3.5 px-6 bg-gradient-to-r from-success-600 to-success-500 hover:from-success-700 hover:to-success-600 text-white font-semibold rounded-xl shadow-lg shadow-success-500/30 transition-all duration-200"
          >
            🔌 Sạc Đầy
          </button>
        </div>
      </form>
      
      <!-- Distance Result -->
      <?php if (isset($totalDistance)): ?>
      <div class="mt-6 p-4 bg-gradient-to-r from-primary-50 to-purple-50 rounded-2xl border border-primary-200/50 ">
        <div class="flex items-center justify-between">
          <div>
            <div class="text-xs text-slate-500 ">Hiệu suất</div>
            <div class="text-lg font-bold text-primary-600 "><?php echo round($distancePerAh, 2); ?> km/Ah</div>
          </div>
          <div class="text-right">
            <div class="text-xs text-slate-500 ">Dự kiến tổng</div>
            <div class="text-lg font-bold text-slate-800 "><?php echo round($totalDistance, 1); ?> km</div>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <!-- Accordion: Calculations -->
    <div class="glass rounded-3xl shadow-xl shadow-slate-200/50 mb-6 border border-white/50 overflow-hidden">
      <button 
        type="button" 
        class="accordion-toggle w-full p-5 md:p-6 flex items-center justify-between text-left transition-colors hover:bg-slate-50/50 :bg-slate-800/50"
        onclick="toggleAccordion(this)"
      >
        <span class="flex items-center gap-3">
          <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white shadow-lg shadow-purple-500/30">📊</span>
          <span class="font-semibold text-slate-800 ">Tính toán & Tiên lượng</span>
        </span>
        <svg class="accordion-icon w-5 h-5 text-slate-400 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
      
      <div class="accordion-content">
        <div class="p-5 md:p-6 pt-0 space-y-6">
          
          <!-- Estimate Time to Full -->
          <form method="post">
            <h3 class="text-sm font-semibold text-slate-700 mb-3">⏱️ Ước tính thời gian sạc đầy</h3>
            <div class="space-y-3">
              <input 
                type="number" 
                step="0.1" 
                name="charge_current_for_calc" 
                value="9.0" 
                placeholder="Nhập dòng sạc (A)"
                required
                class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-slate-800 font-medium transition-all duration-200 focus:border-primary-500"
              >
              <button 
                type="submit" 
                name="calculate_time_to_full" 
                class="btn-press w-full py-3 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-xl transition-colors"
              >
                Ước Tính Thời Gian
              </button>
            </div>
          </form>
          
          <hr class="border-slate-200 ">
          
          <!-- Calculate Required Current -->
          <form method="post">
            <h3 class="text-sm font-semibold text-slate-700 mb-3">💡 Tính dòng sạc cần thiết</h3>
            <div class="flex gap-3">
              <div class="flex-1">
                <label class="block text-xs text-slate-500 mb-1">Muốn đầy lúc</label>
                <input 
                  type="time" 
                  name="full_by_time" 
                  value="<?php echo date('H:i', strtotime('+3 hours')); ?>"
                  class="w-full px-4 py-3 bg-slate-100/80 border border-slate-200 rounded-xl text-slate-800 font-medium transition-all duration-200 focus:border-primary-500"
                >
              </div>
              <button 
                type="submit" 
                name="calculate_required_current" 
                class="btn-press flex-shrink-0 self-end px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-xl transition-colors whitespace-nowrap"
              >
                Tính
              </button>
            </div>
          </form>
          
          <hr class="border-slate-200 ">
          
          <!-- Simulate Charge -->
          <form method="post">
            <h3 class="text-sm font-semibold text-slate-700 mb-3">📈 Tính thử % pin sạc được</h3>
            <div class="grid grid-cols-3 gap-3 mb-3">
              <div>
                <label class="block text-xs text-slate-500 mb-1">Dòng sạc (A)</label>
                <input 
                  type="number" 
                  step="0.1" 
                  name="sim_charging_current" 
                  value="9.0"
                  class="w-full px-3 py-2.5 bg-slate-100/80 border border-slate-200 rounded-xl text-sm text-slate-800 font-medium transition-all duration-200 focus:border-primary-500"
                >
              </div>
              <div>
                <label class="block text-xs text-slate-500 mb-1">Bắt đầu</label>
                <input 
                  type="time" 
                  name="sim_start_time" 
                  value="<?php echo date('H:i'); ?>"
                  class="w-full px-3 py-2.5 bg-slate-100/80 border border-slate-200 rounded-xl text-sm text-slate-800 font-medium transition-all duration-200 focus:border-primary-500"
                >
              </div>
              <div>
                <label class="block text-xs text-slate-500 mb-1">Kết thúc</label>
                <input 
                  type="time" 
                  name="sim_end_time" 
                  value="<?php echo date('H:i', strtotime('+2 hours')); ?>"
                  class="w-full px-3 py-2.5 bg-slate-100/80 border border-slate-200 rounded-xl text-sm text-slate-800 font-medium transition-all duration-200 focus:border-primary-500"
                >
              </div>
            </div>
            <button 
              type="submit" 
              name="calculate_simulation" 
              class="btn-press w-full py-3 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-xl transition-colors"
            >
              Tính Thử
            </button>
          </form>
          
        </div>
      </div>
    </div>

    <!-- Accordion: Advanced -->
    <div class="glass rounded-3xl shadow-xl shadow-slate-200/50 mb-6 border border-white/50 overflow-hidden">
      <button 
        type="button" 
        class="accordion-toggle w-full p-5 md:p-6 flex items-center justify-between text-left transition-colors hover:bg-slate-50/50 :bg-slate-800/50"
        onclick="toggleAccordion(this)"
      >
        <span class="flex items-center gap-3">
          <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center text-white shadow-lg shadow-orange-500/30">⚙️</span>
          <span class="font-semibold text-slate-800 ">Thao tác Nâng cao</span>
        </span>
        <svg class="accordion-icon w-5 h-5 text-slate-400 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
      
      <div class="accordion-content">
        <div class="p-5 md:p-6 pt-0">
          <form method="post">
            <h3 class="text-sm font-semibold text-slate-700 mb-2">🔌 Cộng Ah sạc lẻ</h3>
            <p class="text-xs text-slate-500 mb-4">
              Dùng khi sạc một lượng nhỏ và không muốn reset mốc "sạc đầy".
            </p>
            
            <div class="grid grid-cols-3 gap-3 mb-4">
              <div>
                <label class="block text-xs text-slate-500 mb-1">Dòng sạc (A)</label>
                <input 
                  type="number" 
                  step="0.1" 
                  name="charging_current" 
                  value="9.0"
                  class="w-full px-3 py-2.5 bg-slate-100/80 border border-slate-200 rounded-xl text-sm text-slate-800 font-medium transition-all duration-200 focus:border-primary-500"
                >
              </div>
              <div>
                <label class="block text-xs text-slate-500 mb-1">Bắt đầu</label>
                <input 
                  type="time" 
                  name="start_time" 
                  value="<?php echo date('H:i'); ?>"
                  class="w-full px-3 py-2.5 bg-slate-100/80 border border-slate-200 rounded-xl text-sm text-slate-800 font-medium transition-all duration-200 focus:border-primary-500"
                >
              </div>
              <div>
                <label class="block text-xs text-slate-500 mb-1">Kết thúc</label>
                <input 
                  type="time" 
                  name="end_time" 
                  value="<?php echo date('H:i', strtotime('+1 hours')); ?>"
                  class="w-full px-3 py-2.5 bg-slate-100/80 border border-slate-200 rounded-xl text-sm text-slate-800 font-medium transition-all duration-200 focus:border-primary-500"
                >
              </div>
            </div>
            
            <button 
              type="submit" 
              name="add_custom_ah" 
              class="btn-press w-full py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold rounded-xl shadow-lg shadow-orange-500/30 transition-all duration-200"
            >
              Cộng Ah Custom
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="text-center text-xs text-slate-400 py-4">
      Made with ❤️ for Feliz riders
    </footer>

  </div>
  
  <script>
    // --- Alert Logic with better UX ---
    <?php if (!empty($alert_message)): ?>
    document.addEventListener('DOMContentLoaded', function() {
      // Create toast notification
      const toast = document.createElement('div');
      toast.className = 'fixed top-4 left-1/2 -translate-x-1/2 z-50 px-6 py-4 bg-slate-800 text-white rounded-2xl shadow-2xl max-w-sm text-center font-medium transform transition-all duration-500 opacity-0 -translate-y-4';
      toast.textContent = '<?php echo addslashes($alert_message); ?>';
      document.body.appendChild(toast);
      
      // Animate in
      setTimeout(() => {
        toast.classList.remove('opacity-0', '-translate-y-4');
      }, 100);
      
      // Animate out
      setTimeout(() => {
        toast.classList.add('opacity-0', '-translate-y-4');
        setTimeout(() => toast.remove(), 500);
      }, 4000);
      
      // Clean URL
      if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
      }
    });
    <?php endif; ?>

    // --- Accordion Toggle ---
    function toggleAccordion(button) {
      const content = button.nextElementSibling;
      const icon = button.querySelector('.accordion-icon');
      
      content.classList.toggle('open');
      icon.style.transform = content.classList.contains('open') ? 'rotate(180deg)' : 'rotate(0deg)';
    }
    
    // Light mode only - no dark mode detection
  </script>
</body>
</html>
