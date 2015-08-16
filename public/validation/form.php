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
                <?php if ( ! empty($messages)): ?>
                    <div class="alert alert-info" role="alert">
                    <?php if ($messages->any()): ?>
                        <?php foreach($messages->all() as $message): ?>
                            <p><?php echo $message; ?></p>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-success" role="alert">
                        <p>Valid email.</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <form action="/validation/" method="post">
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