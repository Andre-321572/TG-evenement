<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Télécharger l'Application Événement</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --navy:    #0f1f4b;
      --blue:    #1e3a8a;
      --accent:  #3b82f6;
      --light:   #60a5fa;
      --white:   #ffffff;
      --glass:   rgba(255,255,255,0.07);
    }

    body {
      font-family: 'Outfit', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: radial-gradient(ellipse at 30% 20%, #1e3a8a 0%, #0f1f4b 50%, #050d20 100%);
      overflow: hidden;
    }

    .particles { position: fixed; inset: 0; pointer-events: none; z-index: 0; }
    .particle {
      position: absolute;
      border-radius: 50%;
      background: rgba(96,165,250,0.15);
      animation: float linear infinite;
    }
    @keyframes float {
      from { transform: translateY(100vh) scale(0); opacity: 0; }
      10%  { opacity: 1; }
      90%  { opacity: 1; }
      to   { transform: translateY(-10vh) scale(1); opacity: 0; }
    }

    .card {
      position: relative; z-index: 1;
      background: var(--glass);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 32px;
      padding: 48px 40px 40px;
      max-width: 480px;
      width: 90%;
      text-align: center;
      box-shadow:
        0 0 0 1px rgba(59,130,246,0.2),
        0 32px 80px rgba(0,0,0,0.5),
        inset 0 1px 0 rgba(255,255,255,0.15);
      animation: fadeUp 0.8s cubic-bezier(.22,1,.36,1) both;
    }
    @keyframes fadeUp {
      from { opacity:0; transform: translateY(40px) scale(0.96); }
      to   { opacity:1; transform: translateY(0) scale(1); }
    }

    .badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(59,130,246,0.18);
      border: 1px solid rgba(59,130,246,0.35);
      border-radius: 999px;
      padding: 6px 16px;
      margin-bottom: 24px;
      animation: pulse-glow 2.5s ease-in-out infinite;
    }
    @keyframes pulse-glow {
      0%,100% { box-shadow: 0 0 0 0 rgba(59,130,246,0); }
      50%      { box-shadow: 0 0 0 8px rgba(59,130,246,0.15); }
    }
    .badge-dot { width:8px; height:8px; border-radius:50%; background:#22c55e; animation: blink 1.5s ease-in-out infinite; }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }
    .badge span { font-size:0.78rem; font-weight:600; color:var(--light); letter-spacing:0.08em; text-transform:uppercase; }

    h1 {
      font-size: clamp(1.5rem, 4vw, 2rem);
      font-weight: 800;
      color: var(--white);
      line-height: 1.2;
      margin-bottom: 8px;
      background: linear-gradient(135deg, #fff 30%, #93c5fd 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .subtitle {
      font-size: 0.95rem;
      color: rgba(255,255,255,0.55);
      margin-bottom: 36px;
    }

    .qr-wrapper {
      position: relative;
      display: inline-block;
      margin-bottom: 32px;
    }
    .qr-frame {
      position: relative;
      background: #fff;
      border-radius: 20px;
      padding: 16px;
      display: inline-block;
      box-shadow:
        0 0 0 1px rgba(59,130,246,0.3),
        0 8px 40px rgba(59,130,246,0.25),
        0 24px 60px rgba(0,0,0,0.4);
      animation: qr-float 4s ease-in-out infinite;
    }
    @keyframes qr-float {
      0%,100% { transform: translateY(0); }
      50%      { transform: translateY(-8px); }
    }
    .qr-frame img {
      display: block;
      width: 220px;
      height: 220px;
      border-radius: 8px;
    }

    .corner {
      position: absolute;
      width: 20px; height: 20px;
      border-color: var(--accent);
      border-style: solid;
    }
    .corner.tl { top:-2px; left:-2px; border-width:3px 0 0 3px; border-radius:4px 0 0 0; }
    .corner.tr { top:-2px; right:-2px; border-width:3px 3px 0 0; border-radius:0 4px 0 0; }
    .corner.bl { bottom:-2px; left:-2px; border-width:0 0 3px 3px; border-radius:0 0 0 4px; }
    .corner.br { bottom:-2px; right:-2px; border-width:0 3px 3px 0; border-radius:0 0 4px 0; }

    .steps {
      display: flex;
      justify-content: center;
      gap: 16px;
      margin-bottom: 28px;
      flex-wrap: wrap;
    }
    .step {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 6px;
      flex: 1;
      min-width: 72px;
    }
    .step-icon {
      width: 42px; height: 42px;
      border-radius: 12px;
      background: rgba(59,130,246,0.18);
      border: 1px solid rgba(59,130,246,0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      transition: transform 0.2s;
    }
    .step-icon:hover { transform: scale(1.1); }
    .step p { font-size: 0.72rem; color: rgba(255,255,255,0.55); font-weight: 500; }
    .step-arrow { color: rgba(255,255,255,0.25); font-size: 1rem; align-self: center; margin-top: -14px; }

    .btn-download {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      width: 100%;
      padding: 16px 24px;
      border-radius: 16px;
      background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%);
      color: #fff;
      font-family: 'Outfit', sans-serif;
      font-size: 1rem;
      font-weight: 700;
      text-decoration: none;
      border: none;
      cursor: pointer;
      box-shadow: 0 4px 24px rgba(59,130,246,0.45);
      transition: all 0.25s ease;
      margin-bottom: 16px;
    }
    .btn-download:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 32px rgba(59,130,246,0.6);
    }

    .info-text {
      font-size: 0.78rem;
      color: rgba(255,255,255,0.35);
    }
    .info-text strong { color: rgba(255,255,255,0.6); }

    @media print {
      body { background: #fff !important; }
      .card { background: #fff !important; border: 2px solid #1e3a8a !important; box-shadow: none !important; backdrop-filter: none !important; }
      h1 { -webkit-text-fill-color: #1e3a8a !important; background: none !important; color: #1e3a8a !important; }
      .particles, .btn-download { display: none !important; }
    }
  </style>
</head>
<body>

  <div class="particles" id="particles"></div>

  <div class="card">
    <div class="badge">
      <span class="badge-dot"></span>
      <span>Application disponible</span>
    </div>

    <h1>Téléchargez notre Application</h1>
    <p class="subtitle">Scannez le QR code avec votre smartphone Android</p>

    <div class="qr-wrapper">
      <div class="qr-frame">
        <span class="corner tl"></span>
        <span class="corner tr"></span>
        <span class="corner bl"></span>
        <span class="corner br"></span>
        <img
          src="https://api.qrserver.com/v1/create-qr-code/?size=440x440&data=https%3A%2F%2Fexpo.dev%2Fartifacts%2Feas%2Fqr71gqaDkJu8EBjDAt09uBMzPfPTZIvEh_ek2k5Gwyg.apk&color=1e3a8a&bgcolor=ffffff&margin=15&format=png&qzone=2"
          alt="QR Code téléchargement APK"
        />
      </div>
    </div>

    <div class="steps">
      <div class="step">
        <div class="step-icon">📷</div>
        <p>Scannez<br>le code</p>
      </div>
      <span class="step-arrow">→</span>
      <div class="step">
        <div class="step-icon">⬇️</div>
        <p>Téléchargez<br>l'APK</p>
      </div>
      <span class="step-arrow">→</span>
      <div class="step">
        <div class="step-icon">✅</div>
        <p>Installez<br>& profitez</p>
      </div>
    </div>

    <a
      href="https://expo.dev/artifacts/eas/qr71gqaDkJu8EBjDAt09uBMzPfPTZIvEh_ek2k5Gwyg.apk"
      class="btn-download"
      download
    >
      ⬇ Téléchargement direct (Android)
    </a>

    <p class="info-text">
      ⚙️ Activez <strong>« Sources inconnues »</strong> dans vos paramètres Android avant d'installer
    </p>
  </div>

  <script>
    const container = document.getElementById('particles');
    for (let i = 0; i < 25; i++) {
      const p = document.createElement('div');
      p.className = 'particle';
      const size = Math.random() * 6 + 3;
      p.style.cssText = `
        width:${size}px; height:${size}px;
        left:${Math.random()*100}%;
        animation-duration:${Math.random()*15+10}s;
        animation-delay:${Math.random()*-20}s;
      `;
      container.appendChild(p);
    }
  </script>
</body>
</html>
