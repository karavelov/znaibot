<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>ЗнайБот - Глава</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        body {
            margin: 0;
            background: radial-gradient(circle, #1e293b 0%, #0f172a 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden;
        }

        #robot-container {
            position: absolute;
            right: 50px;
            width: 1000px;
            perspective: 1000px;
        }

        svg {
            width: 100%;
            filter: drop-shadow(0 30px 60px rgba(0,0,0,0.9));
        }

        .led-glow {
            filter: blur(1.2px) brightness(1.4);
        }

        .controls {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 15px;
            z-index: 10;
        }

        button {
            padding: 12px 24px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 212, 255, 0.3);
            color: #00d4ff;
            cursor: pointer;
            border-radius: 12px;
            backdrop-filter: blur(5px);
            font-weight: bold;
            transition: 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        button:hover {
            background: #00d4ff;
            color: #1e293b;
            box-shadow: 0 0 30px rgba(0, 212, 255, 0.6);
        }
    </style>
</head>
<body>

    <div id="robot-container">
        <svg viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg">
            <g class="led-glow">
                <!-- Очите: Лявото е изместено още наляво (x=65) -->
                <rect id="eyeL" x="65" y="100" width="65" height="45" rx="15" fill="#00d4ff" />
                <rect id="eyeR" x="210" y="100" width="65" height="45" rx="15" fill="#00d4ff" />
                
                <!-- Устата: Изместена още надясно (x=115) -->
                <rect id="mouth" x="115" y="185" width="100" height="10" rx="5" fill="#00d4ff" opacity="0.9" />
            </g>
        </svg>
    </div>

    <!-- <div class="controls">
        <button onclick="animateFace('neutral')">Стандарт</button>
        <button onclick="animateFace('happy')">Радост</button>
        <button onclick="animateFace('alert')">Тревога</button>
        <button onclick="speak()">Говори</button>
    </div> -->

    <script>
        const eyeL = "#eyeL";
        const eyeR = "#eyeR";
        const mouth = "#mouth";

        function animateFace(state) {
            if (state === 'neutral') {
                gsap.to([eyeL, eyeR], { attr: { height: 45, y: 100, width: 65 }, fill: "#00d4ff", duration: 0.5 });
                gsap.to(eyeL, { attr: { x: 65 }, duration: 0.5 });
                gsap.to(mouth, { attr: { width: 100, x: 115, height: 10, y: 185 }, fill: "#00d4ff", duration: 0.5 });
            } 
            else if (state === 'happy') {
                gsap.to([eyeL, eyeR], { attr: { height: 12, y: 115 }, duration: 0.5 });
                gsap.to(mouth, { attr: { width: 140, x: 95, height: 20, y: 175 }, duration: 0.5 });
            }
            else if (state === 'alert') {
                gsap.to([eyeL, eyeR], { fill: "#ff0055", attr: { height: 65, y: 85 }, duration: 0.2 });
                gsap.to(mouth, { fill: "#ff0055", attr: { width: 40, x: 145, height: 40, y: 170 }, duration: 0.2 });
                
                gsap.to("#robot-container", { x: 10, repeat: 5, yoyo: true, duration: 0.05, onComplete: () => gsap.to("#robot-container", {x: 0}) });
            }
        }

        function speak() {
            const tl = gsap.timeline({ repeat: 5, yoyo: true });
            tl.to(mouth, { attr: { height: 40, y: 165, width: 80, x: 125 }, duration: 0.15, ease: "power1.inOut" });
        }

        function blink() {
            gsap.to([eyeL, eyeR], { 
                attr: { height: 0, y: 122.5 }, 
                duration: 0.1, 
                yoyo: true, 
                repeat: 1,
                onComplete: () => {
                    setTimeout(blink, Math.random() * 4000 + 2000);
                }
            });
        }

        blink();

        document.addEventListener('mousemove', (e) => {
            const x = (e.clientX / window.innerWidth - 0.5) * 40;
            const y = (e.clientY / window.innerHeight - 0.5) * 40;
            gsap.to("svg", { rotationY: x, rotationX: -y, duration: 1.5, ease: "power2.out" });
        });
    </script>
</body>
</html>