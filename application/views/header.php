<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="<?= SITE_AUTHOR; ?>">
	<title><?= $title ?? SITE_NAME; ?></title>
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Ubuntu">
	<link rel="stylesheet" type="text/css" href="<?= base_url('public/css/libs/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('public/css/libs/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.6/sweetalert2.min.css">
	<?php if (isset($styles)) : ?>
		<?php foreach ($styles as $style) : ?>
			<?php if (is_array($style)) : ?>
				<link rel="stylesheet" type="<?= $style['type']; ?>" href="<?= $style['file']; ?>">
			<?php else : ?>
				<link rel="stylesheet" type="text/css" href="<?= $style; ?>">
			<?php endif ?>
		<?php endforeach ?>
	<?php endif ?>
	<link rel="stylesheet" type="text/css" href="<?= base_url('public/css/styles.css'); ?>">
</head>

<body>