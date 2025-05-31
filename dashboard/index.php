<?php
$baseDir = __DIR__;
$folders = ['json', 'wpu-hut', 'wpu-movie', 'wpu-portfolio', 'wpu-rest-client'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>REST API Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
            padding: 30px;
        }
        h1 {
            color: #333;
        }
        .folder-list, .file-list {
            margin-top: 20px;
        }
        .item {
            background: #fff;
            margin: 8px 0;
            padding: 12px 16px;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h1>REST API Dashboard</h1>

    <div class="folder-list">
        <h2>📁 Daftar Folder</h2>
        <ul>
            <?php foreach ($folders as $folder): ?>
                <li class="item"><a href="?folder=<?= urlencode($folder) ?>"><?= $folder ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php
    if (isset($_GET['folder'])) {
        $selectedFolder = basename($_GET['folder']);
        $path = $baseDir . '/' . $selectedFolder;

        if (is_dir($path)) {
            $files = array_diff(scandir($path), ['.', '..']);
            echo "<div class='file-list'><h2>📂 Isi Folder: $selectedFolder</h2><ul>";
            foreach ($files as $file) {
                $fileUrl = "$selectedFolder/$file";
                echo "<li class='item'><a href='$fileUrl' target='_blank'>$file</a></li>";
            }
            echo "</ul></div>";
        } else {
            echo "<p>❌ Folder tidak ditemukan.</p>";
        }
    }
    ?>

</body>
</html>
