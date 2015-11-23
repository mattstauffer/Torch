<!DOCTYPE html>
<html>
<head>
    <title>Laravel Validation Example</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="margin-top: 20px;">
    <div class="container">
        <div class="row">
            <?php if ($posted): ?>
                <?php if (isset($errors) && $errors->any()): ?>
                    <div class="alert alert-danger" role="alert">
                    <?php foreach ($errors->all() as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-success" role="alert">
                        <p>Valid email.</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <form method="post">
                <h2>Taken emails:</h2>
                <ul class="list-group">
                  <li class="list-group-item">admin@example.com</li>
                  <li class="list-group-item">alan@example.com</li>
                </ul>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="email" value="<?php echo $email; ?>" class="form-control">
                </div>
                <button type="submit" class="btn btn-info">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>
