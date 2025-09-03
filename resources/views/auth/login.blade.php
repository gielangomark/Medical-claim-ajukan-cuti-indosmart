<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Medical Claim</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: 'Lora', serif;
            background-color: #fdfbf8;
            overflow: hidden;
            margin: 0;
            padding: 0;
            cursor: crosshair;
        }
        
        /* Splash Screen & Animation */
        #splash-screen {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: #fdfbf8; display: flex; align-items: center;
            justify-content: center; z-index: 1000; transition: opacity 0.8s ease-out;
        }
        #splash-screen.hide { opacity: 0; pointer-events: none; }
        #splash-logo {
            height: 120px; width: auto; max-width: 300px;
            animation: logoSplash 3s ease-in-out;
        }
        @keyframes logoSplash {
            0% { transform: scale(0.5); opacity: 0; }
            50% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Header Logo */
        #header-logo {
            position: fixed; top: 20px; left: 50%; transform: translateX(-50%);
            height: 65px; width: auto; max-width: 180px; z-index: 100;
            opacity: 0; transition: opacity 0.5s ease-in-out 0.3s;
        }
        #header-logo.show { opacity: 1; }

        /* Main Content */
        #main-content {
            opacity: 0; transform: translateY(20px);
            transition: opacity 0.8s ease-out 0.5s, transform 0.8s ease-out 0.5s;
        }
        #main-content.show { opacity: 1; transform: translateY(0); }
        
        /* --- STYLES UNTUK ROBOT --- */
        @keyframes head-shake {
    0% { transform: translateX(0) rotate(0deg); }
    15% { transform: translateX(-15px) rotate(-5deg); }
    30% { transform: translateX(15px) rotate(5deg); }
    45% { transform: translateX(-15px) rotate(-5deg); }
    60% { transform: translateX(15px) rotate(5deg); }
    75% { transform: translateX(-8px) rotate(-2deg); }
    90% { transform: translateX(8px) rotate(2deg); }
    100% { transform: translateX(0) rotate(0deg); }
}

@keyframes pulse-glow {
    0%, 100% { 
        box-shadow: 0 0 20px rgba(0, 255, 255, 0.3), 0 0 40px rgba(0, 255, 255, 0.1);
    }
    50% { 
        box-shadow: 0 0 30px rgba(0, 255, 255, 0.6), 0 0 60px rgba(0, 255, 255, 0.2);
    }
}

@keyframes scan-line {
    0% { top: -2px; opacity: 0; }
    50% { opacity: 1; }
    100% { top: 100%; opacity: 0; }
}

@keyframes pupil-scan {
    0%, 100% { transform: translateX(-50%); }
    50% { transform: translateX(50%); }
}

@keyframes success-flash {
    0% { background: #86efac; box-shadow: 0 0 15px #86efac; }
    50% { background: #22c55e; box-shadow: 0 0 30px #22c55e, 0 0 50px #22c55e; }
    100% { background: #86efac; box-shadow: 0 0 15px #86efac; }
}

@keyframes error-flash {
    0% { background: #ef4444; box-shadow: 0 0 15px #ef4444; }
    50% { background: #dc2626; box-shadow: 0 0 30px #dc2626, 0 0 50px #dc2626; }
    100% { background: #ef4444; box-shadow: 0 0 15px #ef4444; }
}

#robot-container {
    position: fixed;
    top: 95px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 99;
    opacity: 0;
    transition: opacity 0.5s ease-in-out 0.3s;
    filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.3));
}

#robot-container.show { 
    opacity: 1; 
}

#robot-face {
    width: 140px;
    height: 100px;
    background: linear-gradient(145deg, #2a2d3a 0%, #1f2027 50%, #17181c 100%);
    border: 2px solid #00ffff;
    border-radius: 35px;
    position: relative;
    box-shadow: 
        0 0 20px rgba(0, 255, 255, 0.3),
        0 0 40px rgba(0, 255, 255, 0.1),
        inset 0 2px 15px rgba(255, 255, 255, 0.05);
    transition: all 0.3s ease;
    animation: pulse-glow 3s ease-in-out infinite;
    overflow: hidden;
}

#robot-face::before {
    content: '';
    position: absolute;
    top: -2px;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, #00ffff, transparent);
    animation: scan-line 2s ease-in-out infinite;
}

#robot-face::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent 49%, rgba(0, 255, 255, 0.1) 50%, transparent 51%);
    background-size: 20px 20px;
    animation: scan-pattern 4s linear infinite;
}

