<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Dashboard</title>

    <link href="<?= base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/datepicker3.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/styles.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/sweetalert2.min.css'); ?>" rel="stylesheet">

    <style>
        body { background-color: #f1f4f7; padding-top: 50px; }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">Log in</div>
                    <div class="panel-body">
                        <form role="form" action="<?= base_url('admin/autentikasi-login');?>" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Username" name="username" type="text" autofocus="" required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" required>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Login</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div></div></div>

    <script src="<?= base_url('assets/js/jquery-1.11.1.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/sweetalert2.min.js'); ?>"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            <?php if (session()->getFlashdata('success')) : ?>
                swal("Success!", "<?= session()->getFlashdata('success'); ?>", "success");
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
                swal("Sorry!", "<?= session()->getFlashdata('error'); ?>", "error");
            <?php endif; ?>

            <?php if (session()->getFlashdata('warning')) : ?>
                swal("Warning!", "<?= session()->getFlashdata('warning'); ?>", "warning");
            <?php endif; ?>

            <?php if (session()->getFlashdata('info')) : ?>
                swal("Info!", "<?= session()->getFlashdata('info'); ?>", "info");
            <?php endif; ?>
        });
    </script>
</body>
</html>