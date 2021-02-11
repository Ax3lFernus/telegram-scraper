<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Un semplice scraper di telegram completamente online!">
    <meta name="author" content="Alessandro Annese, Davide De Salvo">
    <meta property="og:title" content="Telegram Scraper">
    <meta property="og:site_name" content="Telegram Scraper">
    <meta property="og:url" content="<?php $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ?
                "https" : "http") . "://" . $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']); echo $link;?>">
    <meta property="og:description" content="Un semplice scraper di telegram completamente online! Scarica le tue chat in maniera facile e veloce!">
    <meta property="og:type" content="website">
    <meta property="og:image" content="<?php echo $link . '/assets/images/og-image.jpg'; ?>">
    <meta property="og:locale" content="it">
    <link rel="shortcut icon" href="<?php echo $link . '/assets/images/logo.svg'; ?>" />
    <title>Telegram Scraper<?php if(isset($page_title)) echo " · " . $page_title;?></title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <?php if(isset($style)){ echo $style; } ?>
</head>