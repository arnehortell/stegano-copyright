(function () {
  document.addEventListener('DOMContentLoaded', () => {
    const overlay = document.createElement('div');
    overlay.id = 'ahSecureOverlay';
    document.body.appendChild(overlay);

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
})();
