export class PxCropper {
  constructor() {
    this.defaults = {
      outputWidth: 800,
      outputHeight: 600,
      aspectRatio: null,
      maxFileSize: 95000,
      allowedMb: 10,
      mimeType: "image/jpeg",
      quality: 0.92,
      minQuality: 0.5,
      maxDimension: 4000,
      onOpen: null,
      onClose: null,
      onComplete: null,
      boundingBox: null,
    };
    this.modal = null;
    this.state = null;
  }

  dataURLToBlob(dataURL) {
    const parts = dataURL.split(',');
    const meta = parts[0].match(/:(.*?);/);
    const mime = meta ? meta[1] : '';
    const binary = atob(parts[1]);
    const len = binary.length;
    const u8 = new Uint8Array(len);
    for (let i = 0; i < len; i++) u8[i] = binary.charCodeAt(i);
    return new Blob([u8], { type: mime });
  }

  initCropper(elementId, options = {}) {
    const settings = { ...this.defaults, ...options };
    const $input = $('#' + elementId);

    if (!$input.length) {
      console.error(`Element with id "${elementId}" not found.`);
      return;
    }

    if ($input.attr('type') !== 'file') {
      console.warn('PxCropper: target element is not a file input.');
      return;
    }

    $input.off('change.icp').on('change.icp', (e) => {
      const files = e.target.files;
      if (!files || !files.length) return;
      const file = files[0];

      if (!file.type.startsWith('image/')) {
        alert('Please select an image file.');
        $input.val('');
        return;
      }

      const maxBytes = settings.allowedMb ? settings.allowedMb * 1024 * 1024 : 1048576;
      if (file.size > maxBytes) {
        alert(`Selected file is too large to upload or process. Allowed ${settings.allowedMb || 1} MB.`);
        $input.val('');
        return;
      }

      this.openModal(file, file.name, $input, settings);
      setTimeout(() => $input.val(''), 0);
    });
  }

  createModal(settings) {
    if (this.modal) return this.modal;

    const $overlay = $('<div class="icp-overlay" style="display:none"></div>');
    const $modal = $(`
            <div class="icp-modal" role="dialog" aria-modal="true" aria-label="Image cropper">
                <div class="icp-header">
                    <div>
                        <div class="icp-title">Crop Image</div>
                        <div class="icp-hint">Drag to pan; mousewheel to zoom; use slider to adjust zoom.</div>
                    </div>
                    <div>
                        <button class="icp-btn secondary icp-cancel">Cancel</button>
                        <button class="icp-btn icp-confirm">Crop & Save</button>
                    </div>
                </div>
                <div class="icp-body">
                    <div class="icp-canvas-wrap">
                        <canvas class="icp-canvas"></canvas>
                        <div class="icp-crop-box" aria-hidden="true"></div>
                    </div>
                    <div class="icp-controls">
                        <div>
                            <label>Zoom</label>
                            <input type="range" class="icp-zoom" min="0.1" max="3" step="0.01" value="1">
                        </div>
                        <div>
                            <label>Preview output size</label>
                            <div class="icp-preview" style="border:1px solid #ddd; padding:6px; text-align:center;">
                                <canvas class="icp-preview-canvas" width="200" height="150" style="max-width:100%;height:auto"></canvas>
                            </div>
                        </div>
                        <div style="margin-top:auto">
                            <div style="font-size:13px;color:#444">Output: <span class="icp-output-size"></span></div>
                            <div style="font-size:12px;color:#666;margin-top:6px">Format: <span class="icp-mime"></span></div>
                        </div>
                    </div>
                </div>
                <div class="icp-footer">
                    <button class="icp-btn secondary icp-cancel2">Cancel</button>
                    <button class="icp-btn icp-confirm2">Crop & Save</button>
                </div>
            </div>
        `);

    $overlay.append($modal);
    $('body').append($overlay);

    this.modal = {
      $overlay,
      $modal,
      $canvas: $modal.find(".icp-canvas"),
      $cropBox: $modal.find(".icp-crop-box"),
      $zoom: $modal.find(".icp-zoom"),
      $previewCanvas: $modal.find(".icp-preview-canvas"),
      $outputSize: $modal.find(".icp-output-size"),
      $mime: $modal.find(".icp-mime"),
      $confirm: $modal.find(".icp-confirm, .icp-confirm2"),
      $cancel: $modal.find(".icp-cancel, .icp-cancel2"),
      settings
    };

    return this.modal;
  }