@keyframes scan-pattern {
    0% { background-position: 0 0; }
    100% { background-position: 20px 20px; }
}

.robot-eye {
    width: 100px;
    height: 45px;
    background: linear-gradient(180deg, #1a1a2e 0%, #0f0f1a 100%);
    border: 1px solid #00ffff;
    border-radius: 15px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 
        inset 0 0 15px rgba(0, 255, 255, 0.2),
        0 0 10px rgba(0, 255, 255, 0.1);
}

.robot-eye::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(0, 255, 255, 0.3), transparent);
    animation: eye-sweep 3s ease-in-out infinite;
}

@keyframes eye-sweep {
    0% { left: -100%; }
    50% { left: 100%; }
    100% { left: -100%; }
}

.pupil {
    width: 16px;
    height: 16px;
    background: radial-gradient(circle, #86efac 0%, #22c55e 70%, #16a34a 100%);
    box-shadow: 
        0 0 15px #86efac,
        0 0 30px rgba(134, 239, 172, 0.5),
        inset 0 0 8px rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    transition: all 0.2s ease;
}

.pupil::before {
    content: '';
    position: absolute;
    top: 20%;
    left: 30%;
    width: 30%;
    height: 30%;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 50%;
    filter: blur(1px);
}

/* Scanning mode */
#robot-container.scanning .pupil {
    animation: pupil-scan 2s ease-in-out infinite;
}

/* Eyes closed - visor menyempit */
#robot-container.eyes-closed .robot-eye {
    height: 6px;
    background: linear-gradient(180deg, #ff6b6b 0%, #ee5a24 100%);
    border-color: #ff6b6b;
    box-shadow: 
        inset 0 0 10px rgba(255, 107, 107, 0.3),
        0 0 15px rgba(255, 107, 107, 0.2);
}

#robot-container.eyes-closed .pupil {
    opacity: 0;
    transform: translate(-50%, -50%) scale(0);
}

/* Head shake saat gagal */
body.no-splash #robot-face {
    animation: head-shake 0.8s ease-in-out;
}

body.no-splash #robot-container.eyes-closed .robot-eye {
    animation: error-flash 0.3s ease-in-out 3;
}

/* Success state */
#robot-container.login-success .robot-eye {
    border-color: #22c55e;
    box-shadow: 
        inset 0 0 15px rgba(34, 197, 94, 0.3),
        0 0 20px rgba(34, 197, 94, 0.2);
}

#robot-container.login-success .pupil {
    background: radial-gradient(circle, #22c55e 0%, #16a34a 70%, #15803d 100%);
    box-shadow: 
        0 0 25px #22c55e,
        0 0 50px rgba(34, 197, 94, 0.4),
        inset 0 0 10px rgba(255, 255, 255, 0.4);
    animation: success-flash 0.5s ease-in-out 2;
}

/* Hover effects */
#robot-container:hover #robot-face {
    transform: scale(1.05);
    border-color: #00ff88;
    box-shadow: 
        0 0 30px rgba(0, 255, 136, 0.4),
        0 0 60px rgba(0, 255, 136, 0.2);
}

#robot-container:hover .robot-eye {
    border-color: #00ff88;
    box-shadow: 
        inset 0 0 20px rgba(0, 255, 136, 0.3),
        0 0 15px rgba(0, 255, 136, 0.2);
}

