  <script src="js/components.js?v=<?php echo time(); ?>" data-page="<?php echo $currentPage ?? ''; ?>"></script>
  <script src="js/products.js?v=<?php echo time(); ?>"></script>
  <?php 
    if (isset($extraScripts)) {
        foreach ($extraScripts as $script) {
            echo "<script src=\"js/$script.js?v=" . time() . "\"></script>";
        }
    }
  ?>
  <script src="js/script.js?v=<?php echo time(); ?>"></script>
</body>
</html>