  openModal(file, fileName, $input, settings) {
    const m = this.createModal(settings);
    m.$modal.data("triggerInput", $input);

    const canvas = m.$canvas[0];
    const ctx = canvas.getContext("2d");
    const previewCanvas = m.$previewCanvas[0];
    const pctx = previewCanvas.getContext("2d");

    this.state = {
      img: new Image(),
      imgNaturalWidth: 0,
      imgNaturalHeight: 0,
      translate: { x: 0, y: 0 },
      scale: 1,
      dragging: false,
      lastPointer: null,
    };

    m.$mime.text(settings.mimeType);
    m.$outputSize.text(`${settings.outputWidth}px × ${settings.outputHeight}px`);

    const reader = new FileReader();
    reader.onload = (e) => {
      this.state.img.onload = () => this.onImageLoad(m, settings);
      this.state.img.src = e.target.result;
    };
    reader.readAsDataURL(file);

    m.$overlay.fadeIn(100);
    if (typeof settings.onOpen === "function") settings.onOpen();

    this.bindEvents(canvas, m, settings,fileName);
  }

  bindEvents(canvas, m, settings,fileName) {
    const self = this;
    let dragging = false;

    const getPointer = (e) => e.touches && e.touches.length ? { x: e.touches[0].clientX, y: e.touches[0].clientY } : { x: e.clientX, y: e.clientY };
    const toCanvasCoords = (x, y) => {
      const rect = canvas.getBoundingClientRect();
      return { x: x - rect.left, y: y - rect.top };
    };

    const startDrag = (e) => {
      e.preventDefault();
      dragging = true;
      const p = getPointer(e);
      self.state.lastPointer = toCanvasCoords(p.x, p.y);
    };

    const drag = (e) => {
      if (!dragging) return;
      e.preventDefault();
      const p = getPointer(e);
      const cur = toCanvasCoords(p.x, p.y);
      const dx = cur.x - self.state.lastPointer.x;
      const dy = cur.y - self.state.lastPointer.y;
      self.state.translate.x += dx;
      self.state.translate.y += dy;
      self.state.lastPointer = cur;
      self.renderCanvas(m);
      self.renderPreview(m);
    };

    const endDrag = () => dragging = false;

    const zoomWheel = (e) => {
      e.preventDefault();

      const factor = e.deltaY < 0 ? 1.08 : 0.92; // scroll up = zoom in, scroll down = zoom out
      const pointer = toCanvasCoords(e.clientX, e.clientY);
      const oldScale = self.state.scale;
      let newScale = oldScale * factor;

      // Calculate minimum scale so image always covers bounding box
      const cropBoxRect = m.$cropBox[0].getBoundingClientRect();
      const imgW = self.state.img.naturalWidth;
      const imgH = self.state.img.naturalHeight;

      // Minimum scale = crop box size relative to image natural size
      const minScaleX = cropBoxRect.width / imgW;
      const minScaleY = cropBoxRect.height / imgH;
      const minScale = Math.max(minScaleX, minScaleY) * 0.6;

      const maxScale = 10; // optional max scale

      // Apply limits
      if (factor < 1) {
        // zooming out → stop at minScale
        newScale = Math.max(newScale, minScale);
      } else {
        // zooming in → stop at maxScale
        newScale = Math.min(newScale, maxScale);
      }

      const dx = (pointer.x - self.state.translate.x) / oldScale;
      const dy = (pointer.y - self.state.translate.y) / oldScale;

      self.state.scale = newScale;
      self.state.translate.x = pointer.x - dx * newScale;
      self.state.translate.y = pointer.y - dy * newScale;

      m.$zoom.val(newScale);
      self.renderCanvas(m);
      self.renderPreview(m);
    };

    const zoomInput = () => {
      const val = parseFloat(m.$zoom.val());
      const box = m.$cropBox[0].getBoundingClientRect();
      const rect = canvas.getBoundingClientRect();
      const cx = (box.left + box.right) / 2 - rect.left;
      const cy = (box.top + box.bottom) / 2 - rect.top;
      const oldScale = self.state.scale;
      const dx = (cx - self.state.translate.x) / oldScale;
      const dy = (cy - self.state.translate.y) / oldScale;
      self.state.scale = val;
      self.state.translate.x = cx - dx * val;
      self.state.translate.y = cy - dy * val;
      self.renderCanvas(m);
      self.renderPreview(m);
    };

    canvas.addEventListener("mousedown", startDrag);
    canvas.addEventListener("touchstart", startDrag, { passive: false });
    window.addEventListener("mousemove", drag);
    window.addEventListener("touchmove", drag, { passive: false });
    window.addEventListener("mouseup", endDrag);
    window.addEventListener("touchend", endDrag);
    canvas.addEventListener("wheel", zoomWheel, { passive: false });
    m.$zoom.on("input change", zoomInput);

    const closeModal = () => {
      m.$overlay.fadeOut(120);
      self.state = null;
      if (typeof settings.onClose === "function") settings.onClose();
    };

    m.$confirm.off("click").on("click", async () => {
      m.$confirm.prop("disabled", true).text("Processing...");
      try {
        const result = await self.produceResult(m, settings,fileName);
        const inputEl = m.$modal.data("triggerInput");
        if (inputEl && result && result.blob) {
          const file = new File([result.blob], result.fileName, { type: result.mimeType });
          const dt = new DataTransfer();
          dt.items.add(file);
          inputEl[0].files = dt.files;
          if (inputEl[0].nextElementSibling?.tagName === "LABEL") {
            inputEl[0].nextElementSibling.textContent = file.name;
          } else {
            inputEl.attr("title", file.name);
          }
        }
        if (typeof settings.onComplete === "function") settings.onComplete(result);
      } catch (err) {
        console.error("Crop error", err);
      } finally {
        m.$confirm.prop("disabled", false).text("Crop & Save");
        closeModal();
      }
    });

    m.$cancel.off("click").on("click", closeModal);
    m.$overlay.off("click").on("click", (e) => {
      if (e.target === m.$overlay[0]) closeModal();
    });
  }

