<svg class="max-w-xs" xmlns="http://www.w3.org/2000/svg" viewBox="55 58 290 208" fill="none">
  <defs>
    <pattern id="dotGrid" width="16" height="16" patternUnits="userSpaceOnUse">
      <circle cx="8" cy="8" r="1" fill="currentColor" opacity="0.12"/>
    </pattern>
    <radialGradient id="glowLight" cx="50%" cy="50%" r="50%">
      <stop offset="0%" stop-color="#d4a96a" stop-opacity="0.18"/>
      <stop offset="100%" stop-color="#d4a96a" stop-opacity="0"/>
    </radialGradient>
    <radialGradient id="glowDark" cx="50%" cy="50%" r="50%">
      <stop offset="0%" stop-color="#f4c87a" stop-opacity="0.12"/>
      <stop offset="100%" stop-color="#f4c87a" stop-opacity="0"/>
    </radialGradient>
    <linearGradient id="pageLeftLight" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#f5ede0"/>
      <stop offset="100%" stop-color="#ede0cc"/>
    </linearGradient>
    <linearGradient id="pageRightLight" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#ede0cc"/>
      <stop offset="100%" stop-color="#e8d8bf"/>
    </linearGradient>
    <linearGradient id="pageLeftDark" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#2a2318"/>
      <stop offset="100%" stop-color="#231d14"/>
    </linearGradient>
    <linearGradient id="pageRightDark" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#231d14"/>
      <stop offset="100%" stop-color="#1e1810"/>
    </linearGradient>
    <linearGradient id="spineGrad" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#a07840" stop-opacity="0.35"/>
      <stop offset="50%" stop-color="#c89850" stop-opacity="0.15"/>
      <stop offset="100%" stop-color="#a07840" stop-opacity="0.35"/>
    </linearGradient>
    <clipPath id="leftPageClip">
      <path d="M65 78 Q66 72 78 70 L190 68 L190 248 L78 250 Q66 252 65 246 Z"/>
    </clipPath>
    <clipPath id="rightPageClip">
      <path d="M210 68 L322 70 Q334 72 335 78 L335 246 Q334 252 322 250 L210 248 Z"/>
    </clipPath>
    <filter id="bookShadow" x="-10%" y="-10%" width="120%" height="130%">
      <feDropShadow dx="0" dy="8" stdDeviation="12" flood-color="#000" flood-opacity="0.18"/>
    </filter>
    <filter id="ghostBlur">
      <feGaussianBlur stdDeviation="0.6"/>
    </filter>
    <style>
      .book-page-left    { fill: url(#pageLeftLight); }
      .book-page-right   { fill: url(#pageRightLight); }
      .book-cover        { fill: #7a4f2e; }
      .page-border       { stroke: #c9a87a; }
      .spine-line        { stroke: #b8935a; }
      .glow-circle       { fill: url(#glowLight); }
      .dot-grid          { fill: url(#dotGrid); color: #8a6a3a; }
      .tree-node         { fill: #c9a87a; stroke: #a07840; }
      .tree-node-empty   { fill: none; stroke: #c9a87a; stroke-dasharray: 4 3; }
      .tree-connector    { stroke: #b8935a; }
      .tree-root         { fill: #a07840; stroke: #7a5a20; }
      .question-mark     { fill: #c9a87a; }
      .magnifier-body    { stroke: #b8935a; }
      .magnifier-glass   { fill: #dfc9a0; fill-opacity: 0.25; }
      .page-lines        { stroke: #c9a87a; stroke-opacity: 0.3; }

      @media (prefers-color-scheme: dark) {
        .book-page-left    { fill: url(#pageLeftDark); }
        .book-page-right   { fill: url(#pageRightDark); }
        .book-cover        { fill: #3d2510; }
        .page-border       { stroke: #7a5a2a; }
        .spine-line        { stroke: #8a6a3a; }
        .glow-circle       { fill: url(#glowDark); }
        .dot-grid          { fill: url(#dotGrid); color: #6a4e28; }
        .tree-node         { fill: #5a4020; stroke: #8a6a3a; }
        .tree-node-empty   { fill: none; stroke: #6a5030; stroke-dasharray: 4 3; }
        .tree-connector    { stroke: #7a5a2a; }
        .tree-root         { fill: #4a3010; stroke: #6a4a18; }
        .question-mark     { fill: #8a6a3a; }
        .magnifier-body    { stroke: #8a6a3a; }
        .magnifier-glass   { fill: #3a2a14; fill-opacity: 0.3; }
        .page-lines        { stroke: #6a4e28; stroke-opacity: 0.25; }
      }
    </style>
  </defs>

  <circle cx="200" cy="162" r="145" class="glow-circle"/>

  <g filter="url(#bookShadow)">
    <path d="M68 82 Q68 76 74 74 L192 72 L192 256 L74 258 Q68 256 68 250 Z" class="book-cover" opacity="0.6"/>
    <path d="M208 72 L326 74 Q332 76 332 82 L332 250 Q332 256 326 258 L208 256 Z" class="book-cover" opacity="0.6"/>

    <path d="M65 78 Q66 72 78 70 L190 68 L190 248 L78 250 Q66 252 65 246 Z" class="book-page-left"/>
    <rect x="65" y="68" width="125" height="182" clip-path="url(#leftPageClip)" class="dot-grid" opacity="0.6"/>
    <g clip-path="url(#leftPageClip)" class="page-lines" stroke-width="0.5">
      <line x1="80" y1="100" x2="185" y2="100"/><line x1="80" y1="116" x2="185" y2="116"/>
      <line x1="80" y1="132" x2="185" y2="132"/><line x1="80" y1="148" x2="185" y2="148"/>
      <line x1="80" y1="164" x2="185" y2="164"/><line x1="80" y1="180" x2="185" y2="180"/>
      <line x1="80" y1="196" x2="185" y2="196"/><line x1="80" y1="212" x2="185" y2="212"/>
      <line x1="80" y1="228" x2="185" y2="228"/>
    </g>
    <path d="M65 78 Q66 72 78 70 L190 68 L190 248 L78 250 Q66 252 65 246 Z" class="page-border" stroke-width="1" fill="none"/>

    <path d="M210 68 L322 70 Q334 72 335 78 L335 246 Q334 252 322 250 L210 248 Z" class="book-page-right"/>
    <rect x="210" y="68" width="125" height="182" clip-path="url(#rightPageClip)" class="dot-grid" opacity="0.6"/>
    <g clip-path="url(#rightPageClip)" class="page-lines" stroke-width="0.5">
      <line x1="215" y1="100" x2="320" y2="100"/><line x1="215" y1="116" x2="320" y2="116"/>
      <line x1="215" y1="132" x2="320" y2="132"/><line x1="215" y1="148" x2="320" y2="148"/>
      <line x1="215" y1="164" x2="320" y2="164"/><line x1="215" y1="180" x2="320" y2="180"/>
      <line x1="215" y1="196" x2="320" y2="196"/><line x1="215" y1="212" x2="320" y2="212"/>
      <line x1="215" y1="228" x2="320" y2="228"/>
    </g>
    <path d="M210 68 L322 70 Q334 72 335 78 L335 246 Q334 252 322 250 L210 248 Z" class="page-border" stroke-width="1" fill="none"/>

    <rect x="190" y="68" width="20" height="180" fill="url(#spineGrad)"/>
    <line x1="200" y1="68" x2="200" y2="248" class="spine-line" stroke-width="1.5"/>
    <path d="M190 68 Q200 62 210 68" fill="none" class="spine-line" stroke-width="1.5"/>
    <path d="M190 248 Q200 254 210 248" fill="none" class="spine-line" stroke-width="1.5"/>
  </g>

  <rect x="113" y="215" width="50" height="22" rx="4" class="tree-root" stroke-width="1.5" filter="url(#ghostBlur)"/>
  <line x1="138" y1="215" x2="138" y2="196" class="tree-connector" stroke-width="1.5"/>
  <line x1="100" y1="196" x2="176" y2="196" class="tree-connector" stroke-width="1.5"/>
  <line x1="100" y1="196" x2="100" y2="177"/>
  <rect x="75" y="155" width="50" height="22" rx="4" class="tree-node" stroke-width="1.5" opacity="0.75" filter="url(#ghostBlur)"/>
  <line x1="176" y1="196" x2="176" y2="177" class="tree-connector" stroke-width="1.5" stroke-dasharray="4 3"/>
  <rect x="151" y="155" width="50" height="22" rx="4" class="tree-node-empty" stroke-width="1.5" opacity="0.6"/>
  <line x1="88" y1="155" x2="88" y2="140" class="tree-connector" stroke-width="1" stroke-dasharray="3 3" opacity="0.5"/>
  <rect x="70" y="120" width="36" height="18" rx="3" class="tree-node-empty" stroke-width="1" opacity="0.4"/>
  <line x1="112" y1="155" x2="112" y2="140" class="tree-connector" stroke-width="1" stroke-dasharray="3 3" opacity="0.5"/>
  <rect x="94" y="120" width="36" height="18" rx="3" class="tree-node-empty" stroke-width="1" opacity="0.35"/>
  <text x="176" y="170" text-anchor="middle" font-family="Georgia, serif" font-size="12" class="question-mark" opacity="0.7">?</text>
  <text x="88" y="132" text-anchor="middle" font-family="Georgia, serif" font-size="10" class="question-mark" opacity="0.5">?</text>
  <text x="112" y="132" text-anchor="middle" font-family="Georgia, serif" font-size="10" class="question-mark" opacity="0.4">?</text>

  <circle cx="268" cy="162" r="42" class="magnifier-glass" stroke-width="0"/>
  <circle cx="268" cy="162" r="40" class="magnifier-body" fill="none" stroke-width="5" opacity="0.85"/>
  <line x1="298" y1="192" x2="322" y2="218" class="magnifier-body" stroke-width="7" stroke-linecap="round" opacity="0.85"/>

  <g clip-path="url(#rightPageClip)">
    <g transform="translate(268,162)" opacity="0.28">
      <rect x="-14" y="-18" width="22" height="28" rx="2" class="tree-node" stroke-width="1"/>
      <path d="M4 -18 L8 -18 L8 -14 L4 -14 Z" class="tree-root" stroke-width="0"/>
    </g>
    <g transform="translate(268,162)" opacity="0.45">
      <rect x="-11" y="-14" width="22" height="28" rx="2" class="tree-node" stroke-width="1"/>
      <path d="M7 -14 L11 -14 L11 -10 L7 -10 Z" class="tree-root" stroke-width="0"/>
      <line x1="-7" y1="-5" x2="7" y2="-5" class="magnifier-body" stroke-width="1" stroke-linecap="round" opacity="0.5"/>
      <line x1="-7" y1="0"  x2="7" y2="0"  class="magnifier-body" stroke-width="1" stroke-linecap="round" opacity="0.5"/>
      <line x1="-7" y1="5"  x2="3" y2="5"  class="magnifier-body" stroke-width="1" stroke-linecap="round" opacity="0.5"/>
    </g>
    <g transform="translate(268,162)" opacity="0.7">
      <rect x="-8" y="-10" width="22" height="28" rx="2" class="tree-node" stroke-width="1.2"/>
      <path d="M10 -10 L14 -10 L14 -6 L10 -6 Z" class="tree-root" stroke-width="0"/>
      <path d="M10 -10 L14 -6 L10 -6 Z" class="magnifier-body" fill="none" stroke-width="1"/>
      <line x1="-3" y1="-1" x2="9" y2="-1" class="magnifier-body" stroke-width="1.2" stroke-linecap="round" opacity="0.55"/>
      <line x1="-3" y1="4"  x2="9" y2="4"  class="magnifier-body" stroke-width="1.2" stroke-linecap="round" opacity="0.55"/>
      <line x1="-3" y1="9"  x2="5" y2="9"  class="magnifier-body" stroke-width="1.2" stroke-linecap="round" opacity="0.55"/>
      <rect x="-3" y="-7" width="8" height="6" rx="1" class="tree-node-empty" stroke-width="0.8" opacity="0.6"/>
      <polyline points="-2,-3 0,-5.5 2,-3" class="magnifier-body" fill="none" stroke-width="0.7" opacity="0.5"/>
      <circle cx="3" cy="-5.5" r="0.8" fill="#b8935a" stroke-width="0" opacity="0.4"/>
    </g>
    <circle cx="268" cy="162" r="28" fill="none" stroke="#a07840" stroke-width="1.2" stroke-dasharray="3 3" opacity="0.25"/>
    <line x1="249" y1="143" x2="287" y2="181" stroke="#a07840" stroke-width="1.5" stroke-linecap="round" opacity="0.18"/>
    <line x1="287" y1="143" x2="249" y2="181" stroke="#a07840" stroke-width="1.5" stroke-linecap="round" opacity="0.18"/>
  </g>

  <path d="M130 248 Q190 256 200 258 Q210 256 270 248" fill="none" stroke="#b8935a" stroke-width="1" opacity="0.2"/>
</svg>
