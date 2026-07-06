<?php
// Messaging module: template editor
// Stores templates as JSON in ../../templates/messaging/

$templatesDir = __DIR__ . '/../../templates/messaging';
if (!is_dir($templatesDir)) mkdir($templatesDir, 0755, true);

$files = glob($templatesDir . '/*.json');

// load template
$editing = null;
if (!empty($_GET['slug'])) {
    $path = $templatesDir . '/' . basename($_GET['slug']) . '.json';
    if (is_file($path)) $editing = json_decode(file_get_contents($path), true);
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Messaging Template Editor</title>
    <style>
        body {
            font-family: Arial;
            padding: 16px
        }

        textarea {
            width: 100%;
            height: 280px
        }

        input[type=text] {
            width: 100%
        }

        .col {
            display: inline-block;
            vertical-align: top
        }

        .left {
            width: 28%
        }

        .right {
            width: 70%;
            margin-left: 2%
        }

        .placeholders button {
            margin: 3px
        }
    </style>
</head>

<body>
    <h2>Messaging Template Editor</h2>
    <div class="col left">
        <h3>Templates</h3>
        <div><a href="template_editor.php">+ New template</a></div>
        <div>
            <?php foreach ($files as $f): $data = json_decode(file_get_contents($f), true);
                $slug = basename($f, '.json');
                $title = isset($data['title']) ? $data['title'] : $slug; ?>
                <div><a href="template_editor.php?slug=<?php echo urlencode($slug) ?>"><?php echo htmlspecialchars($title) ?></a></div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col right">
        <form method="post" action="save_template.php">
            <input type="hidden" name="existing_slug" value="<?php echo isset($editing['slug']) ? htmlspecialchars($editing['slug']) : '' ?>">
            <label>Title<br><input type="text" name="title" value="<?php echo isset($editing['title']) ? htmlspecialchars($editing['title']) : '' ?>"></label>
            <label>Slug (optional)<br><input type="text" name="slug" value="<?php echo isset($editing['slug']) ? htmlspecialchars($editing['slug']) : '' ?>"></label>
            <label>Subject<br><input type="text" name="subject" value="<?php echo isset($editing['subject']) ? htmlspecialchars($editing['subject']) : '' ?>"></label>
            <div class="placeholders"><strong>Placeholders:</strong>
                <button type="button" data-ph="{{name}}">{{name}}</button>
                <button type="button" data-ph="{{email}}">{{email}}</button>
                <button type="button" data-ph="{{link}}">{{link}}</button>
                <button type="button" data-ph="{{date}}">{{date}}</button>
            </div>
            <label>HTML Content<br><textarea id="content" name="content"><?php echo isset($editing['content']) ? htmlspecialchars($editing['content']) : '' ?></textarea></label>
            <div style="margin-top:8px"><button type="submit">Save</button></div>
        </form>
        <h4>Preview</h4>
        <div id="preview" style="border:1px solid #ddd;padding:8px;background:#fff"></div>
    </div>
    <script>
        function insertAtCursor(f, v) {
            if (document.selection) {
                f.focus();
                var s = document.selection.createRange();
                s.text = v;
            } else if (f.selectionStart || f.selectionStart === 0) {
                var a = f.selectionStart,
                    b = f.selectionEnd;
                f.value = f.value.substring(0, a) + v + f.value.substring(b);
                f.selectionStart = a + v.length;
                f.selectionEnd = a + v.length;
            } else f.value += v;
            f.focus();
            update();
        }
        document.querySelectorAll('.placeholders button').forEach(function(b) {
            b.addEventListener('click', function() {
                insertAtCursor(document.getElementById('content'), this.getAttribute('data-ph'))
            })
        });

        function update() {
            document.getElementById('preview').innerHTML = document.getElementById('content').value
        }
        document.getElementById('content').addEventListener('input', update);
        update();
    </script>
</body>

</html>