/* Responsive design */
@media (max-width: 768px) {
    #robot-face {
        width: 120px;
        height: 85px;
    }
    
    .robot-eye {
        width: 85px;
        height: 38px;
    }
    
    .pupil {
        width: 14px;
        height: 14px;
    }
}

        /* Form Styles */
        .top-bar { background-color: #5d4037; height: 8px; }
        .form-container { background-color: #fff5f5; border: 1px solid rgba(0, 0, 0, 0.05); }
        .input-field { background-color: #e5e7eb; font-family: 'Lora', serif; transition: all 0.3s ease; }
        .input-field:focus { background-color: #f3f4f6; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
        .login-button { background-color: #6366f1; transition: all 0.3s ease; }
        .login-button:hover { background-color: #4f46e5; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3); }

        /* Utility classes */
        .min-h-screen { min-height: 100vh; } .flex { display: flex; } .flex-col { flex-direction: column; } .justify-center { justify-content: center; } .items-center { align-items: center; } .text-center { text-align: center; } .w-full { width: 100%; } .max-w-md { max-width: 28rem; } .p-4 { padding: 1rem; } .p-3 { padding: 0.75rem; } .p-8 { padding: 2rem; } .mb-6 { margin-bottom: 1.5rem; } .mb-2 { margin-bottom: 0.5rem; } .mt-1 { margin-top: 0.25rem; } .mt-10 { margin-top: 2.5rem; } .pt-4 { padding-top: 1rem; } .space-y-6 > * + * { margin-top: 1.5rem; } .rounded-lg { border-radius: 0.5rem; } .rounded-2xl { border-radius: 1rem; } .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); } .shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); } .text-2xl { font-size: 1.5rem; line-height: 2rem; } .text-sm { font-size: 0.875rem; line-height: 1.25rem; } .text-xs { font-size: 0.75rem; line-height: 1rem; } .text-base { font-size: 1rem; line-height: 1.5rem; } .font-bold { font-weight: 700; } .font-medium { font-weight: 500; } .text-slate-800 { color: #1e293b; } .text-slate-600 { color: #475569; } .text-slate-700 { color: #334155; } .text-white { color: #ffffff; } .text-indigo-500 { color: #6366f1; } .text-indigo-700 { color: #4338ca; } .text-red-500 { color: #ef4444; } .border-transparent { border-color: transparent; } .border { border-width: 1px; } .block { display: block; } .fixed { position: fixed; } .top-0 { top: 0; } .left-0 { left: 0; } .z-10 { z-index: 10; } .focus\:outline-none:focus { outline: 2px solid transparent; outline-offset: 2px; } .focus\:ring-2:focus { box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.5); } .focus\:ring-indigo-400:focus { --tw-ring-color: #818cf8; } .hover\:text-indigo-700:hover { color: #4338ca; } .placeholder-slate-500::placeholder { color: #64748b; } .justify-between { justify-content: space-between; } .h-24 { height: 6rem; }
    </style>
</head>
<body @if($errors->any()) class="no-splash" @endif>

    <div id="splash-screen">
        <img id="splash-logo" src="{{ asset('Indosmart-Update.png') }}" alt="Logo Indosmart" style="height:140px; width:auto; max-width:320px;">
    </div>

    <div class="top-bar fixed top-0 left-0 w-full z-10"></div>

    <img id="header-logo" src="{{ asset('Indosmart-Update.png') }}" alt="Logo Indosmart" style="height:80px; width:auto; max-width:240px; object-fit:contain;">
    
    <div id="robot-container">
        <div id="robot-face">
            <div class="robot-eye left">
                <div class="pupil"></div>
            </div>
            <div class="robot-eye right">
                <div class="pupil"></div>
            </div>
            <div class="robot-mouth"></div>
        </div>
    </div>

    <div id="main-content" class="min-h-screen flex flex-col justify-center items-center p-4">
        <div class="w-full max-w-md">
            <div class="h-24" style="margin-top: 80px;"></div>
            
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-slate-800">Medical Claim & Pengajuan Cuti</h1>
                <p class="text-slate-600 mt-1 text-sm">Silahkan Login untuk melanjutkan</p>
            </div>
            
            <div class="form-container rounded-2xl shadow-lg p-8">
                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="nik" class="block text-sm font-medium text-slate-700 mb-2">NIK (Nomor Induk Karyawan)</label>
                        <input id="nik" name="nik" type="text" required autofocus class="w-full p-3 input-field border-transparent rounded-lg placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-400" placeholder="Masukan NIK Anda">
                        @error('nik')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                            <div class="text-sm">
                                <a href="{{ route('password.request') }}" class="font-medium text-indigo-500 hover:text-indigo-700">Lupa Password?</a>
                            </div>
                        </div>
                        <div style="position: relative;">
                            <input id="password" name="password" type="password" required class="w-full p-3 input-field border-transparent rounded-lg placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-400 pr-10" placeholder="Masukan Password Anda">
                            <span id="togglePassword" style="position: absolute; top: 50%; right: 1rem; transform: translateY(-50%); cursor: pointer;">
                                👁️
                            </span>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-base font-bold text-white login-button">Login</button>
                    </div>
                </form>
            </div>

            <p class="text-center text-sm text-slate-600 mt-10">
                © 2025 Indosmart Komunikasi Global. All rights reserved.
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- ELEMEN SELEKTOR ---
            const splashScreen = document.getElementById('splash-screen');
            const headerLogo = document.getElementById('header-logo');
            const mainContent = document.getElementById('main-content');
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const robotContainer = document.getElementById('robot-container');
            const pupils = document.querySelectorAll('.pupil');
            const loginForm = document.querySelector('.form-container');

            // --- LOGIKA ANIMASI & KONTEN ---
            function showContent(instant = false) {
                if (instant) {
                    splashScreen.style.display = 'none';
                } else {
                    splashScreen.classList.add('hide');
                }
                headerLogo.classList.add('show');
                robotContainer.classList.add('show');
                mainContent.classList.add('show');
                setTimeout(() => {
                    document.body.style.overflow = 'auto';
                }, 1000);
            }

            function runSplashScreen() {
                setTimeout(() => showContent(), 3000);
            }

            if (document.body.classList.contains('no-splash')) {
                showContent(true);
            } else {
                runSplashScreen();
            }

            // --- LOGIKA INTERAKSI ROBOT ---
            // Advanced Robot Face JavaScript
class RobotFace {
    constructor() {
        this.robotContainer = document.getElementById('robot-container');
        this.robotFace = document.getElementById('robot-face');
        this.pupils = document.querySelectorAll('.pupil');
        this.loginForm = document.getElementById('login-form') || document.querySelector('form');
        
        this.isMouseInForm = false;
        this.isScanning = false;
        this.blinkTimer = null;
        this.scanTimer = null;
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.startAutoBlinking();
        this.startRandomScanning();
        this.createParticleEffect();
    }

    setupEventListeners() {
        // Mouse movement untuk pupil tracking
        document.addEventListener('mousemove', (e) => this.movePupils(e));
        
        // Form interaction
        if (this.loginForm) {
            this.loginForm.addEventListener('mouseenter', () => this.onFormEnter());
            this.loginForm.addEventListener('mouseleave', () => this.onFormLeave());
            this.loginForm.addEventListener('focus', () => this.onFormFocus(), true);
            this.loginForm.addEventListener('blur', () => this.onFormBlur(), true);
        }

        // Hover effects
        this.robotContainer.addEventListener('mouseenter', () => this.onRobotHover());
        this.robotContainer.addEventListener('mouseleave', () => this.onRobotLeave());

        // Click interaction
        this.robotFace.addEventListener('click', () => this.onRobotClick());
    }

    movePupils(e) {
        if (this.robotContainer.classList.contains('eyes-closed') || this.isMouseInForm || this.isScanning) {
            return;
        }

        this.pupils.forEach(pupil => {
            const rect = pupil.parentElement.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            
            const deltaX = e.clientX - centerX;
            const deltaY = e.clientY - centerY;
            const distance = Math.sqrt(deltaX * deltaX + deltaY * deltaY);
            
            // Smooth movement with distance consideration
            const maxMove = 10;
            const moveX = Math.max(-maxMove, Math.min(maxMove, deltaX / 10));
            const moveY = Math.max(-maxMove, Math.min(maxMove, deltaY / 10));
            
            pupil.style.transform = `translate(calc(-50% + ${moveX}px), calc(-50% + ${moveY}px))`;
        });
    }

    onFormEnter() {
        this.isMouseInForm = true;
        this.robotContainer.classList.add('scanning');
        this.focusPupilsOnForm();
    }

    onFormLeave() {
        this.isMouseInForm = false;
        this.robotContainer.classList.remove('scanning');
        this.resetPupils();
    }

    onFormFocus() {
        this.isMouseInForm = true;
        this.robotContainer.classList.add('scanning');
        this.focusPupilsOnForm();
    }

    onFormBlur() {
        this.isMouseInForm = false;
        this.robotContainer.classList.remove('scanning');
        this.resetPupils();
    }

    focusPupilsOnForm() {
        if (!this.loginForm) return;
        
        const formRect = this.loginForm.getBoundingClientRect();
        const formCenterX = formRect.left + formRect.width / 2;
        const formCenterY = formRect.top + formRect.height / 2;

        this.pupils.forEach(pupil => {
            const rect = pupil.parentElement.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            
            const deltaX = formCenterX - centerX;
            const deltaY = formCenterY - centerY;
            
            const maxMove = 8;
            const moveX = Math.max(-maxMove, Math.min(maxMove, deltaX / 20));
            const moveY = Math.max(-maxMove, Math.min(maxMove, deltaY / 20));
            
            pupil.style.transform = `translate(calc(-50% + ${moveX}px), calc(-50% + ${moveY}px))`;
        });
    }

    resetPupils() {
        this.pupils.forEach(pupil => {
            pupil.style.transform = 'translate(-50%, -50%)';
        });
    }

    onRobotHover() {
        this.robotFace.style.transform = 'scale(1.05)';
        this.stopAutoBlinking();
    }

    onRobotLeave() {
        this.robotFace.style.transform = 'scale(1)';
        this.startAutoBlinking();
    }

    onRobotClick() {
        // Easter egg - robot wink
        this.wink();
    }

    wink() {
        const rightEye = this.robotFace.querySelector('.robot-eye');
        if (rightEye) {
            rightEye.style.height = '6px';
            setTimeout(() => {
                rightEye.style.height = '45px';
            }, 300);
        }
    }

    blink() {
        if (this.isMouseInForm) return;
        
        this.robotContainer.classList.add('eyes-closed');
        setTimeout(() => {
            this.robotContainer.classList.remove('eyes-closed');
        }, 150);
    }

    startAutoBlinking() {
        this.stopAutoBlinking();
        this.blinkTimer = setInterval(() => {
            if (Math.random() < 0.1) { // 30% chance to blink
                this.blink();
            }
        }, 2000);
    }

    stopAutoBlinking() {
        if (this.blinkTimer) {
            clearInterval(this.blinkTimer);
            this.blinkTimer = null;
        }
    }

    startRandomScanning() {
        this.scanTimer = setInterval(() => {
            if (!this.isMouseInForm && Math.random() < 0.2) { // 20% chance
                this.performScan();
            }
        }, 5000);
    }

    performScan() {
        this.isScanning = true;
        this.robotContainer.classList.add('scanning');
        
        setTimeout(() => {
            this.isScanning = false;
            this.robotContainer.classList.remove('scanning');
        }, 2000);
    }

    showSuccess() {
        this.robotContainer.classList.add('login-success');
        this.robotContainer.classList.remove('eyes-closed');
        
        // Success animation sequence
        setTimeout(() => {
            this.robotContainer.classList.remove('login-success');
        }, 2000);
    }

    showError() {
        this.robotContainer.classList.add('eyes-closed');
        document.body.classList.add('no-splash');
        
        setTimeout(() => {
            this.robotContainer.classList.remove('eyes-closed');
            document.body.classList.remove('no-splash');
        }, 1500);
    }

    createParticleEffect() {
        const particleCount = 50;
        const container = document.createElement('div');
        container.className = 'particles';
        document.body.appendChild(container);

        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 6 + 's';
            particle.style.animationDuration = (Math.random() * 3 + 3) + 's';
            container.appendChild(particle);
        }
    }

    // Public methods untuk integrasi dengan form
    onLoginAttempt() {
        this.performScan();
    }

    onLoginSuccess() {
        this.showSuccess();
    }

    onLoginError() {
        this.showError();
    }

    destroy() {
        this.stopAutoBlinking();
        if (this.scanTimer) {
            clearInterval(this.scanTimer);
        }
    }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.robotFace = new RobotFace();
});

            // Toggle password visibility
            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.textContent = type === 'password' ? '👁️' : '🙈';
                robotContainer.classList.toggle('eyes-closed');
            });

            function triggerLoginSuccess() {
                robotContainer.classList.add('login-success');
            }
        });
    </script>
</body>
</html>