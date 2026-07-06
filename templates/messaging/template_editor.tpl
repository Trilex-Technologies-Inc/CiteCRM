{* Smarty template for Messaging Template Editor (uses Bootstrap markup) *}
<div class="container-fluid">

  <div class="card shadow-sm">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h2 class="mb-0">Messaging - Templates</h2>
      <div>
        <a href="index.php?page=messaging:list" class="btn btn-sm btn-secondary">Back</a>
      </div>
    </div>

    <div class="card-body">

      <div class="row">
        <div class="col-md-3">
          <h5>Templates</h5>
          <div class="list-group">
            <a href="index.php?page=messaging:templates" class="list-group-item list-group-item-action">+ New template</a>
            {foreach from=$templates_list item=tpl}
              <a href="index.php?page=messaging:templates&slug={$tpl.slug|escape}" class="list-group-item list-group-item-action">{$tpl.title|escape}</a>
            {/foreach}
          </div>
        </div>

        <div class="col-md-9">
          <form method="post" action="index.php?page=messaging:save_template">
            <input type="hidden" name="existing_slug" value="{if $editing}{$editing.slug}{/if}">
            <div class="mb-3">
              <label class="form-label">Title</label>
              <input type="text" name="title" class="form-control" value="{if $editing}{$editing.title|escape}{/if}">
            </div>
            <div class="mb-3">
              <label class="form-label">Slug (optional)</label>
              <input type="text" name="slug" class="form-control" value="{if $editing}{$editing.slug|escape}{/if}">
            </div>
            <div class="mb-3">
              <label class="form-label">Subject</label>
              <input type="text" name="subject" class="form-control" value="{if $editing}{$editing.subject|escape}{/if}">
            </div>

            <div class="mb-2">
              <strong>Placeholders:</strong>
              <div class="mt-2">
                <button type="button" class="btn btn-sm btn-outline-secondary ph-btn" data-ph="&#123;&#123;name&#125;&#125;">&#123;&#123;name&#125;&#125;</button>
                <button type="button" class="btn btn-sm btn-outline-secondary ph-btn" data-ph="&#123;&#123;email&#125;&#125;">&#123;&#123;email&#125;&#125;</button>
                <button type="button" class="btn btn-sm btn-outline-secondary ph-btn" data-ph="&#123;&#123;link&#125;&#125;">&#123;&#123;link&#125;&#125;</button>
                <button type="button" class="btn btn-sm btn-outline-secondary ph-btn" data-ph="&#123;&#123;date&#125;&#125;">&#123;&#123;date&#125;&#125;</button>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">HTML Content</label>
              <textarea id="content" name="content" class="form-control" rows="12">{if $editing}{$editing.content|escape}{/if}</textarea>
            </div>

            <div class="mb-3">
              <button type="submit" class="btn btn-primary">Save Template</button>
            </div>
          </form>

          <h5>Preview</h5>
          <div id="preview" class="border p-3 bg-white">{if $editing}{$editing.content}{/if}</div>
        </div>
      </div>

    </div>
  </div>

</div>

{literal}
<script src="include/tinymce/js/tinymce/tinymce.min.js"></script>
<script>
  tinymce.init({
    selector: 'textarea[name="content"]',
    license_key: 'gpl',
    height: 400,
    menubar: true,
    plugins: 'preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help quickbars emoticons',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    paste_as_text: false,
    valid_elements: '*[*]',
    extended_valid_elements: '*[*]',
    verify_html: false,
    cleanup: false,
    toolbar_mode: 'sliding',
    setup: function (editor) {
      editor.on('PastePreProcess', function (e) { e.content = e.content; });
      editor.on('keyup change input', function () { updatePreview(); });
    }
  });

  function insertPlaceholder(ph) {
    var ed = tinymce.get(document.querySelector('textarea[name="content"]').id) || tinymce.activeEditor;
    if (ed) ed.insertContent(ph);
    else {
      var ta = document.getElementById('content');
      if (ta) {
        var start = ta.selectionStart || 0;
        var end = ta.selectionEnd || 0;
        ta.value = ta.value.substring(0,start) + ph + ta.value.substring(end);
      }
    }
    updatePreview();
  }

  document.addEventListener('click', function(e){
    if (e.target && e.target.classList && e.target.classList.contains('ph-btn')) {
      insertPlaceholder(e.target.getAttribute('data-ph'));
    }
  });

  function updatePreview(){
    var content = '';
    var ed = tinymce.activeEditor;
    if (ed) content = ed.getContent();
    else content = document.getElementById('content').value;
    document.getElementById('preview').innerHTML = content;
  }

  // ensure preview updates after init
  setTimeout(updatePreview,500);
</script>
{/literal}
