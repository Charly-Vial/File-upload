<?php


$uploadDir = 'uploads/';
$it = new FilesystemIterator($uploadDir);

if(!empty($_FILES['files']['name'][0])) {

    $files = $_FILES['files'];
    $allowed = ['jpg', 'png', 'gif'];
    $size_max = 1048576;

    foreach($files['name'] as $position => $file_name) {
        $file_ext = explode('.', $file_name);
        $file_ext = strtolower(end($file_ext));

        $file_tmp = $files['tmp_name'][$position];
        $file_size = $files['size'][$position];
        $file_error = $files['error'][$position];
        $file_type = $files['type'][$position];

        if($file_size >= $size_max) {
            echo "[{$file_name}] is too large, max size = {$size_max} ";
            return;
        }

        if($file_error != 0) {
            echo "[{$file_name}] errored with code {$file_error}";
            return;
        }


        if(!in_array($file_ext, $allowed)) {
            echo "[{$file_name}] file extention '{$file_ext}' is not allowed, only " . implode(", ", $allowed);
            return;
        }
        $file_name_new = uniqid('', true) . '.' . $file_ext;

        $file_destination = $uploadDir . $file_name_new;

        if(move_uploaded_file($file_tmp, $file_destination)) {
            $uploaded[$position] = $file_destination;
            // header('Location: upload.php');
        } else {
            echo "[{$file_name}] failed to upload.";
            return;
        }
    }

    if(!empty($uploaded)) {
        print_r($uploaded);
    }
    if(!empty($failed)) {
        print_r($failed);
    }
}




?>

<form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="imageUpload">Select files to upload</label>
        <input type="file" name="files[]" multiple /><br>
        <input type="submit" value="Upload">
</form>



<?php foreach ($it as $image): ?>
    <figure>
        <img src="uploads/<?= $image->getFilename()?>" alt="<?= $image->getFilename()?>" style="width: 300px">
        <figcaption><?= $image->getFilename() . "\n" ?></figcaption>
    </figure>
<?php endforeach; ?>


