(function ($) {
  $.extend($.summernote.plugins, {
    'bootstrapLayout': function (context) {
      const ui = $.summernote.ui;
      const $editor = context.layoutInfo.editor;
      const $editable = context.layoutInfo.editable;
      const options = context.options;

      // predefined Bootstrap blocks
      const blocks = [
        {
          name: '1 Column',
          html: `<div class="row my-3">
                   <div class="col">
                     <p>Column content</p>
                   </div>
                 </div>`
        },
        {
          name: '2 Columns',
          html: `<div class="row my-3">
                   <div class="col">
                     <p>Column 1</p>
                   </div>
                   <div class="col">
                     <p>Column 2</p>
                   </div>
                 </div>`
        },
        {
          name: '3 Columns',
          html: `<div class="row my-3">
                   <div class="col">
                     <p>Col 1</p>
                   </div>
                   <div class="col">
                     <p>Col 2</p>
                   </div>
                   <div class="col">
                     <p>Col 3</p>
                   </div>
                 </div>`
        },
        {
          name: '4 Columns',
          html: `<div class="row my-3">
                   <div class="col">
                     <p>Col 1</p>
                   </div>
                   <div class="col">
                     <p>Col 2</p>
                   </div>
                   <div class="col">
                     <p>Col 3</p>
                   </div>
                   <div class="col">
                     <p>Col 4</p>
                   </div>
                 </div>`
        },
      ];

      // helper: insert HTML and move cursor inside first col
      function insertBlock(html) {
        const node = $(html)[0];
        context.invoke('editor.insertNode', node);

        // move caret into the first <p> inside the new block
        const firstP = $(node).find('p').get(0);
        if (firstP) {
          const range = document.createRange();
          range.selectNodeContents(firstP);
          range.collapse(true);
          const sel = window.getSelection();
          sel.removeAllRanges();
          sel.addRange(range);
        }

        context.invoke('editor.focus');
      }

      // create button
      context.memo('button.bootstrapLayout', function () {
        return ui.buttonGroup([
          ui.button({
            className: 'dropdown-toggle',
            contents: '<i class="note-icon-layout"></i> Layout <span class="caret"></span>',
            tooltip: 'Insert Bootstrap 5 layout',
            data: {
              toggle: 'dropdown'
            }
          }),
          ui.dropdown({
            className: 'dropdown-style',
            items: blocks.map(b => b.name),
            callback: function ($dropdown) {
              $dropdown.find('li').each(function (index) {
                $(this).click(function () {
                  insertBlock(blocks[index].html);
                });
              });
            }
          })
        ]).render();
      });
    }
  });
})(jQuery);