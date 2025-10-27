/*
 * jQuery Image Cropper (ICP) plugin
 * - Opens a modal when a file input is used
 * - Lets user pan & zoom image inside a fixed crop box
 * - Crops to specified output size (outputWidth x outputHeight)
 * - Downscales/compresses if final blob > maxFileSize
 * - Returns { blob, dataURL, fileName, mimeType, width, height } via onComplete
 */

(function($){
  const defaults = {
    outputWidth: 800,
    outputHeight: 600,
    aspectRatio: null,
    maxFileSize: 200000,
    mimeType: 'image/jpeg',
    quality: 0.92,
    minQuality: 0.5,
    maxDimension: 4000,
    onOpen: null,
    onClose: null,
    onComplete: null,
    boundingBox: null
  };

  function dataURLToBlob(dataURL){
    const parts = dataURL.split(',');
    const meta = parts[0].match(/:(.*?);/);
    const mime = meta ? meta[1] : '';
    const binary = atob(parts[1]);
    const len = binary.length;
    const u8 = new Uint8Array(len);
    for (let i = 0; i < len; i++) u8[i] = binary.charCodeAt(i);
    return new Blob([u8], { type: mime });
  }

  function clamp(v, a, b){ return Math.max(a, Math.min(b, v)); }

  $.fn.imageCropper = function(opts){
    const settings = $.extend({}, defaults, opts || {});
    if (!settings.outputWidth || !settings.outputHeight) {
      console.error('imageCropper: outputWidth and outputHeight must be specified.');
      return this;
    }

    let modal = null, state = null;

    function createModal(){
      if (modal) return modal;
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

      modal = {
        $overlay, $modal,
        $canvas: $modal.find('.icp-canvas'),
        $cropBox: $modal.find('.icp-crop-box'),
        $zoom: $modal.find('.icp-zoom'),
        $previewCanvas: $modal.find('.icp-preview-canvas'),
        $outputSize: $modal.find('.icp-output-size'),
        $mime: $modal.find('.icp-mime'),
        $confirm: $modal.find('.icp-confirm, .icp-confirm2'),
        $cancel: $modal.find('.icp-cancel, .icp-cancel2')
      };
      return modal;
    }

    function openModal(file, fileName, $triggerInput){
      const m = createModal();
      m.$modal.data('triggerInput', $triggerInput); // ✅ store original input reference
      const canvas = m.$canvas[0];
      const ctx = canvas.getContext('2d');
      const previewCanvas = m.$previewCanvas[0];
      const pctx = previewCanvas.getContext('2d');
      state = {
        img: new Image(),
        imgNaturalWidth: 0,
        imgNaturalHeight: 0,
        translate: { x: 0, y: 0 },
        scale: 1,
        dragging: false,
        lastPointer: null
      };

      m.$mime.text(settings.mimeType);
      m.$outputSize.text(`${settings.outputWidth}px × ${settings.outputHeight}px`);

      const reader = new FileReader();
      reader.onload = function(ev){
        state.img.onload = function(){
          let naturalW = state.img.naturalWidth;
          let naturalH = state.img.naturalHeight;
          if (settings.maxDimension && Math.max(naturalW, naturalH) > settings.maxDimension) {
            const ratio = settings.maxDimension / Math.max(naturalW, naturalH);
            naturalW = Math.round(naturalW * ratio);
            naturalH = Math.round(naturalH * ratio);
            const off = document.createElement('canvas');
            off.width = naturalW;
            off.height = naturalH;
            const octx = off.getContext('2d');
            octx.drawImage(state.img, 0, 0, naturalW, naturalH);
            state.img = new Image();
            state.img.onload = onImageReady;
            state.img.src = off.toDataURL('image/png');
          } else {
            onImageReady();
          }

          function onImageReady(){
            state.imgNaturalWidth = state.img.naturalWidth || naturalW;
            state.imgNaturalHeight = state.img.naturalHeight || naturalH;
            const wrapRect = m.$canvas.parent()[0].getBoundingClientRect();
            const availW = Math.max(300, Math.floor(wrapRect.width));
            const availH = Math.max(200, Math.floor(Math.min(wrapRect.height, window.innerHeight * 0.6)));

            const dpr = window.devicePixelRatio || 1;
            canvas.width = availW * dpr;
            canvas.height = availH * dpr;
            canvas.style.width = availW + 'px';
            canvas.style.height = availH + 'px';
            ctx.setTransform(dpr,0,0,dpr,0,0);
            ctx.imageSmoothingQuality = 'high';
            const scaleFit = Math.min(availW / state.imgNaturalWidth, availH / state.imgNaturalHeight, 1.0);
            state.scale = scaleFit;
            state.translate.x = (availW - state.imgNaturalWidth * state.scale) / 2;
            state.translate.y = (availH - state.imgNaturalHeight * state.scale) / 2;

            const aspect = settings.aspectRatio || (settings.outputWidth / settings.outputHeight);
            const maxCropW = availW * 0.8;
            const maxCropH = availH * 0.8;
            let cropW = maxCropW;
            let cropH = cropW / aspect;
            if (cropH > maxCropH) {
              cropH = maxCropH;
              cropW = cropH * aspect;
            }

            m.$cropBox.css({
              width: cropW + 'px',
              height: cropH + 'px',
              left: (availW - cropW) / 2 + 'px',
              top: (availH - cropH) / 2 + 'px'
            });

            m.$zoom.attr('min', 0.1);
            m.$zoom.attr('max', Math.max(3, state.scale * 5));
            m.$zoom.val(state.scale);

            render();
            drawPreview();
          }
        };
        state.img.src = ev.target.result;
      };
      reader.readAsDataURL(file);

      // show modal
      m.$overlay.fadeIn(100);
      if (typeof settings.onOpen === 'function') settings.onOpen();

      // --- [PAN & ZOOM LOGIC OMITTED FOR BREVITY: SAME AS YOUR ORIGINAL CODE] ---
      // keep your pan/zoom handlers, render(), drawPreview(), produceResult() unchanged

      async function produceResult(){
        if (!state || !state.img) return null;
        const mapping = getCropMapping();
        let sx = Math.max(0, mapping.srcX);
        let sy = Math.max(0, mapping.srcY);
        let sw = mapping.srcW;
        let sh = mapping.srcH;

        const outW = settings.outputWidth;
        const outH = settings.outputHeight;
        const off = document.createElement('canvas');
        off.width = outW;
        off.height = outH;
        const octx = off.getContext('2d');
        octx.fillStyle = settings.mimeType === 'image/png' ? 'rgba(0,0,0,0)' : '#fff';
        octx.fillRect(0,0,outW,outH);
        octx.drawImage(state.img, sx, sy, sw, sh, 0, 0, outW, outH);

        let q = settings.quality;
        let dataURL = off.toDataURL(settings.mimeType, q);
        let blob = dataURLToBlob(dataURL);

        if (settings.maxFileSize && blob.size > settings.maxFileSize) {
          if (settings.mimeType === 'image/jpeg') {
            while (blob.size > settings.maxFileSize && q > settings.minQuality + 0.01) {
              q -= 0.06;
              dataURL = off.toDataURL('image/jpeg', q);
              blob = dataURLToBlob(dataURL);
            }
          }
        }

        return {
          blob, dataURL, mimeType: settings.mimeType,
          width: outW, height: outH,
          fileName: fileName || ('cropped.' + (settings.mimeType==='image/png'?'png':'jpg'))
        };
      }

      m.$confirm.off('click').on('click', async function(){
        m.$confirm.prop('disabled', true).text('Processing...');
        try {
          const result = await produceResult();
          const $input = m.$modal.data('triggerInput'); // ✅ now correctly retrieved
          if ($input && $input.attr('type') === 'file' && result && result.blob) {
            const file = new File([result.blob], result.fileName, { type: result.mimeType });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            $input[0].files = dataTransfer.files;
          }
          if (typeof settings.onComplete === 'function') settings.onComplete(result);
        } catch (err) {
          console.error('Crop error', err);
        } finally {
          m.$confirm.prop('disabled', false).text('Crop & Save');
          closeModal();
        }
      });

      m.$cancel.off('click').on('click', closeModal);

      function closeModal(){
        m.$overlay.fadeOut(120);
        state = null;
        if (typeof settings.onClose === 'function') settings.onClose();
      }

      m.$overlay.off('click').on('click', function(e){
        if (e.target === m.$overlay[0]) closeModal();
      });
    }

    // apply plugin
    this.each(function(){
      const $input = $(this);
      if ($input.attr('type') !== 'file') {
        console.warn('imageCropper: target is not a file input.');
        return;
      }
      $input.off('change.icp').on('change.icp', function(e){
        const files = e.target.files;
        if (!files || !files.length) return;
        const file = files[0];
        if (!file.type.startsWith('image/')) {
          alert('Please select an image file.');
          $input.val('');
          return;
        }
        openModal(file, file.name, $input); // ✅ pass the input reference
        setTimeout(()=> $input.val(''), 0);
      });
    });

    return this;
  };
})(jQuery);