  onImageLoad(m, settings) {
    const canvas = m.$canvas[0];
    const ctx = canvas.getContext("2d");
    const parentRect = m.$canvas.parent()[0].getBoundingClientRect();
    const width = Math.max(300, Math.floor(parentRect.width));
    const height = Math.max(200, Math.floor(Math.min(parentRect.height, 0.6 * window.innerHeight)));
    const dpr = window.devicePixelRatio || 1;

    canvas.width = width * dpr;
    canvas.height = height * dpr;
    canvas.style.width = width + "px";
    canvas.style.height = height + "px";
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    ctx.imageSmoothingQuality = "high";

    const scaleFit = Math.min(width / this.state.img.naturalWidth, height / this.state.img.naturalHeight, 1);
    this.state.scale = scaleFit;
    this.state.translate.x = (width - this.state.img.naturalWidth * scaleFit) / 2;
    this.state.translate.y = (height - this.state.img.naturalHeight * scaleFit) / 2;

    let cropW, cropH;
    if (settings.boundingBox?.width && settings.boundingBox?.height) {
      cropW = settings.boundingBox.width;
      cropH = settings.boundingBox.height;
    } else {
      const aspect = settings.aspectRatio || settings.outputWidth / settings.outputHeight;
      cropW = 0.8 * width;
      cropH = cropW / aspect;
      if (cropH > 0.8 * height) {
        cropH = 0.8 * height;
        cropW = cropH * aspect;
      }
    }

    m.$cropBox.css({
      width: cropW + "px",
      height: cropH + "px",
      left: (width - cropW) / 2 + "px",
      top: (height - cropH) / 2 + "px",
    });

    m.$zoom.attr("min", 0.1);
    m.$zoom.attr("max", Math.max(3, 5 * this.state.scale));
    m.$zoom.val(this.state.scale);

    this.renderCanvas(m);
    this.renderPreview(m);
  }

