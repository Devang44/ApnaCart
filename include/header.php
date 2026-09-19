<?php 
if(!isset ($page_title)){
    $page_title='ApnaCart - Ecommerce';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>

    <!-- Bootstrap CSS (Local) -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

    <!-- Bootstrap Icons (Local) -->
    <link rel="stylesheet" href="bootstrap-icons/bootstrap-icons.css">

    <!-- Font Awesome CSS (Local - Casing & .css fixed) -->
    <link rel="stylesheet" href="fontawesome/css/all.min.css">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="style.css">

    <!-- Note: Google Fonts (fonts.googleapis.com) online hone ki wajah se offline chalega nahi. 
         Agar aapko Poppins font 100% offline chahiye, to Poppins font files ko download karke 
         local CSS me @font-face se define karna padega, ya phir default system font use hoga. -->

</head>
<body>