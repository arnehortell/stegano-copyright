<?php
/**
 * Plugin Name: Stegano Copyright Shield
 * Description: Lägg till ett osynligt, dynamiskt copyright-overlay över hela sidan för att skydda innehållet.
 * Version: 1.0.0
 * Author: Arne Hortell
 * Email: arne@hortell.se
 */

add_action('wp_footer', 'ah_secure_overlay_text_alpha');

function ah_secure_overlay_text_alpha() {
  ?>
  <style>
    #ahSecureOverlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      z-index: 999999;
      pointer-events: none;
      background-repeat: repeat;
      image-rendering: pixelated;
    }
  </style>

  <div id="ahSecureOverlay"></div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const overlay = document.getElementById('ahSecureOverlay');

      const message = `COPYRIGHT © ${location.hostname} ${new Date().toISOString().slice(0,10)} | Protected by arne@hortell.se`;

      const canvas = document.createElement('canvas');
      const ctx = canvas.getContext('2d');

      const fontSize = 14;
      const padding = 5;

      ctx.font = `${fontSize}px sans-serif`;
      const textWidth = ctx.measureText(message).width;
      const width = Math.ceil(textWidth + padding * 2);
      const height = fontSize + padding * 2;

      canvas.width = width;
      canvas.height = height;

      const imageData = ctx.createImageData(width, height);
      const data = imageData.data;
      for (let i = 0; i < data.length; i += 4) {
        data[i] = 0;
        data[i+1] = 0;
        data[i+2] = 0;
        data[i+3] = 0;
      }
      ctx.putImageData(imageData, 0, 0);

      ctx.fillStyle = 'rgba(100, 100, 100, 1)';
      ctx.font = `${fontSize}px sans-serif`;
      ctx.fillText(message, padding, fontSize + padding / 2);

      const rendered = ctx.getImageData(0, 0, canvas.width, canvas.height);
      const pixels = rendered.data;
      for (let i = 0; i < pixels.length; i += 4) {
        const isText = pixels[i] > 0 || pixels[i+1] > 0 || pixels[i+2] > 0;
        pixels[i] = 0;
        pixels[i+1] = 255;
        pixels[i+2] = 0;
        pixels[i+3] = isText ? 8 : 0;
      }
      ctx.putImageData(rendered, 0, 0);

      const dataUrl = canvas.toDataURL();
      overlay.style.backgroundImage = `url(${dataUrl})`;
      overlay.style.backgroundSize = `${width}px ${height}px`;
    });
  </script>
  <?php
}
