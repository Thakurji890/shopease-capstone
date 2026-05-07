<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="description" content="<?php echo $pageDesc ?? 'ShopEase - Shop Smart, Shop Easy'; ?>">
  <title>ShopEase | <?php echo $pageTitle ?? 'Home'; ?></title>
  <link rel="icon" href="assets/icons/favicon.ico" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>" />
</head>
<body>
<?php 
// We still use components.js for Nav/Footer injection to keep the 
// transition smooth, but we've centralized the <head>.
?>