  renderCanvas(m) {
    const canvas = m.$canvas[0];
    const ctx = canvas.getContext("2d");
    const dpr = window.devicePixelRatio || 1;
    const width = canvas.width / dpr;
    const height = canvas.height / dpr;

    ctx.clearRect(0, 0, width, height);
    ctx.fillStyle = "#222";
    ctx.fillRect(0, 0, width, height);

    ctx.save();
    ctx.translate(this.state.translate.x, this.state.translate.y);
    ctx.scale(this.state.scale, this.state.scale);
    ctx.drawImage(this.state.img, 0, 0);
    ctx.restore();

    const cropBox = m.$cropBox[0].getBoundingClientRect();
    const canvasRect = canvas.getBoundingClientRect();
    const x = cropBox.left - canvasRect.left;
    const y = cropBox.top - canvasRect.top;
    const w = cropBox.width;
    const h = cropBox.height;

    ctx.fillStyle = "rgba(0,0,0,0.45)";
    ctx.fillRect(0, 0, width, y);
    ctx.fillRect(0, y, x, h);
    ctx.fillRect(x + w, y, width - (x + w), h);
    ctx.fillRect(0, y + h, width, height - (y + h));
  }

  renderPreview(m) {
    const canvas = m.$canvas[0];
    const cropBox = m.$cropBox[0].getBoundingClientRect();
    const canvasRect = canvas.getBoundingClientRect();

    const srcX = (cropBox.left - canvasRect.left - this.state.translate.x) / this.state.scale;
    const srcY = (cropBox.top - canvasRect.top - this.state.translate.y) / this.state.scale;
    const srcW = cropBox.width / this.state.scale;
    const srcH = cropBox.height / this.state.scale;

    const pcanvas = m.$previewCanvas[0];
    const ctx = pcanvas.getContext("2d");
    ctx.clearRect(0, 0, pcanvas.width, pcanvas.height);
    ctx.fillStyle = "#fff";
    ctx.fillRect(0, 0, pcanvas.width, pcanvas.height);
    ctx.save();
    ctx.drawImage(this.state.img, srcX, srcY, srcW, srcH, 0, 0, pcanvas.width, pcanvas.height);
    ctx.restore();
  }

  async produceResult(m, settings,fileName='cropped') {
    if (!this.state || !this.state.img) return null;

    const cropBox = m.$cropBox[0].getBoundingClientRect();
    const canvasRect = m.$canvas[0].getBoundingClientRect();

    let sx = (cropBox.left - canvasRect.left - this.state.translate.x) / this.state.scale;
    let sy = (cropBox.top - canvasRect.top - this.state.translate.y) / this.state.scale;
    let sw = cropBox.width / this.state.scale;
    let sh = cropBox.height / this.state.scale;

    const outW = settings.outputWidth;
    const outH = settings.outputHeight;
    const off = document.createElement("canvas");
    off.width = outW;
    off.height = outH;
    const octx = off.getContext("2d");

    octx.fillStyle = settings.mimeType === "image/png" ? "rgba(0,0,0,0)" : "#fff";
    octx.fillRect(0, 0, outW, outH);
    octx.drawImage(this.state.img, sx, sy, sw, sh, 0, 0, outW, outH);

    let q = settings.quality;
    let dataURL = off.toDataURL(settings.mimeType, q);
    let blob = this.dataURLToBlob(dataURL);

    if (settings.maxFileSize && blob.size > settings.maxFileSize && settings.mimeType === "image/jpeg") {
      while (blob.size > settings.maxFileSize && q > settings.minQuality + 0.01) {
        q -= 0.06;
        dataURL = off.toDataURL("image/jpeg", q);
        blob = this.dataURLToBlob(dataURL);
      }
    }
    return {
      blob,
      dataURL,
      mimeType: settings.mimeType,
      width: outW,
      height: outH,
      fileName,
    };
  }
}
